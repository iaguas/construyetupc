<?php
/**
 *    Fichero: validateLanding.php
 *    Descripcion: Este fichero php permite tratar el envío de direcciones de correo por Angular desde la Landing Page
 */

// Usamos método de model.php
require_once 'model.php';

$postData = file_get_contents('php://input');
$request = json_decode($postData);
@$email = $request->email;

if(mRegisterEmail($email)) {
    echo 'regOk';
}
else {
    echo 'regErr';
}
/*
// Operaciones con GET, POST, Ajax, que usen la base de datos.
$errors = array(); // array to hold validation errors
$data = array(); // array to pass back data
// validate the variables ======================================================
if (empty($_POST['email']))
    $errors['email'] = 'Escriba una dirección de correo.';
// return a response ===========================================================
// response if there are errors
if (!empty($errors)) {
    // if there are items in our errors array, return those errors
    $data['success'] = false;
    $data['errors'] = $errors;
    $data['messageError'] = 'Compruebe los campos en rojo';
} else {
    // if there are no errors, return a message
    $data['success'] = true;
    $data['messageSuccess'] = 'Gracias por su atención. Te contestaremos en breve ;)';
    // CHANGE THE TWO LINES BELOW
    $email_to = "yourEmailHere@gmail.com";
    $email_subject = "message submission";
    $name = $_POST['name']; // required
    $email_from = $_POST['email']; // required
    $message = $_POST['message']; // required
    $email_message = "Form details below.nn";
    $email_message .= "Name: ".$name."n";
    $email_message .= "Email: ".$email_from."n";
    $email_message .= "Message: ".$message."n";
    $headers = 'From: '.$email_from."rn".
    'Reply-To: '.$email_from."rn" .
    'X-Mailer: PHP/' . phpversion();
}
// return all our data to an AJAX call
echo json_encode($data);
*/