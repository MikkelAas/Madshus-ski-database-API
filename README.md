# Database Project 2021 - Group64
This is the database project for Group 64 in the subject IDATG2204.

## Installation

### Software required
The following is a list of software that is required to be installed before you can setup and use this project:
1. Apache2 or another kind of webserver. Note that additional configuration (rewriting url's and such) is required if Apache2 is not used. If using Apache2, make sure that `AllowOverride All` is included in the `httpd.conf`. If you are using XAMPP this should be the default, and you don't need to worry about adding this line.
1. MySQL or MariaDB database
1. PHP 7 or above
1. (Optional) Codeception if you want to run tests

- *NOTE: Points 1, 2 and 3 are covered by installing XAMPP.*  
- *NOTE: If using Apache2 / XAMPP, you will have to configure a `httpd.conf` file that is located different places depending on your Apache2 installation. Refer to the appropriate documentation for where this file is located. On Windows, this file is typically located at `C:/xampp/apache/conf/httpd.conf`* if using XAMPP.

### Setting up project
Follow the steps below to setup project for deployment the project.
Make sure to follow the instructions for software required before starting the process below. 

1. Clone the project
1. Create a new empty database and populate it by importing the `tests/db.sql`. This can be done in phpMyAdmin by `creating empty database` -> `select empty database by clicking on it` -> `Import` -> `Upload .sql file` -> `Go`
1. Copy `config/config_template.php` to `config/config.php`  
1. Modify `config.php` so that the parameters match the configuration of your database. For the `set_include_path("")`, you should provide the absolute path to your cloned project directory (the base folder of this project).
1. In your webserver configuration, set the `public` folder as the *root* folder. If you are using Apache2 / XAMPP, you can do this by changing the `DocumentRoot` in the `httpd.conf` to refer to the *public* folder of wherever you cloned this project. For instance, if you cloned the repository into `C:/.../project`, your `httpd.conf` should have `DocumentRoot "C:/.../project/public"`
1. After restarting Apache2 and MySQL / MariaDB, you should be able to access `http://localhost/rest/diag/` (you might have a different hostname than localhost)

#### Additional configuration to run tests manually
1. Copy `config/config_template.php` to `config/config_test.php`
1. Modify `config_test.php` so that the parameters match the configuration of your *test*-database (normally this is different from the production database)
1. Modify `tests/unit.suite.yml` to match the configuration of your *test*-database 
1. Create an empty database called `db_project` in your *test*-database (it will automatically be populated when running the tests, so you can leave it empty)
1. Run `php vendor/bin/codecept run unit` in your command-line to run unit-tests

#### Something still not working?
1. Where you have to specify host / hostname, try using `localhost`, `127.0.0.1`, or `127.1`.
1. Try to restart / reload apache / mysql

## Don't want to run the tests yourself?
We have employed a continuous integration strategy, meaning that our tests are run every time we push to gitlab. This means that you can view the tests by going to `CI / CD` -> `Pipelines` in our Gitlab repository to view the tests that have been run. 

## Project structure
`.docker` -> Contains files related to Docker  
`config` -> Contains config information for PHP  
`db` -> Contains database-connection related files  
`models` -> Contains functions used for getting / setting information in database  
`public` -> Contains files that should be used when accessing the webserver through the browser. *NOTE: Files outside `public` should not be accessible from any web browser*  
`tests` -> Contains files related to testing

## Wiki
Refer to the wiki for conceptual and logical models, along with other documents used in planning. 