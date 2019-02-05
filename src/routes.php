<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', 'App\Controllers\PagesController:index');
$app->get('/google-callback', 'App\Controllers\PagesController:google_callback');
$app->get('/profile', 'App\Controllers\PagesController:profile');
$app->get('/logout', 'App\Controllers\PagesController:logout');