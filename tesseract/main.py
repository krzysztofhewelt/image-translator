from datetime import datetime
import cv2
import pytesseract
import argparse
import os

# Config
pytesseract.pytesseract.tesseract_cmd = r'C:\\Program Files\\Tesseract-OCR\\tesseract.exe'
custom_config = r'--oem 3 --psm 6'

# Args parser
parser = argparse.ArgumentParser()
parser.add_argument('--image-url')
parser.add_argument('--lang')
args = parser.parse_args()

if args.image_url is None:
    exit('--image-url parameter cannot be empty')

# load image
img = cv2.imread(args.image_url)

# OCR image
d = None
if args.lang:
    d = pytesseract.image_to_string(img, config=custom_config, lang=args.lang)
else:
    d = pytesseract.image_to_string(img, config=custom_config)


# Create file and save output
timestamp = datetime.now().strftime("%Y%m%d%H%M%S%f")
file_name = f"output_{timestamp}.txt"
test_txt_path = os.path.join(os.path.dirname(os.path.realpath(__file__)), '..', 'outputs', file_name)
print(test_txt_path)

f = open(test_txt_path, "w", encoding="utf-8")
f.write(d)
f.close()

