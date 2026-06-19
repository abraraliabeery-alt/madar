import argparse
import json
import os
from pathlib import Path

import cv2
import numpy as np


def _ensure_dir(path: Path) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)


def _to_feature(parcel_id: int, ring: np.ndarray) -> dict:
    coords = ring.reshape(-1, 2).astype(float).tolist()
    if len(coords) < 3:
        return {}

    if coords[0] != coords[-1]:
        coords.append(coords[0])

    return {
        "type": "Feature",
        "properties": {"parcel_id": parcel_id},
        "geometry": {
            "type": "Polygon",
            "coordinates": [coords],
        },
    }


def extract_parcels_pixels(
    image_path: Path,
    out_geojson_path: Path,
    out_debug_path: Path,
    min_area_ratio: float,
    max_area_ratio: float,
    dilate_px: int,
    close_px: int,
    approx_eps_ratio: float,
) -> tuple[int, dict]:
    img_bgr = cv2.imread(str(image_path), cv2.IMREAD_UNCHANGED)
    if img_bgr is None:
        raise FileNotFoundError(f"Cannot read image: {image_path}")

    if img_bgr.ndim == 3 and img_bgr.shape[2] == 4:
        bgr = img_bgr[:, :, :3]
    else:
        bgr = img_bgr

    h, w = bgr.shape[:2]
    img_area = float(h * w)

    gray = cv2.cvtColor(bgr, cv2.COLOR_BGR2GRAY)
    gray = cv2.GaussianBlur(gray, (3, 3), 0)

    thr = cv2.adaptiveThreshold(
        gray,
        255,
        cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
        cv2.THRESH_BINARY_INV,
        31,
        7,
    )

    if dilate_px > 0:
        k = cv2.getStructuringElement(cv2.MORPH_RECT, (dilate_px, dilate_px))
        thr = cv2.dilate(thr, k, iterations=1)

    if close_px > 0:
        k = cv2.getStructuringElement(cv2.MORPH_RECT, (close_px, close_px))
        thr = cv2.morphologyEx(thr, cv2.MORPH_CLOSE, k, iterations=1)

    regions = cv2.bitwise_not(thr)

    flood = regions.copy()
    mask = np.zeros((h + 2, w + 2), np.uint8)
    cv2.floodFill(flood, mask, (0, 0), 0)
    enclosed = flood

    contours, _hier = cv2.findContours(enclosed, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    min_area = img_area * float(min_area_ratio)
    max_area = img_area * float(max_area_ratio)

    parcels: list[dict] = []

    for c in contours:
        area = float(cv2.contourArea(c))
        if area < min_area or area > max_area:
            continue

        peri = float(cv2.arcLength(c, True))
        eps = max(1.0, peri * float(approx_eps_ratio))
        approx = cv2.approxPolyDP(c, eps, True)
        if approx is None or len(approx) < 3:
            continue

        feature = _to_feature(len(parcels) + 1, approx)
        if feature:
            parcels.append(feature)

    fc = {
        "type": "FeatureCollection",
        "name": "parcels_pixels",
        "properties": {
            "image": str(image_path).replace('\\\\', '/'),
            "crs": "pixel",
            "width": w,
            "height": h,
        },
        "features": parcels,
    }

    _ensure_dir(out_geojson_path)
    with out_geojson_path.open("w", encoding="utf-8") as f:
        json.dump(fc, f, ensure_ascii=False, indent=2)

    overlay = bgr.copy()
    rng = np.random.default_rng(42)
    for i, feat in enumerate(parcels, start=1):
        ring = np.array(feat["geometry"]["coordinates"][0], dtype=np.int32)
        color = tuple(int(x) for x in rng.integers(40, 255, size=3))
        cv2.polylines(overlay, [ring], True, color, 2)
        m = cv2.moments(ring)
        if m.get("m00"):
            cx = int(m["m10"] / m["m00"])
            cy = int(m["m01"] / m["m00"])
            cv2.putText(
                overlay,
                str(i),
                (cx, cy),
                cv2.FONT_HERSHEY_SIMPLEX,
                0.5,
                (0, 0, 255),
                1,
                cv2.LINE_AA,
            )

    _ensure_dir(out_debug_path)
    cv2.imwrite(str(out_debug_path), overlay)

    return len(parcels), fc


def main() -> int:
    parser = argparse.ArgumentParser(description="Extract parcel polygons from plan overlay image into pixel GeoJSON")
    parser.add_argument(
        "--image",
        default=str(Path("public") / "assets" / "images" / "1.png"),
        help="Input plan image path",
    )
    parser.add_argument(
        "--out-geojson",
        default=str(Path("public") / "geojson" / "parcels_pixels.geojson"),
        help="Output GeoJSON path (pixel coordinates)",
    )
    parser.add_argument(
        "--out-debug",
        default=str(Path("public") / "geojson" / "parcels_debug.png"),
        help="Output debug preview image path",
    )
    parser.add_argument("--min-area-ratio", type=float, default=0.00005)
    parser.add_argument("--max-area-ratio", type=float, default=0.20)
    parser.add_argument("--dilate-px", type=int, default=1)
    parser.add_argument("--close-px", type=int, default=3)
    parser.add_argument("--approx-eps-ratio", type=float, default=0.002)

    args = parser.parse_args()

    count, _ = extract_parcels_pixels(
        image_path=Path(args.image),
        out_geojson_path=Path(args.out_geojson),
        out_debug_path=Path(args.out_debug),
        min_area_ratio=args.min_area_ratio,
        max_area_ratio=args.max_area_ratio,
        dilate_px=args.dilate_px,
        close_px=args.close_px,
        approx_eps_ratio=args.approx_eps_ratio,
    )

    print(f"Extracted parcels: {count}")
    print(f"GeoJSON: {os.path.abspath(args.out_geojson)}")
    print(f"Debug image: {os.path.abspath(args.out_debug)}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
