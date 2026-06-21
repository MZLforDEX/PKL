import os
from PIL import Image

src_path = r"C:\Users\muzam\.gemini\antigravity-ide\brain\37f69f48-bccd-4afd-a18d-4620b46eaa7c\media__1781797740588.jpg"
out_dir = r"d:\xampp\htdocs\project_6sks_v5.1\public\images"

img = Image.open(src_path)
width, height = img.size

# School building: 0px to 400px (top photo)
# We can crop from Y=0 to Y=400 or Y=50 to Y=400 to focus on the building.
# Let's crop from Y=50 to Y=400 (350px height) or Y=0 to Y=400 (400px height). Let's use Y=0 to Y=400.
sekolah_img = img.crop((0, 0, width, 400))

# Students & Teacher: faces are at 500-600px, bodies go down to 800px.
# To focus on their faces and middle/upper bodies (tengah atau muka) as requested:
# Let's crop from Y=430 to Y=780. Height will be 350px.
# This starts just above the heads (around 430) and goes down to their waists/hips (780).
siswa_img = img.crop((0, 430, width, 780))

# Save the updated crops
sekolah_img.save(os.path.join(out_dir, "slider-sekolah.jpg"), "JPEG", quality=95)
siswa_img.save(os.path.join(out_dir, "slider-siswa.jpg"), "JPEG", quality=95)

print(f"New crops saved: sekolah={sekolah_img.size}, siswa={siswa_img.size}")
