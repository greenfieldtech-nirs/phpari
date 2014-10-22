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

Configuring phpari.ini
----------------------
The phpari.ini file is our primary configuration file. You can define your own, just make sure to initiate your phpari object correctly. 
The files' contents is as following:

```
[general]
debug=0
logfile=console ; console for direct console output, filename for file based logging

[asterisk_ari]
username=testuser
password=testing
host=your_asterisk_server_ip_or_fqdn
port=your_asterisk_http_port
endpoint=/ari
transport=ws ; ws for none encrypted, wss for encrypted (currently, only ws is supported)

[asterisk_manager]
username=amiuser
password=amipassword
host=127.0.0.1
port=5038
```

As you may notice, we already carry an Asterisk Manager configuration in there - this will be used in the future to provide
an Asterisk manager connector object as well.

Verify functionality
--------------------
The simplest way to verify that phpari is installed correctly is by using it. Here is a minimal script to ensure you every installed correctly:

```php
require_once("vendor/autoload.php");

echo "Starting ARI Connection\n";
$ariConnector = new phpari();
echo "Active Channels: " . json_encode($ariConnector->channels()->channel_list()) . "\n";
echo "Ending ARI Connection\n";

```

The output should resemble the following:

```
[root@ari agi-bin]# php test.php
Starting ARI Connection
Active Channels: []
Ending ARI Connection
```

Error Handling within PHPARI
--------------------
In order to allow for better error handling, we've decided to hold two variables, within the initiated phpari object. These are "lasterror" and "lasttrace". 
When an error occures, in any of the phpari module requests, be it a PEST error or another, an exception is thrown internally. In order not to break your applications,
we will return a FALSE value, while populating the "lasterror" and "lasttrace" variables.

For example:

```php
    try {
        $conn = new phpari("hello-world"); //create new object
        $app  = new applications($conn);

        $result=$app->applications_list();

        if ((!$result) && (count($result)))
            throw new Exception("phpari error occured", 503);

        echo json_encode($result);
        exit(0);

    } catch (Exception $e) {
        echo "Error: " . $conn->lasterror. "\n";
        echo "Trace: " . $conn->lasttrace. "\n";
    }
```

In the above case, we try to issue an "applications" GET request over to our Asterisk server. In case of an error, the 
applications object will return a FALSE value, while populating the "lasterror" and "lasttrace" variables. Here is a sample
output, for a case where the "port" configuration is wrong, in phpari.ini:

```
$ php ApplicationList.php
Error: Failed connect to 178.62.XXX.XXX:8080; No error
Trace: #0 C:\Users\nirsi_000\Documents\phpari\vendor\educoder\pest\Pest.php(128): Pest->doRequest(Resource id #60)
#1 C:\Users\nirsi_000\Documents\phpari\src\interfaces\applications.php(58): Pest->get('/applications')
#2 C:\Users\nirsi_000\Documents\phpari\examples\ApplicationList.php(33): applications->applications_list()
#3 {main}
```

Basic Stasis application programming
------------------------------------
Stasis is an event driven environment, which isn't really the native environment for PHP. However, thanks to PHP 5.3 and the React library, it is possible to write a "callback" based web socket clinet.
The following example shows how this can be done - the complete example is under examples/BasicStasisApplication.php.

First, we need to setup our basic Stasis connection to Asterisk:

```php
    class BasicStasisApplication
    {

        private $ariEndpoint;
        private $stasisClient;
        private $stasisLoop;
        private $phpariObject;
        private $stasisChannelID;
        private $dtmfSequence = "";

        public $stasisLogger;

        public function __construct($appname = NULL)
        {
            try {
                if (is_null($appname))
                    throw new Exception("[" . __FILE__ . ":" . __LINE__ . "] Stasis application name must be defined!", 500);

                $this->phpariObject = new phpari($appname);

                $this->ariEndpoint  = $this->phpariObject->ariEndpoint;
                $this->stasisClient = $this->phpariObject->stasisClient;
                $this->stasisLoop   = $this->phpariObject->stasisLoop;
                $this->stasisLogger = $this->phpariObject->stasisLogger;
                $this->stasisEvents = $this->phpariObject->stasisEvents;
            } catch (Exception $e) {
                echo $e->getMessage();
                exit(99);
            }
        }
```

Note this, the constructor will normally return no errors for this stage, as we are mearly building the required objects, not connecting to Asterisk yet.
Now, we need to define our Stasis Connection Handler:

```php
        public function StasisAppConnectionHandlers()
        {
            try {
                $this->stasisClient->on("request", function ($headers) {
                    $this->stasisLogger->notice("Request received!");
                });

                $this->stasisClient->on("handshake", function () {
                    $this->stasisLogger->notice("Handshake received!");
                });

                $this->stasisClient->on("message", function ($message) {
                    $event = json_decode($message->getData());
                    $this->stasisLogger->notice('Received event: ' . $event->type);
                    $this->stasisEvents->emit($event->type, array($event));
                });

            } catch (Exception $e) {
                echo $e->getMessage();
                exit(99);
            }
        }
```

Note that we will be ommiting an Event for any additional Asterisk Stasis "message" that is received. 
Now, we need to actually build our connection to Asterisk:

```php
        public function execute()
        {
            try {
                $this->stasisClient->open();
                $this->stasisLoop->run();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit(99);
            }
        }
```

Our main script body would be the following:

```php
    $basicAriClient = new BasicStasisApplication("hello-world");

    $basicAriClient->stasisLogger->info("Starting Stasis Program... Waiting for handshake...");
    $basicAriClient->StasisAppEventHandler();

    $basicAriClient->stasisLogger->info("Connecting... Waiting for handshake...");
    $basicAriClient->execute();
```

That's it - this is your most basic Stasis application. We suggest that you now take a look at examples/BasicStasisApplication.php to see the entire code in action.

Reporting Issues
--------------------
Please report issues directly via the Github project page.
