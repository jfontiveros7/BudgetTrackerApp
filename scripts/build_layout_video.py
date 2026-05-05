from pathlib import Path
import numpy as np
from PIL import Image, ImageOps, ImageDraw, ImageFont
import imageio.v2 as imageio

ROOT = Path(__file__).resolve().parents[1]
media_dir = ROOT / "docs" / "media" / "layout-video"
out_path = media_dir / "layout-preview.mp4"

slides = [
    ("01-landing.png", "Landing Page"),
    ("02-login.png", "Login"),
    ("03-dashboard.png", "Dashboard"),
    ("04-add-transaction.png", "Add Transaction"),
    ("05-settings.png", "Settings"),
]

size = (1920, 1080)
fps = 30
seconds_per_slide = 3
frames_per_slide = fps * seconds_per_slide

try:
    font = ImageFont.truetype("arial.ttf", 42)
except OSError:
    font = ImageFont.load_default()

with imageio.get_writer(out_path, fps=fps, codec="libx264", quality=8, macro_block_size=None) as writer:
    for file_name, label in slides:
        img_path = media_dir / file_name
        if not img_path.exists():
            continue

        img = Image.open(img_path).convert("RGB")
        framed = ImageOps.pad(img, size, method=Image.Resampling.LANCZOS, color=(9, 12, 11), centering=(0.5, 0.5))

        draw = ImageDraw.Draw(framed, "RGBA")
        overlay_h = 90
        draw.rectangle((0, size[1] - overlay_h, size[0], size[1]), fill=(0, 0, 0, 115))
        draw.text((48, size[1] - 62), label, fill=(242, 245, 243), font=font)

        frame = np.array(framed)
        for _ in range(frames_per_slide):
            writer.append_data(frame)

print(f"Video created: {out_path}")
