cURL Version
============
- Implementation: **Tabellarium\Curl\CurlVersion**

Simple access to cURL versions

```php
use Tabellarium\Curl\CurlVersion;

echo sprintf("cURL version: %s", CurlVersion::getCurlVersion());
echo sprintf("cURL version number: %d", CurlVersion::getCurlVersionNumber());
echo sprintf("SSL version: %s", CurlVersion::getSSLVersion());
echo sprintf("SSL version number: %d", CurlVersion::getSSLVersionNumber());
echo sprintf("Libz version: %s", CurlVersion::getLibzVersion());

```