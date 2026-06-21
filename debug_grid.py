import cv2
import numpy as np

src_path = r"C:\Users\muzam\.gemini\antigravity-ide\brain\37f69f48-bccd-4afd-a18d-4620b46eaa7c\media__1781797740588.jpg"
img = cv2.imread(src_path)

# Let's calculate the horizontal gradient or row-by-row differences to find the border.
# Or let's just save a version with horizontal lines drawn every 50 pixels so we can inspect it.
img_grid = img.copy()
for y in range(0, img.shape[0], 50):
    cv2.line(img_grid, (0, y), (img.shape[1], y), (0, 0, 255), 2)
    cv2.putText(img_grid, str(y), (10, y - 5), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255), 1)

cv2.imwrite(r"d:\xampp\htdocs\project_6sks_v5.1\public\images\debug_grid.jpg", img_grid)
print("Debug grid image saved.")
