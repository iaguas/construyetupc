<?php
/**
 *	Fichero: model.php
 * 	Descripcion: Archivo que contiene todo lo relacionado con el modelo del patrón MVC.
 *		El nombre de las funciones empiezan por la letra "m" (hace referencia al modelo para que se más sencillo localizarlo).
 *		Después de dicha letra, las palabras empiezan con mayúsculas.
 */

// Clase de encapsulación para MongoDB
require_once 'DBHelper.php';

// Creación del objeto de base de datos
//$db = new DBHelper();

// Operaciones con GET, POST, Ajax, que usen la base de datos.

// Ejemplo:
//$res = $db->mGetEmailsLanding();

function mGetEmails(){
    $db = new DBHelper();
    $lista = $db->mGetEmailsLanding();
    return $lista;

}

function mResgiterEmail(){
    $db = new DBHelper();
    if(isset($_POST['email'])){
        $email = $_POST['email'];
        $db->mInsertEmailLanding($email);
        
    }


}