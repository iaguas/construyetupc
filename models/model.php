<?php 
/**
*	Fichero: model.php
* 	Descripcion: Archivo que contiene todo lo relacionado con el modelo del patrón MVC.
*		El nombre de las funciones empiezan por la letra "m" (hace referencia al modelo para que se más sencillo localizarlo).
*		Después de dicha letra, las palabras empiezan con mayúsculas.
*/

/**
 * Clase DBHelper
 *
 * Encapsula la interacción con la base de datos MongoDB.
 */
class DBHelper {
    // Datos de conexión
    private $mongoIp = '127.0.0.1';
    private $mongoPort = 27017;
    private $mongoDbName = 'construyetupc';

    /**
     * Método de conexión a la BD
     * Conexión local
     */
    public function mConnectMongo() {
        $mon = new MongoClient();
        $db = $mon->selectDB($this->mongoDbName);
        echo 'Test conexión MongoDB: ';
        print($db);
    }
}

$db = new DBHelper();
$db->mConnectMongo();
?>