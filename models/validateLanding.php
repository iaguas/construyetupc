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
$opcion=mRegisterEmail($email);
if($opcion==1) {
    echo 'regOk';//Email registrado correctamente
}
else if ($opcion==0){
    echo 'emailErr'; //Email ya registrado
}
else {
    echo 'regErr';//Email no válido
}
