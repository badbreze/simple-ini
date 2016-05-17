Simple Ini Files Utility
===========================

[![Total Downloads](https://img.shields.io/packagist/dt/badbreze/simple-ini.svg?style=flat-square)](https://packagist.org/packages/badbreze/simple-ini)

The SimpleIni Utility is a small INI file tool for read/write values inside INI file easy.

Installation
------------

### Using Composer

```shell
composer require badbreze/simple-ini
```

Example
-------
```
use IniUtil\SimpleIni;

require_once "vendor/autoload.php";

$ini = new SimpleIni(__DIR__.'/helloworld.ini');

$hello = $ini->getVariable('hello');

echo($hello);
//result: world
```

In this example we have a file called "helloworld.ini" with the following content.
```
hello = world
```

@author Damian Gomez


[Project Page](http://www.divenock.com/projects/simple-ini)

