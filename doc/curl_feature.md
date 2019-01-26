cURL Feature
============
- Implementation: **Tabellarium\Curl\CurlFeature**

Not all cURL feature constants are (at the time of writing this code) present in PHP. Given class has constants for
all the cURL features and provides simple way to check feature availability and description.

Checking cURL specific feature
------------------------------

```php
use Tabellarium\Curl\CurlFeature;

echo sprintf('Feature SSL is %s', CurlFeature::isPresent(CurlFeature::SSL) ? 'present' : 'absent');
```


Checking all cURL features
--------------------------

```php
use Tabellarium\Curl\CurlFeature;

foreach (CurlFeature::listFeatures() as $feature);
echo sprintf(
    "Feature %s (%d) is %s (description: %s)\n",
    $feature['feature'],
    $feature['constant'],
    $feature['present'] ? 'present' : 'absent',
    $feature['description']
 );
```