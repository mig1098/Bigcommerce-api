<?php

/**
 * PHP client for connecting to the Bigcommerce V2 REST Bigcommerce.
 *
 * Only use this file if you don't want autoloading or package management.
 * The new api no need set CIPHER
 */
define('STDIN' , fopen("php://stdin","r"));
require_once  'Library/Bigcommerce/Connection.php';
require_once  'Library/Bigcommerce/Error.php';
require_once  'Library/Bigcommerce/ClientError.php';
require_once  'Library/Bigcommerce/ServerError.php';
require_once  'Library/Bigcommerce/NetworkError.php';
