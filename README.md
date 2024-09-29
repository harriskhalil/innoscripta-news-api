# innoscripta-news-api

## Install Dependencies

### composer install
### cp .env.example .env
### php artisan key:generate

## Running the Project

### docker-compose up -d

- ### Laravel container : laravel_app
- ### MySQL container : mysql

## Set Mail Configuration

- ### put mail  variables in your .env file i have used Mailtrap for mails.


## Finding the Correct DB Host

### - docker exec -it mysql /bin/sh

### - cat /etc/hosts
### Use the correct DB_HOST value in your .env file.

## API Documentation

### The API documentation will be available at: http://localhost:8001/docs/api#/

## Fetching News Articles

### php artisan app:fetch-news-articles

## Running Tests with Coverage

### ./vendor/bin/pest --coverage

##in case commnad doesnot work follow these steps
### - docker exec -it laravel_app /bin/sh
### - apk add php82-xdebug
### - vi /etc/php82/conf.d/50_xdebug.ini
### - put these in file 
   ### zend_extension=xdebug.so
   ### xdebug.mode=coverage
   
save it and then  run
### killall php-fpm
### php-fpm -F &
### nginx -s reload

### Now retry the coverage command 

## Task Schdeuling

- ### you can run php artisan schedule:work
- ### you can also check the status of task using
- ### php artisan schedule:list
