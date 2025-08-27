# BGR-ILS
Integrated Logistic Solution

----
## Struktur sistem
- SAAS dengan multiple akses perusahaan
- Superadmin bisa melihat semua data dari multiple perusahaan
- Admin dan role dibawahnya hanya bisa melihat data perusahaan masing-masing
- Data yang mempunyai relasi dengan perusahaan: User, Project, Warehouse, Storage, Item, Data transaksional

----
## Akses user (password sama: password)
- Superadmin
- Admin
- CargoOwner
- WarehouseSupervisor
- WarehouseOfficer

----
## First setup
- Set .env
- php composer.phar update
- php composer.phar dump-autoload
- php artisan migrate:refresh --seed

----
## Mailtrap.io SMTP
- Host:	smtp.mailtrap.io
- Port:	25 or 465 or 2525
- Username:	8341069ff52509
- Password:	a987beb6f7b310
- Auth:	PLAIN, LOGIN and CRAM-MD5
- TLS:	Optional

----
## reCAPTCHA (Paste into .env)
- NOCAPTCHA_SITEKEY=6Ldj6HcUAAAAAJAfa6QoE5D8h6MVkGvSobloh-Jx
- NOCAPTCHA_SECRET=6Ldj6HcUAAAAAKyD1GKxkkdvobvsZoG2rGEa05C3

----
## Laravel API Passport setup
- composer require laravel/passport
- php artisan migrate
- php artisan passport:install
- php artisan passport:client --password

----
## FOMS API
URL API : https://api-bgr.itgps.co.id/
username: WMS
password: WMSadmin
Content-Type: Application/json