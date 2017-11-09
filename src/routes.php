<?php
use Slim\Http\Request;
use Slim\Http\Response;
use Catatumbo\Core\Loading;
use \Psr\Http\Message\ResponseInterface;

// Routes
$return = new Loading();
$return->auth();

$return->init($app);
