<?php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('456469069186-v4o6e24nqghl0juq1f0t3clelfk4a4tr.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('xRpPU9M5nibTZNCbBZ8JWfIc');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/account_class/index.php');

//
$google_client->addScope('email');

$google_client->addScope('profile');

//start session on web page
session_start();

?>