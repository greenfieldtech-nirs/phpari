phpari
======

A Class Library enabling Asterisk ARI functionality for PHP

Dependencies
------------
These are the minimum requirements to have phpari installed on your server:

** PHP >= 5.3.9
** Composer
** PHP OpenSSL Module to connect using SSL (wss:// uris)

Additional dependencies are installed via Composer, these include:

** Reactphp   (http://reactphp.org/)
** ZF2 Logger (http://framework.zend.com/manual/2.0/en/modules/zend.log.overview.html)

Installation
------------
The recommended method of installation is using Composer. Add the following to your composer.json file:

```
{
    "require": {
        "php": ">=5.3.9",
        "educoder/pest": "1.0.0",
        "devristo/phpws": "dev-master",
        "greenfieldtech-nirs/phpari": "dev-master"
    }
}
```

We recommend using the "dev-master" version at this point in time, as we are still under heavy development and testing.

Verify functionality
--------------------
The simplest way to verify that phpari is installed correctly is by using it. Here is a minimal script to ensure you every installed correctly:

```php
require_once("vendor/autoload.php");

echo "Starting ARI Connection\n";
$ariConnector = new phpari(ARI_USERNAME, ARI_PASSWORD, "hello-world", ARI_SERVER, ARI_PORT, ARI_ENDPOINT);
echo "Active Channels: " . json_encode($ariConnector->channels()->channel_list()) . "\n";
echo "Ending ARI Connection\n";

```

The output should resemble the followiong:

```
[root@ari agi-bin]# php test.php
Starting ARI Connection
Active Channels: []
Ending ARI Connection
```

Reporiting Issues
--------------------
Please report issues directly via the Github project page.
