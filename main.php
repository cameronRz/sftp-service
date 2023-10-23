<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use Cameron\SftpService\SftpService;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$sftp = new SftpService(
    $_ENV['SFTP_HOST'],
    $_ENV['SFTP_PORT'],
    $_ENV['SFTP_USERNAME'],
    $_ENV['SFTP_PASSWORD'],
    $_ENV['SFTP_PATH'],
);

//$sftp->write('my-file.txt', 'This is a test.');

//$contents = $sftp->read('my-file.txt');
//echo $contents;

//$filenames = $sftp->getFilenames();
//var_dump($filenames);