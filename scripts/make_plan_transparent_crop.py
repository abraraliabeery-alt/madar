import argparse
from pathlib import Path

import cv2
import numpy as np


def _ensure_dir(path: Path) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)


def _pick_largest_contour(contours: list[np.ndarray]) -> np.ndarray | None:
    best = None
    best_area = 0.0
    for c in contours:
        a = float(cv2.contourArea(c))
        if a > best_area:
            best_area = a
            best = c
    return best


def main() -> int:
    parser = argparse.ArgumentParser(description="Make plan image transparent outside outer boundary and crop tight")
    parser.add_argument("--image", default=str(Path("public") / "assets" / "images" / "1.png"))
    parser.add_argument("--out", default=str(Path("public") / "assets" / "images" / "1_cutout.png"))
    parser.add_argument("--out-debug", default=str(Path("public") / "assets" / "images" / "1_cutout_debug.png"))
    parser.add_argument("--pad", type=int, default=8)

    args = parser.parse_args()

    image_path = Path(args.image)
    out_path = Path(args.out)
    out_debug = Path(args.out_debug)

    img = cv2.imread(str(image_path), cv2.IMREAD_UNCHANGED)
    if img is None:
        raise FileNotFoundError(f"Cannot read image: {image_path}")

    if img.ndim == 2:
        bgr = cv2.cvtColor(img, cv2.COLOR_GRAY2BGR)
    elif img.shape[2] == 4:
        bgr = img[:, :, :3]
    else:
        bgr = img

    h, w = bgr.shape[:2]

    hsv = cv2.cvtColor(bgr, cv2.COLOR_BGR2HSV)

    lower_g = np.array([35, 30, 30], dtype=np.uint8)
    upper_g = np.array([90, 255, 255], dtype=np.uint8)
    green_mask = cv2.inRange(hsv, lower_g, upper_g)

    k = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (7, 7))
    green_mask = cv2.morphologyEx(green_mask, cv2.MORPH_CLOSE, k, iterations=3)

    contours, _ = cv2.findContours(green_mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    outer = _pick_largest_contour(contours)
    if outer is None or len(outer) < 3:
        raise RuntimeError("Could not detect outer green boundary contour")

    poly = cv2.approxPolyDP(outer, 2.0, True)

    inside_mask = np.zeros((h, w), dtype=np.uint8)
    cv2.fillPoly(inside_mask, [poly], 255)

    b, g, r = cv2.split(bgr)
    alpha = inside_mask

    rgba = cv2.merge([b, g, r, alpha])

    x, y, ww, hh = cv2.boundingRect(poly)
    pad = int(max(0, args.pad))
    x0 = max(0, x - pad)
    y0 = max(0, y - pad)
    x1 = min(w, x + ww + pad)
    y1 = min(h, y + hh + pad)

    cropped = rgba[y0:y1, x0:x1]

    _ensure_dir(out_path)
    cv2.imwrite(str(out_path), cropped)

    debug = bgr.copy()
    cv2.polylines(debug, [poly], True, (0, 0, 255), 2)
    _ensure_dir(out_debug)
    cv2.imwrite(str(out_debug), debug)

    print(f"Cutout PNG: {out_path}")
    print(f"Debug PNG: {out_debug}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
