<?php

$cmd = 'docker -v';
//$cmd = 'docker run -dp 8081:80 phpmyadmin 2>&1';
//$cmd = 'docker run -dp 5099:80 -e APACHE_RUN_USER=#1000  kolekulyanov/apache-runner 2>&1';
$cmd = 'docker run -dp 81:80 kolekulyanov/sail-runner:2.1 2>&1';
//$cmd = 'docker stop ee889904eec29e418a49d299f42c57e6130768b5d4e35b4b445f4e24c4b33d1d';
//$cmd = 'docker rm ee889904eec29e418a49d299f42c57e6130768b5d4e35b4b445f4e24c4b33d1d';
//$cmd = 'docker ps -a';
//$cmd = 'whoami';

$msg = shell_exec($cmd);

echo $msg;
