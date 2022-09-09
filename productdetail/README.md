# Products with QR Code

INTRODUCTION:

This module provides feature to manage and show products with QR code 

It provides a fields Title, Description, Product Image and QR code of product page link.
 
*********

REQUIREMENTS:

Install simplesoftwareio/simple-qrcode package from packagist.org using:

*composer require simplesoftwareio/simple-qrcode "~4"*.

Your PHP must have *Imagick* extension enabled. 

INSTALLATION:

1) Install simplesoftwareio/simple-qrcode package from packagist.org using: *composer require simplesoftwareio/simple-qrcode "~4"*.  
2) Extract module zip in modules/custom folder
3) Enable module "Products with QR Code"
4) Go to Administration > Configuration and click on "QR Code Block Settings" to set Default Block Title, Description and Whether show/hide default QR code in product detail page and please select it "No" if you want to show in the other region of page by Administration > Structure > Block 
5) Go to Administration > Structure > Block layout and place "Product Detail - QR Code" block in content or any other section to render this block at fronted. 
6) Go to Administration > Content > Add Content > QR Products  and Create new products
7) Clear cache and go to [YOUR SITE URL]/product-list to get list of products and get QR code from product detail page.

Video Demo:

https://drive.google.com/file/d/1nkYj3OefTyWmQ2MvtQ0ewmlNM7_-rSDY/view?usp=sharing