<?php

header('content-type:application/json');

include 'config/db.php';
include 'controllers/UserController.php';
include 'controllers/AddressController.php';
include 'models/User.php';
include 'models/addresses.php';

$db = (new Database())->connect();

$controller = new UserController($db);
$controller->handleRequest($_SERVER['REQUEST_METHOD']);
