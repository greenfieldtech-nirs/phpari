#!/usr/bin/php -q
<?php

    /*
     *
     * PHP Startup Script for Stasis applications, using PHPARI - For RedHat style distributions (RedHat, CentOS, Fedora, etc)
     * Author: Nir Simionovich, http://www.phpari.org
     * Version 0.1 - 11/10/2014
     *
     * THIS SCRIPT IS FAR FROM BEING A WORK OF ART - IT'S UGLY AND REQUIRES SOME SERIOUS WORK!
     *
     */

    /**
     * You need to change these parameters to run your service script
     */
    $ServicePath    = "/var/lib/asterisk/stasis"; // Location of your Stasis application
    $ServiceHandler = "BasicStasisApplication.php"; // Name of your Stasis application
    $ServiceRunners = 1; // Number of child processes to fork, normally, this should be 1
    $ServiceSleep   = 25; // In case the child dies, sleep for 25ms and spawn a new child
    $ServiceAlias   = "stasis-centos"; // Normally, this will be your Stasis filename, without the .php

    // From this point onwards, don't change anything
    $ServiceCmd = $ServicePath . $ServiceHandler . "  > /dev/null 2> /dev/null";
    $pidFile    = "/var/run/" . $ServiceAlias . ".pid";

    if (isset($argv[1])) {
        switch ($argv[1]) {
            case "start":
                // Clear the pidFile
                exec("rm -f " . $pidFile);
                for ($i = 1; $i < $ServiceRunners + 1; $i++) {
                    $pid = pcntl_fork();
                    if ($pid == -1) {
                        die ("Unable to fork new process");
                    } else if ($pid) {
                        $pid_arr[$i] = $pid;
                        echo "Starting " . $ServiceAlias . " child number " . $i . " : [OK]\n";
                        exit(0);
                    } else {
                        $cpid = pcntl_fork();
                        if ($cpid == -1) {
                            die ("Unable to fork new child");
                        }
                        if (!$cpid) {
                            pcntl_signal(SIGTERM, "sig_handler");
                            pcntl_signal(SIGHUP, "sig_handler");
                            $childPID = posix_getpid();
                            exec("echo " . $childPID . " >> " . $pidFile);
                            while (1) {
                                exec($ServiceCmd);
                                usleep($ServiceSleep . "000");
                            }
                            exit(0);
                        }
                    }
                }
                break;
            case "stop":
                killChildren();
                break;
            case "restart":
                hupChildren();
                break;
            case "default":
                echo "Usage: " . $ServiceAlias . " start|stop|restart\n";
                break;
        }
    } else {
        echo "Usage: " . $ServiceAlias . " start|stop|restart\n";
    }


    function sig_handler($signo)
    {
        switch ($signo) {
            case SIGTERM:
                exit;
                break;
            case SIGHUP:
                foreach ($pid_arr as $kpid) {
                    posix_kill($kpid, SIGTERM);
                    $i--;
                }
                break;
            default:
        }
    }

    function killChildren()
    {
        global $pidFile;
        global $ServiceAlias;

        if (file_exists($pidFile)) {
            $pf = fopen($pidFile, "r");
            while ($pid = fgets($pf)) {
                exec("kill -9 " . $pid);
                echo "Stopping " . $ServiceAlias . " PID " . trim($pid) . ": [OK]\n";
            }
            exec("rm -f " . $pidFile);
            exit(0);
        } else {
            echo "PID file missing, most probably the service is stopped!\n";
            exit (99);
        }
    }

    function hupChildren()
    {
        global $pidFile;
        global $ServiceAlias;

        if (file_exists($pidFile)) {
            $pf = fopen($pidFile, "r");
            while ($pid = fgets($pf)) {
                exec("kill -1 " . $pid);
            }
            echo "Restarting " . $ServiceAlias . ": [OK]\n";
            exit(0);
        } else {
            echo "PID file missing, most probably the service is stopped!\n";
            exit (99);
        }
    }

?>