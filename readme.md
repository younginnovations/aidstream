# [![AidStream](http://v201.aidstream.org/images/logo.png)](http://v201.aidstream.org)

### AidStream stands as one such platform, that presents complexity of the IATI in an understandable and a consumable way.

User friendly interfaces within the platform enhance an effortless entry, 
update and publish of the aid data in the IATI format for organisations that want to publish IATI data. 
The system hides all the complexities and technicalities of the xml. With AidStream, 
the necessity to understand the details of the IATI standard becomes lesser. 
All that needs to be done is to register into the system, enter the data and publish it. 
The data will be easily published to the IATI registry . In addition, AidStream guarantees high security, 
proper maintenance and easy accessability of the aid data.


## Code Quality and Test Coverage

We use [CodeClimate](https://codeclimate.com/repos/55f540606956805fc2010677/feed) to check out code quality and report the test coverage from the build system to it.

[![Code Climate](https://codeclimate.com/repos/55f540606956805fc2010677/badges/dab7b46f5a489b6104ed/gpa.svg)](https://codeclimate.com/repos/55f540606956805fc2010677/feed)
[![Test Coverage](https://codeclimate.com/repos/55f540606956805fc2010677/badges/dab7b46f5a489b6104ed/coverage.svg)](https://codeclimate.com/repos/55f540606956805fc2010677/coverage)


## Install

AidStream can be cloned after having access to the gitlab repository and installed following the procedure given below:

* git clone git@gitlab.yipl.com.np:web-apps/aidstream-new.git
* cd aidstream-new

## Run

The app can be run with the command below:

* install the application dependencies using command: ` composer install `
* copy .env.example to .env and update your the database configurations
* give read/write permission to the storage folder using `chmod -R 777 storage`
* Create a directory uploads inside public folder and, files and temp directory inside the uploads directory.
* Create Organization and Activity folder inside files
* Give read/write permission to uploads directory using: `chmod 777 -R uploads/`.
* run migration using ` php artisan migrate `
* seed dummy data using ` php artisan db:seed `
* serve application using `php artisan serve` (append --port PORT_NUMBER to run in different port other than 8000)
* access `localhost:8000`

## Framework

The application is written in PHP based on the [Laravel](http://laravel.com) framework, current version of Laravel 
used for this project is 5.
 

## Tools and packages

This application uses many tools and packages, the packages can 
be seen in the [composer.json](http://gitlab.yipl.com.np/web-apps/aidstream-new/blob/master/composer.json) file

Some major PHP packages used are listed below:

* [kris/laravel-form-builder](https://github.com/kristijanhusak/laravel-form-builder) - for generating html forms

## Structure

The application is structured in app/Core/{version} as V201,V202 folder.

Each Version folder contains five folders

* Codelist - Contains standard values declaration. Detailed documentation is [here](http://iatistandard.org/201/codelists/)
* Element - Contains classes for retrieval of elements needed
* Forms - Contains all the forms that generates html form using [kris/laravel-form-builder](https://github.com/kristijanhusak/laravel-form-builder)
* Repositories - Contains all the classes for storage and retrieval from database.
* Requests - Contains form validation rules and messages

Classes inside each of the above directories are properly written within corresponding modules namespace. 

## Check code quality

We follow [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) for 
coding standard. To check if your code 
matches the projects quality checks please see [codeclimate](https://codeclimate.com/repos/55f540606956805fc2010677/feed).