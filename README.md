# CakePHP DigitalOcean

[![Build Status](https://travis-ci.org/LubosRemplik/CakePHP-Table.svg)](https://travis-ci.org/LubosRemplik/CakePHP-DigitalOcean)
[![Latest Stable Version](https://poser.pugx.org/lubos/table/v/stable.svg)](https://packagist.org/packages/lubos/digital-ocean) 
[![Total Downloads](https://poser.pugx.org/lubos/table/downloads.svg)](https://packagist.org/packages/lubos/digital-ocean) 
[![Latest Unstable Version](https://poser.pugx.org/lubos/table/v/unstable.svg)](https://packagist.org/packages/lubos/digital-ocean) 
[![License](https://poser.pugx.org/lubos/table/license.svg)](https://packagist.org/packages/lubos/digital-ocean)

CakePHP 3.x plugin for creating interacting with DigitalOcean api v1

## Installation

```
composer require lubos/digital-ocean
```

Load plugin in bootstrap.php file

```php
Plugin::load('Lubos/DigitalOcean');
```

## Usage

run `bin/cake` to see shells and its options
Example:  
`bin/cake Lubos/DigitalOcean.droplets all`

## Bugs & Features

For bugs and feature requests, please use the issues section of this repository.

If you want to help, pull requests are welcome.  
Please follow few rules:  

- Fork & clone
- Code bugfix or feature
- Follow [CakePHP coding standards](https://github.com/cakephp/cakephp-codesniffer)
