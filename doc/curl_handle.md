cURL Handle Wrapper
===================

- Interface: **Tabellarium\Curl\CurlHandleInterface**
- Implementation: **Tabellarium\Curl\CurlHandle**

Wrapper implements standard operations you would perform with a cURL handle resource. Exception are error message
operations as those are handled in form of Exception throwing.

Constructor
-----------
Wrapper constructor can be called using one of the following arguments:
- *NULL*  (or no argument) - Wrapper will create a new cURL resource.
```php
use Tabellarium\Curl\CurlHandle;
$c = new CurlHandle();
```

- *string* - Will create a new cURL resource and set default URL to given string value.
```php
use Tabellarium\Curl\CurlHandle;
$c = new CurlHandle('http://localhost');
```

- *cURL resource* - cURL resource to wrap
```php
use Tabellarium\Curl\CurlHandle;
$r = curl_init();
$c = new CurlHandle($r);
```

Passing any other value will result in a *Tabellarium\Curl\Throwable\Exception\InvalidArgumentException*