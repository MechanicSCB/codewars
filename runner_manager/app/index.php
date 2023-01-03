<?php

$cmd = 'docker -v';
$cmd = 'docker run -dp 8081:80 phpmyadmin 2>&1';
$cmd = 'docker run -dp 5099:80 -e APACHE_RUN_USER=#1000  kolekulyanov/apache-runner 2>&1';
//$cmd = 'docker ps -a';
//$cmd = 'whoami';

$msg = shell_exec($cmd);

echo $msg;
