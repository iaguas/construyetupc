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
     * Obtiene un sólo documento de una colección con un ID.
     *
     * @param $colName string nombre de la colección.
     * @param $id string identificador del documento.
     * @return array|null array que contiene el documento.
     */
    public function mGetDocumentByColAndId($colName, $id) {
        $col = $this->db->selectCollection($colName);
        return $col->findOne(array('_id' => new MongoId($id)));
    }

    /**
     * Obtiene la lista de correos recibidos en la landing page.
     *
     * @return mixed array de correos almacenados en Mongo.
     */
    public function mGetEmailsLanding() {
        $col = $this->db->selectCollection('emails_landing');
        return $col->find();
    }

    /**
     * Determina si el email a registrar en la Landing Page se encuentra registrado o no.
     *
     */
    public function mMatchEmailLanding($email) {
        $col = $this->db->selectCollection('emails_landing');
        $cont = $col->count(array('email'=>$email));
        if($cont == 0){
            return 0;//no está
        }else{
            return 1;//está
        }
    }


    /**
     * Obtiene la lista de correos recibidos en la landing page,
     * que respondan a un consulta concreta.
     *
     * @param $query mixed consulta (where de SQL).
     * @return mixed array de correos almacenados en Mongo.
     */
    public function mGetCpus() {
        $col = $this->db->selectCollection('cpus');
        return $col->find();
    }

    /**
     * Obtiene la lista de cpus que respondan a un consulta concreta
     *
     * @param $query mixed consulta (where de SQL).
     * @return mixed array de correos almacenados en Mongo.
     */
    public function mGetEmailsByQuery($query) {
        $col = $this->db->selectCollection('emails_landing');
        return $col->find($query);
    }

    /**
     * Obtiene las categorías de hardware y demás datos sobre las mismas.
     *
     * @return MongoCursor categorías y datos sobre las mismas.
     */
    public function mGetHardwareCategories() {
        $col = $this->db->selectCollection('hardware_categories');
        return $col->find();
    }

    /************************/
    /* Métodos de inserción */
    /************************/

    /**
     * Crea una colección en la BD.
     *
     * @param $name string nombre de la colección.
     * @return mixed
     */
    public function mCreateCollection($name) {
        return $this->db->createCollection($name);
    }

    /**
     * Inserta un documento en una colección.
     *
     * @param $document array documento a insertar.
     * @param $colName string colección donde insertarlo.
     */
    public function mInsertDocument($document, $colName) {
        $col = $this->db->selectCollection($colName);
        $col->insert($document);
    }

    /**
     * Inserta un documento JSON en una colección.
     *
     * @param $json string documento JSON.
     * @param $colName string nombre de la colección.
     */
    public function mInsertJson($json, $colName) {
        // Conversión JSON a Array
        $array = json_decode($json, true);

        // Inserción del array en BD
        foreach($array as $id => $item) {
            $this->mInsertDocument($item, $colName);
        }
    }

    /**
     * Inserta un correo en la colección "emails_landing".
     *
     * @param $email string email a insertar.
     */
    public function mInsertEmailLanding($email) {
        $email_array = array('email' => $email);
        $this->mInsertDocument($email_array, 'emails_landing');
    }

    /****************************/
    /* Métodos de actualización */
    /****************************/

    /**
     * Actualizar datos
     * REQUISITO: El array debe estar bien construido
     */
    public function mCompleteData($colName, $data) {
        // Colección donde están las cosas
        $this->db->createCollection($data);
        $col = $this->db->selectCollection($colName);
        /*
        // Documento donde están las cosas
        $doc = $col->findOne(array('_id' => new MongoId($data["id"])));

        // Analizamos los datos y metemos sólo lo necesario (los datos que faltan).
        foreach ($data as $key => $item) 
            if($doc[$key]=="")
                $doc[$key] = $data[$key];
        */
        if($col->insert($data)){
            return true;
        }else{
            return false;
        }
    }


    /**********************/
    /* Métodos de borrado */
    /**********************/

    /**
     * Vacía una colección.
     *
     * @param $colName string colección a vaciar.
     */
    public function mRemoveAllInCollection($colName) {
        $col = $this->db->selectCollection($colName);
        $col->remove();
    }

    /**
     * Elimina uno o más documentos de una colección.
     *
     * @param $doc array patrón que tienen que cumplir los documentos a borrar.
     *             Ejemplo: array('name' => 'power-supply')
     * @param $colName string colección de la que eliminar los documentos.
     */
    public function mRemoveDocsInCollection($doc, $colName) {
        $col = $this->db->selectCollection($colName);
        return $col->remove($doc);
    }

}
