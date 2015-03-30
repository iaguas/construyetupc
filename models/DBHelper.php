<?php
/**
 * Clase DBHelper
 *
 * Encapsula la interacción con la base de datos MongoDB.
 */

require_once 'IDBHelper.php';

class DBHelper implements IDBHelper {

    // Datos de conexión
    private $mongoDbName = 'construyetupc';
    private $db;

    /**
     * Método de conexión a la BD
     * Conexión local
     */
    function __construct() {
        $mon = new MongoClient();
        $db = $mon->selectDB($this->mongoDbName);
        $this->db = $db;
    }

    /***********************/
    /* Métodos de consulta */
    /***********************/

    /**
     * Obtiene la lista de correos recibidos en la landing page.
     *
     * @return mixed array de correos almacenados en Mongo.
     */
    public function mGetEmailsLanding() {
        $col = $this->db->emails_landing;
        return $col->find();
    }

    /**
     * Obtiene un sólo documento de una colección con un ID.
     *
     * @param $colName nombre de la colección.
     * @param $id identificador del documento (string).
     * @return array|null array que contiene el documento.
     */
    public function mGetDocumentByColAndId($colName, $id) {
        $col = $this->db->selectCollection($colName);
        return $col->findOne(array('_id' => new MongoId($id)));
    }

    /************************/
    /* Métodos de inserción */
    /************************/

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
     * Inserta un correo en la colección "emails_landing".
     *
     * @param string email a insertar.
     */
    public function mInsertEmailLanding($email) {
        $this->mInsertDocument($email, 'emails_landing');
    }

    /****************************/
    /* Métodos de actualización */
    /****************************/



    /**********************/
    /* Métodos de borrado */
    /**********************/



}
