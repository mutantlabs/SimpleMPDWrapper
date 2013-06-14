SimpleMPDWrapper
================

Simple PHP Wrapper for MDP is a PHP class for interfacing with the MPD Music Daemon. It allows you to develop an API to interface with MPD

#SimpleMPDWrapper Class useage

Construct a new PocketMp instance
Required parameters: Password, MPD Server address, Port, Refresh interval
```php
$mp = new PocketMP("","192.168.0.1",6600,0);
```

Send a command using the send method:
```php
echo json_encode($mp->send("add", "spotify:track:48mZ0CGCffjH49h5lAPTIe"));
```

Or utilise the quick method wrappers
```php
echo json_encode($mp->add("spotify:track:48mZ0CGCffjH49h5lAPTIe"));
```
# Simple API example

Requires https://github.com/marcj/php-rest-service

Install php-rest-service with Composer:

 - https://packagist.org/packages/marcj/php-rest-service.
 - More information available under https://packagist.org/.

Create a `composer.json`:

```json
{
    "require": {
        "marcj/php-rest-service": "*"
    }
}
```

and run

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
```

After the installation, you need to include the `vendor/autoload.php` to make the class in your script available.
```php
include 'vendor/autoload.php';
```

Requirements
------------

 - PHP 5.3 and above.
 - PHPUnit to execute the test suite.
 - Setup PATH_INFO in mod_rewrite (.htaccess) or other webserver configuration

Example:
```
//apache .htaccess
RewriteEngine On
RewriteRule (.+) index.php/$1 [L]
```

SimpleMPDWrapper API Method using php-rest-service Demo
----------

```php
    include 'vendor/autoload.php';
    include 'helpers/BaseUrl.php';
    include 'SimpleMPDWrapper.php';

    use RestService\Server;

    Server::create('/')
    ->addGetRoute('add/(.*)', function($data){
                $mp = new SimpleMPDWrapper("","192.168.1.120",6600,0);
                $response = array(
                    'message' => 'track sent to mutant playlist',
                    'track' => $data,
                    'response' => $mp->add($data)
                );
                return $response;
            })
    ->run();
```