<?php

use App\DTO\FormData;
use App\Service\Form\Form;
use App\Service\Database\DatabaseConnection;
use App\Validator\FormValidator;
use App\Validator\OrderTableValidator;

header('Access-Control-Allow-Origin: *');

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$formHandler = new Form( DatabaseConnection::getConnection());

if (isset($_GET['getList'])) {
    echo $formHandler->getList(
        OrderTableValidator::validate($_GET['getList']) ? $_GET['getList'] : null
    );
}

if (isset($_GET['getCounters'])) {
    echo $formHandler->counter('kowalski', 'gmail.com');
}

if (isset($_POST) && count($_POST) > 0) {
        $formValidator = FormValidator::validate($_POST);

        if ($formValidator === true) {
            $formHandler->saveForm(new FormData(
                $_POST['name'],
                $_POST['surname'],
                $_POST['email'],
                $_POST['clientNumber'] ?? null,
                $_POST['choose'],
                $_POST['agreement1'],
                $_POST['agreement2'],
                $_POST['agreement3'] ?? null,
                $_POST['phone'] ?? null,
                $_POST['account'] ?? null,        
            ));

            echo json_encode(201);
        } else {
            echo json_encode($formValidator);
        }
}