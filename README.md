# CakePHP DigitalOcean

[![Build Status](https://travis-ci.org/LubosRemplik/CakePHP-DigitalOcean.svg)](https://travis-ci.org/LubosRemplik/CakePHP-DigitalOcean)
[![Latest Stable Version](https://poser.pugx.org/lubos/digital-ocean/v/stable.svg)](https://packagist.org/packages/lubos/digital-ocean) 
[![Total Downloads](https://poser.pugx.org/lubos/digital-ocean/downloads.svg)](https://packagist.org/packages/lubos/digital-ocean) 
[![Latest Unstable Version](https://poser.pugx.org/lubos/digital-ocean/v/unstable.svg)](https://packagist.org/packages/lubos/digital-ocean) 
[![License](https://poser.pugx.org/lubos/digital-ocean/license.svg)](https://packagist.org/packages/lubos/digital-ocean)

CakePHP 3.x plugin for creating interacting with DigitalOcean api v2

## Installation & Configuration

```
composer require lubos/digital-ocean
```

Load plugin in bootstrap.php file

```php
Plugin::load('Lubos/DigitalOcean');
```

Get [Access Token](https://cloud.digitalocean.com/settings/api/tokens) and put them into config
```php
'DigitalOcean' => [
    'token' => 'your-access-token',
]
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
