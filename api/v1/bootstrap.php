<?php 
//---------------------------------------------------------------------------//
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
set_error_handler("ErrorHandler::handleError");
set_exception_handler("errorHandler::handleException");
//---------------------------------------------------------------------------//
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
//---------------------------------------------------------------------------//
header("Content-type: application/json; charset=UTF-8");
//---------------------------------------------------------------------------//