#!/bin/bash
#find /var/www -type d -exec sudo chmod 2775 {} \;
#find /var/www -type f -exec sudo chmod 0664 {} \;
chmod -R 777 /var/www/html/advproducts/api/web/assets/
chmod -R 777 /var/www/html/advproducts/api/runtime/
