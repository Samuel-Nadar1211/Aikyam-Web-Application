import os
import shutil
import fitz  # PyMuPDF
from PIL import Image

def convert_pdf_to_images(pdf_path, output_dir, zoom_x=4.0, zoom_y=4.0):
    # Open the PDF file
    pdf_document = fitz.open(pdf_path)

    # Create the output directory if it doesn't exist
    os.makedirs(output_dir, exist_ok=True)

    # Iterate through the pages and save each as an image
    for page_num in range(pdf_document.page_count):
        page = pdf_document.load_page(page_num)  # Get page
        
        # Increase the resolution by adjusting zoom
        matrix = fitz.Matrix(zoom_x, zoom_y)
        pix = page.get_pixmap(matrix=matrix)  # Convert page to high-res image
        
        image_filename = f"{page_num + 1}.png"
        image_path = os.path.join(output_dir, image_filename)
        pix.save(image_path)  # Save image as PNG

        # Convert PNG to JPEG with high quality using Pillow
        img = Image.open(image_path)
        img = img.convert("RGB")  # Convert to RGB mode if needed
        img.save(image_path.replace('.png', '.jpg'), 'JPEG', quality=100)  # Save as JPEG with high quality

        # Remove the original PNG file
        os.remove(image_path)

    pdf_document.close()

def rename_images(directory):
    # Get list of files in directory
    files = os.listdir(directory)

    # Sort files numerically
    files.sort(key=lambda x: int(x.split('.')[0]))

    # Iterate over each file
    for i, filename in enumerate(files, start=1):
        # Rename original file
        new_filename = f"{i}.{filename.split('.')[1]}"
        os.rename(os.path.join(directory, filename), os.path.join(directory, new_filename))

        # Copy original file as -large
        large_filename = f"{i}-large.{filename.split('.')[1]}"
        shutil.copyfile(os.path.join(directory, new_filename), os.path.join(directory, large_filename))

        # Copy original file as -thumb
        thumb_filename = f"{i}-thumb.{filename.split('.')[1]}"
        shutil.copyfile(os.path.join(directory, new_filename), os.path.join(directory, thumb_filename))

        print(f"Renamed {filename} to {new_filename}, {large_filename}, {thumb_filename}")


# Example usage
issue = input("Enter issue number: ")

output_dir = f"Image/Aikyam - {issue}/"
pdf_path = f"../PDF/Aikyam - {issue}.pdf"

# Convert PDF pages to high-res images
convert_pdf_to_images(pdf_path, output_dir)

# Rename images and create -large and -thumb copies
rename_images(output_dir)
