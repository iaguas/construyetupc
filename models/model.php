<?php 
/**
*	Fichero: model.php
* 	Descripcion: Archivo que contiene todo lo relacionado con el modelo del patrón MVC.
*/

/**
* Formulario de suscripción por correo
* Comprueba si se ha introducido un correo por petición POST, y lo almacena en la BBDD
*/
include 'DBHelper.php';


if(isset($_POST['email'])){
    $db = new DBHelper();
    $db->mCreateCollection('emails_landing');

    $doc1 = array(
        "email" => $_POST['email']
    );

    $db->mInsertDocument($doc1, 'emails_landing');
    echo "El email se ha procesado correctamente";
    echo "<br>";
    echo '<a href="../index.php?action=landing&id=1">Volver</a>';


}

?>