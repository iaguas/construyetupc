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


// TODO: Incorporar esta función a validateLanding.php
/**
 * Registra un email enviado por la Landing Page tras ser filtrado y comprobada su no existencia en la BD
 * @param $email correo a registrar/comprobar su existencia
 * @return int código de exito/fracaso en función del resultado
 *  1: registrado correctamente
 *  0: ya registrado
 *  -1: no válido
 */
function mRegisterEmail($email) {
    // Filtramos posibles inyecciones inyecciones...
    // Devuelve el correo o false, de ahí la forma en el que se muestra la condición
    if(filter_var($email, FILTER_VALIDATE_EMAIL) != false) {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        // Se coteja con la base de datos
        $db = new DBHelper();
        $found = $db->mMatchEmailLanding($email);
        if($found==0) {
            $db->mInsertEmailLanding($email);
            return 1;//registrado correctamente
        } else {
            return 0;//Email ya registrado
        }
    }else{
        return -1;//Email no válido
    }
}
