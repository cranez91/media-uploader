# media-uploader
Laravel project where users are allowed to upload base64 encoded images

First of all, you need to clone this project to you local machine. Once you have done that, you need to copy the .env.example file content to a new .env file:

* copy .env.example .env   *For Windows*
* cp .env.example .env   *For Linux*

Make sure you have installed **Composer** and **Node.js**, so you need to run:

* composer install
* php artisan key:generate
* php artisan serve

Finally, you should be able to see the project running at **http://localhost:8000/**
