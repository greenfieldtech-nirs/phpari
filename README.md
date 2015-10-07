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

Contibuting Code
----------------
We are very open when it comes to people contributing code to this project. In order to make life easier, here is the preferred method to contribute code:

For bug fixes and security updates in Master branch:

  1. Fork the Master Branch into your own personal Github account 
  2. Update your local fork
  3. Generate a pull request from your own fork over to our Master Branch

For new features and improvement:

  1. Fork the Development Branch into your own personal Github account
  2. Update your local fork
  3. Generate a pull request from your own fork over to our development branch

We will do our best to go over the various contributions as fast as possible. Bug fixes and security updates will be handled faster - feature improvements will be added to the next major release.

Our IDE of choice is phpStorm from JetBrains (http://www.jetbrains.com/phpstorm/) - we use the default styling, so no need to change that. If you use a different IDE, please make sure you update your IDE to support the internal styling of the project, so that you don't break the general code styling. 

Make sure to document your code - once it's merged in, we will need to keep working on your code, so please make sure your documentation will be clear and concise, so we can continue your work (as required). 

Our objective is to involve the community as much as possible, so feel free to jump in and assist. Contibutions to the project will automatically put your name into the README.md file, so that everybody will see your coolness and greatness supporting the Open Source movement and the continuation of this project.

Release Policy
--------------
Releasing code into the Open Source is always a challenge, it can be both confusing and dawnting at the same time. In order to make life simple with version numbers, here is our projected release policy (it may change in the future).

Every version will be marked with a Major.Minor.Patch version numbering scheme. 

A major release will be released once the code of the library is stable and battle tested. How long does that take? good question, we don't know. Currently, our major release version is 0 - we are still in active development.

A minor release will be released once the code of the library is stable and had been introduced with a significant number of fixes and modifications, and been regressed by several members of the community. 

A patch release will be released once the code of the library is stable and had been introduced with minor modifications. These modifications will normally include bug fixes and security updates.

Feature enhancements will only be merged into minor releases, not into patch releases.

Team Members and Contributors
------------------------------
The following list includes names and aliases for people who had something to do with the creation/maintenance of this library. It takes alot of resources to maintain an Open Source project, thus, we will always do our best to make sure contributions and tested and merged as fast as possible.

    Nir Simionovich, https://github.com/greenfieldtech-nirs
    Leonid Notik, https://github.com/lnotik
    Scott Griepentrog, https://github.com/stgnet
    Matak, https://github.com/matak
