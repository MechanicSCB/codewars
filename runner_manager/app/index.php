<?php

//$cmd = 'docker run -dp 5099:80 -e APACHE_RUN_USER=#1000  kolekulyanov/apache-runner 2>&1';
//$cmd = 'docker run -dp 81:80 --name laravel_runner kolekulyanov/sail-runner:2.1 2>&1';
$cmd = 'docker restart codewars-laravel-runner-1 codewars-apache-runner-1 2>&1';

$msg = shell_exec($cmd);

echo $msg;
