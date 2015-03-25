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
    private $mongoDbName = 'construyetupc';
    private $db;

    /**
     * Método de conexión a la BD
     * Conexión local
     */
    public function __construct() {
        $mon = new MongoClient();
        $db = $mon->selectDB($this->mongoDbName);
        $this->db = $db;
    }

    /**
     * Crea una colección en la BD.
     *
     * @param $name nombre de la colección.
     * @return mixed
     */
    public function mCreateCollection($name) {
        return $this->db->createCollection($name);
    }

    /**
     * Inserta un documento en una colección.
     *
     * @param $document documento a insertar.
     * @param $collection colección donde insertarlo.
     */
    public function mInsertDocument($document, $collection) {
        $col = $this->db->$collection;
        $col->insert($document);
    }

    /**
     * NO USAR!! En construcción...
     */
    public function mGetEmailsLanding() {
        $col = $this->db->emails_landing;
        $cursor = $col->find();

        foreach($cursor as $doc) {
            // Insertar $doc en un array, así hasta que acabe el foreach y returnarlo!
        }
    }

}

/**
 * ¡¡¡¡¡¡¡¡ MUNARRIZ !!!!!!!!
 *
 * Básate en este código para hacer lo del correo de la landing page.
 */
//$db = new DBHelper();
//$db->mCreateCollection('emails_landing');

/*$doc1 = array(
    "email" => "test1@test.com"
);

$db->mInsertDocument($doc1, 'emails_landing');*/
?>