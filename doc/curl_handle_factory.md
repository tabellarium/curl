cURL Handle Wrapper Factory
===========================

- Interface: **Tabellarium\Curl\CurlHandleFactoryInterface**
- Implementation: **Tabellarium\Curl\CurlFactoryHandle**

Factory creates new cURL handle wrapper with a wrapped cURL handle

Factory usage
-------------
Factory method can be called using one of the following arguments:
- *NULL*  (or no argument) - Wrapper will create a new cURL resource.
```php
use Tabellarium\Curl\CurlHandleFactory;

$f = new CurlHandleFactory();
$c = $f->createCurlHandle();
```

- *string* - Will create a new cURL resource and set default URL to given string value.
```php
use Tabellarium\Curl\CurlHandleFactory;

$f = new CurlHandleFactory();
$c = $f->createCurlHandle('http://localhost.com');
```

- *cURL resource* - cURL resource to wrap
```php
use Tabellarium\Curl\CurlHandleFactory;

$f = new CurlHandleFactory();
$r = curl_init();
$c = f->createCurlHandle($r);
```

Passing any other value will result in a *Tabellarium\Curl\Throwable\Exception\InvalidArgumentException*


Exceptions
----------
Operation failures will throw a runtime exception **Tabellarium\Curl\Throwable\Exception\RuntimeException**. There are
two sub-types of this exception:

- *\Tabellarium\Curl\Throwable\Exception\NoOpenHandleException* - Thrown when underlying resource handle has been closed
or detached from the wrapper
- *\Tabellarium\Curl\Throwable\Exception\CurlException* - When a cURL operation error occurred, with message being a
cURL error, and code being a cURL error code

