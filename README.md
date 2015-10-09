# [![AidStream](http://v201.aidstream.org/images/logo.png)](http://v201.aidstream.org)

### AidStream stands as one such platform, that presents complexity of the IATI in an understandable and a consumable way.

User friendly interfaces within the platform enhance an effortless entry, update and publish of the aid data in the IATI format for organisations that want to publish IATI data. The system hides all the complexities and technicalities of the xml. With AidStream, the necessity to understand the details of the IATI standard becomes lesser. All that needs to be done is to register into the system, enter the data and publish it. The data will be easily published to the IATI registry . In addition, AidStream guarantees high security, proper maintenance and easy accessability of the aid data.

[![wercker status](https://app.wercker.com/status/c1afa54ce0c0a4972f17b3b4c4f72e73/m/master "wercker status")](https://app.wercker.com/project/bykey/c1afa54ce0c0a4972f17b3b4c4f72e73)

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
used for this project is 5.1.17.
 

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

[![Code Climate](https://codeclimate.com/repos/55f540606956805fc2010677/badges/dab7b46f5a489b6104ed/gpa.svg)](https://codeclimate.com/repos/55f540606956805fc2010677/feed)

## Tests

There are two types of tests for AidStream, one for unit testing and other one is smoke testing with Hookah library.

### Unit Tests

Unit tests are in the `tests\app` folder. They can be run with the following command:

```
./vendor/bin/phpunit tests/app 
```

### Smoke Tests with Hookah

Smoke tests are in the `tests\Smoke` folder, they can be run with the following commands:

```
./vendor/bin/phpunit tests/Smoke 
```

To run the tests faster in parallel run it with paraunit like below:

```
./vendor/bin/paratest --colors -m 2 -p 4 --stop-on-failure --path= tests/Smoke
```

## Continuous Integration

On each push the hookah test will run on [Wercker](https://app.wercker.com/#applications/560f9c92d77c55dc7303a957) CI. It
also sends notification to hipchat on [Build-Bot](https://yipl.hipchat.com/chat/room/1267700) room.

## Integration with Gitomate

[Gitomate](http://gitlab.yipl.com.np/internal/gitomate) is an application that automates other actions after a git push 
is done with special tags.

To open a merge request you can simply do it with adding `#mr` to the commit message. It will open a merge request for 
you. If it does not open in the first push try doing a commit amend and force push or make changes with a second commit
and do a second push.

Gitomate will also run a code climate analysis for the branch on each push on [code climate](http://gitlab.yipl.com.np/internal/gitomate).
