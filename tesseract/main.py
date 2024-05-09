import cv2
import pytesseract
import argparse

parser = argparse.ArgumentParser()
parser.add_argument('--image-url')
parser.add_argument('--lang')
args = parser.parse_args()

if args.image_url is None:
    exit('--image-url parameter cannot be empty')

img = cv2.imread(args.image_url)

pytesseract.pytesseract.tesseract_cmd = r'C:\\Program Files\\Tesseract-OCR\\tesseract.exe'
custom_config = r'--oem 3 --psm 6'
#
# osd = pytesseract.image_to_osd(img, output_type='dict', config=custom_config)
# print(osd)

d = pytesseract.image_to_string(img, config=custom_config)
print(d)


