import cv2
import os

src_path = r"C:\Users\muzam\.gemini\antigravity-ide\brain\37f69f48-bccd-4afd-a18d-4620b46eaa7c\media__1781797740588.jpg"
img = cv2.imread(src_path)
h, w, _ = img.shape

out_dir = r"d:\xampp\htdocs\project_6sks_v5.1\public\images"

# Crop 100px slices
for i in range(0, h, 100):
    y_end = min(i + 100, h)
    slice_img = img[i:y_end, 0:w]
    cv2.imwrite(os.path.join(out_dir, f"slice_{i}.jpg"), slice_img)

print("Slices saved successfully.")
