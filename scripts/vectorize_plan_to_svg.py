import argparse
import math
from pathlib import Path

import cv2
import numpy as np


def _ensure_dir(path: Path) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)


def _contour_to_path(contour: np.ndarray) -> str:
    pts = contour.reshape(-1, 2)
    if len(pts) == 0:
        return ""

    parts = [f"M {float(pts[0][0]):.2f} {float(pts[0][1]):.2f}"]
    for x, y in pts[1:]:
        parts.append(f"L {float(x):.2f} {float(y):.2f}")
    return " ".join(parts)


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
    parser = argparse.ArgumentParser(description="Vectorize Ajlan plan image to SVG (outer green boundary + inner black lines)")
    parser.add_argument("--image", default=str(Path("public") / "assets" / "images" / "1.png"))
    parser.add_argument("--out-svg", default=str(Path("public") / "assets" / "images" / "1_vector.svg"))
    parser.add_argument("--out-debug", default=str(Path("public") / "assets" / "images" / "1_vector_debug.png"))
    parser.add_argument("--inner-min-length", type=float, default=60.0)
    parser.add_argument("--approx-eps", type=float, default=1.5)

    args = parser.parse_args()

    image_path = Path(args.image)
    out_svg = Path(args.out_svg)
    out_debug = Path(args.out_debug)

    img = cv2.imread(str(image_path), cv2.IMREAD_UNCHANGED)
    if img is None:
        raise FileNotFoundError(f"Cannot read image: {image_path}")

    if img.ndim == 3 and img.shape[2] == 4:
        bgr = img[:, :, :3]
    else:
        bgr = img

    h, w = bgr.shape[:2]

    hsv = cv2.cvtColor(bgr, cv2.COLOR_BGR2HSV)

    # Green boundary mask (tuned for typical green stroke)
    lower_g = np.array([35, 30, 30], dtype=np.uint8)
    upper_g = np.array([90, 255, 255], dtype=np.uint8)
    green_mask = cv2.inRange(hsv, lower_g, upper_g)

    k = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (5, 5))
    green_mask = cv2.morphologyEx(green_mask, cv2.MORPH_CLOSE, k, iterations=2)

    green_contours, _ = cv2.findContours(green_mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    outer = _pick_largest_contour(green_contours)

    # Black-ish internal line mask
    gray = cv2.cvtColor(bgr, cv2.COLOR_BGR2GRAY)
    gray = cv2.GaussianBlur(gray, (3, 3), 0)

    # Threshold for dark pixels
    _, black = cv2.threshold(gray, 180, 255, cv2.THRESH_BINARY_INV)

    # Remove noise, connect broken strokes a bit
    kb = cv2.getStructuringElement(cv2.MORPH_RECT, (3, 3))
    black = cv2.morphologyEx(black, cv2.MORPH_OPEN, kb, iterations=1)
    black = cv2.morphologyEx(black, cv2.MORPH_CLOSE, kb, iterations=1)

    inner_contours, _ = cv2.findContours(black, cv2.RETR_LIST, cv2.CHAIN_APPROX_NONE)

    inner_paths: list[str] = []
    inner_kept = 0

    for c in inner_contours:
        if c is None or len(c) < 10:
            continue
        length = float(cv2.arcLength(c, False))
        if length < float(args.inner_min_length):
            continue

        approx = cv2.approxPolyDP(c, float(args.approx_eps), False)
        if approx is None or len(approx) < 2:
            continue

        d = _contour_to_path(approx)
        if d:
            inner_paths.append(d)
            inner_kept += 1

    outer_path = ""
    if outer is not None and len(outer) >= 3:
        outer_approx = cv2.approxPolyDP(outer, 2.0, True)
        outer_path = _contour_to_path(outer_approx) + " Z"

    _ensure_dir(out_svg)

    svg_parts: list[str] = []
    svg_parts.append(f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w} {h}" width="{w}" height="{h}">')
    svg_parts.append('<g fill="none" stroke-linecap="round" stroke-linejoin="round">')

    if outer_path:
        svg_parts.append(f'<path d="{outer_path}" stroke="#00A651" stroke-width="3"/>')

    for d in inner_paths:
        svg_parts.append(f'<path d="{d}" stroke="#000000" stroke-width="1"/>')

    svg_parts.append("</g>")
    svg_parts.append("</svg>")

    out_svg.write_text("\n".join(svg_parts), encoding="utf-8")

    # Debug preview
    debug = bgr.copy()
    if outer is not None:
        cv2.drawContours(debug, [outer], -1, (0, 200, 0), 2)
    cv2.drawContours(debug, inner_contours, -1, (0, 0, 255), 1)
    _ensure_dir(out_debug)
    cv2.imwrite(str(out_debug), debug)

    print(f"SVG: {out_svg}")
    print(f"Debug: {out_debug}")
    print(f"Inner paths kept: {inner_kept}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
