# Composer Package Template

If you are trying to create a new PHP Composer package, whether it is going to be submitted to [packagist.org](packagist.org) 
or just to exist in your Github account, this template package of files will surely help you make the process a lot easier 
and faster.

## Requirements

* PHP >= 7.0;
* composer.

## Features

* PSR-4 autoloading compliant structure;
* Unit-Testing with PHPUnit 6;
* Comprehensive guide and tutorial;
* Easy to use with any framework or even a plain php file;
* Useful tools for better code included.

Installation
============

    composer create-project ginopane/composer-package-template yourproject
    
This will create a basic project structure for you:

* **src** is where your codes will live in, each class will need to reside in its own file inside this folder.
* **tests** each class that you write in src folder needs to be tested before it was even "included" into somewhere else. So basically we have tests classes there to test other classes.
* **.gitignore** there are certain files that we don't want to publish in Git, so we just add them to this fle for them to "get ignored by git"
* **LICENSE** terms of how much freedom other programmers is allowed to use this library.
* **README.md** it is a mini documentation of the library, this is usually the "home page" of your repo if you published it in GitHub and Packagist.
* **composer.json** is where the information about your library is stored, like package name, author and dependencies.
* **phpunit.xml** It is a configuration file of PHPUnit, so that tests classes will be able to test the classes you've written.

Please refer to original [article](http://www.darwinbiler.com/creating-composer-package-library/) for more information.

## Useful Tools

### Code sniffer tool:

 ```php vendor/squizlabs/php_codesniffer/scripts/phpcs -s --report-full=phpcs.txt --standard=PSR2 src/```

### Code auto-fixer:

 ```php vendor/squizlabs/php_codesniffer/scripts/phpcbf --standard=PSR2 src/```    
 
### PhpUnit:

 ```php vendor/phpunit/phpunit/phpunit -c build/phpunit.xml```

I encourage that you put more information on this readme file instead of leaving it as is. See [How to make a README file](http://www.darwinbiler.com/designing-and-making-the-readme-file-for-your-github-repository/) for more info.
