<?php
/**
 *	Fichero: model.php
 * 	Descripcion: Archivo que contiene lo relacionado con el modelo del patrón MVC.
 *		El nombre de las funciones empiezan por la letra "m" (hace referencia al modelo para que se más sencillo localizarlo).
 *		Después de dicha letra, las palabras empiezan con mayúsculas.
 */

// Clase de encapsulación para MongoDB
require_once 'DBHelper.php';

// Creación del objeto de base de datos
$db = new DBHelper();

// Operaciones con GET, POST, Ajax, que usen la base de datos.

// Ejemplo:
//$res = $db->mGetEmailsLanding();

// TODO: Arreglo temporal debido al router!!

function mGetEmails() {
    $db = new DBHelper();
    $lista = $db->mGetEmailsLanding();
    return $lista;
}

/**
 * Registra un email enviado por la Landing Page tras ser filtrado
 * @param $email
 * @return bool
 */
// TODO: Incorporar esta función a validateLanding.php
function mRegisterEmail($email) {
    // Filtramos posibles inyecciones inyecciones...
    // Devuelve el correo o false, de ahí la forma en el que se muestra la condición
    if(filter_var($email, FILTER_VALIDATE_EMAIL) != false) {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $db = new DBHelper();
        $db->mInsertEmailLanding($email);
        return true;
    }else{
        return false;
    }
}
