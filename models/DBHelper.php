<?php
/**
 * Clase DBHelper
 *
 * Encapsula la interacción con la base de datos MongoDB.
 */

require_once 'IDBHelper.php';

class DBHelper implements IDBHelper {

    // Datos de conexión
    private static $mongoDbName = 'construyetupc';

    // Duración de la sesión de PHP en segundos
    private static $sessionMaxTime = 300; // 5 minutos

    private $db;

    /**
     * Método de conexión a la BD
     * Conexión local
     */
    function __construct() {
        $mon = new MongoClient();
        $db = $mon->selectDB(self::$mongoDbName);
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
     * Busca sobre una colección todos los productos que cumplen una regex concreta.
     *
     * @param $colName string con el nombre de la colección.
     * @param $searchText parte del nombre del producto que se debe buscar.
     * @return array con todos los productos que cumplen la forma del nombre.
     */
    public function mSearchProduct($colName, $searchText){
        // Acceso a la colección adecuada
        $col = $this->db->selectCollection($colName);
        
        // Consulta con la expresión regular y devolución de los resultados
        $regex = new MongoRegex("/$searchText/i"); 
        $where = array('name' => $regex);
        return $col->find($where);
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
     * Obtiene la lista de un componente determinado almacenado en mongoDB.
     */
    public function mGetComponent($ct) {
        $col = $this->db->selectCollection($ct);
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

    /**
     * Comprueba si el nombre de usuario y contraseña existen en la base de datos.
     *
     * @param $userName string nombre de usuario.
     * @param $password string contraseña hasheada con SHA-256.
     * @return bool true si existen, false en otro caso.
     */
    public function mCheckAdminUserCredentials($userName, $password) {
        $col = $this->db->selectCollection('users_admin');
        $res = $col->findOne(array('username' => $userName, 'password' => $password));

        if($res != NULL) {
            return true;
        }

        return false;
    }

    /**
     * Comprueba si la sesión es válida.
     *
     * @param $sessionid string identificador de la sesión.
     * @return bool true si es válida, false en caso contrario.
     */
    public function mCheckAdminSession($sessionid) {
        $col = $this->db->selectCollection('admin_sessions');
        $res = $col->findOne(array('sessionid' => $sessionid));

        // Comprobamos si la sesion es valida
        $sessionTime = $res['time'];

        if(abs(time() - $sessionTime) < self::$sessionMaxTime) {
            // Actualizamos el valor de la sesión
            $this->mUpdateSessionTime($sessionid);

            return true;
        }

        // Borramos la sesión expirada
        $rm = $col->remove(array('sessionid' => $sessionid));

        return false;
    }


    /**
     * Obtiene un array de arrays de precios por tiendas para el componente dado
     * @param $idComp número de serie "pn" del componente en mongoDB
     * @param $colName nombre de la colección en el que se encuentra el componente a buscar
     * @return array contiene los arrays contenidos en el campo "prices" del documento del componente
     */
    public function mGetCompPrices($idComp, $colName) {
        $col = $this->db->selectCollection($colName);
        // Argumentos de findOne(busqueda,campo_a_filtrar)
        $query = $col->findOne(array('pn' => $idComp),array('prices' => 1));
        $subq = $query['prices'];
        $comp = array();
        $comps = array();
        $max = sizeof($subq);
        for($i = 0; $i < $max;$i++){
            foreach ($subq[$i] as $key => $value){
                array_push($comp,$value);
            }
            array_push($comps,$comp);
            $comp = [];
        }

        return $comps;
    }

    /**
     * Obtiene el nombre del componente dado
     * @param $idComp pn del componente en mongoDB
     * @param $colName nombre de la colección en el que se encuentra el componente a buscar
     * @return string tabla que contiene el campo "nombre" del documento del componente
     */
    public function mGetCompName($idComp, $colName){
        $col = $this->db->selectCollection($colName);
        $query = $col->findOne(array('pn' => $idComp),array('name' => 1));

        return $query['name'];
    }

    /**
     * Obtiene todos los datos del componente dado a través de su PN
     * @param $pn PN del componente en mongoDB
     * @param $colName nombre de la colección en el que se encuentra el componente a buscar
     * @return array con los datos del componente requerido
     */
    public function mGetCompPropieties($pn, $colName){
        $col = $this->db->selectCollection($colName);
        $query = $col->findOne(array('pn' => $pn));
        return $query;
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

    public function mInsertAdminSession($sessionid) {
        $col = $this->db->selectCollection('admin_sessions');
        return $col->insert(array('sessionid' => $sessionid, 'time' => time()));
    }



    /****************************/
    /* Métodos de actualización */
    /****************************/

    /**
     * Actualizar datos
     * REQUISITO: El array debe estar bien construido
     */
    //Le falta actualizar precios.
    // Problemas para insertar los precios ya que no existen los de las diferentes tiendas.
    // Disponer de cómo funciona el tema de los precios. ¿Con otro array como hablé con Kevin?
        // Quitar algo si la araña no lo ha encontrado
    // Tener en cuenta la función update de Mongo
    public function mCompleteData($colName, $dataJSON) {
        // Colección donde están las cosas
        $col = $this->db->selectCollection($colName);

        // Cambio el json
        $data = json_decode($dataJSON, true);

        // Documento donde están las cosas
        //$doc = $col->findOne(array('_id' => new MongoId($data["id"])));

        // Analizamos los datos y metemos sólo lo necesario (los datos que faltan).
        foreach ($data as $item){
            $doc = $col->findOne(array('pn' => new MongoId($item["pn"])));
            if($doc != NULL) // El producto existe.
                foreach ($doc as $key => $value) {
                    if($doc[$key]=="")
                        $doc[$key] = $data[$key];
                }
            else // El producto no existía
                $this->mInsertDocument($item, $colName);
        }
    }

    /**
     * Actualiza el tiempo de una sesión concreta de PHP.
     *
     * @param $sessionid string identificador de la sesión de PHP.
     */
    private function mUpdateSessionTime($sessionid) {
        $col = $this->db->selectCollection('admin_sessions');
        $res = $col->update(array('sessionid' => $sessionid), array('$set' => array('time' => time())));
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

    /**
     * Elimina un documento de una colección con cierto número de producto pn.
     *
     * @param $pn número de producto del documento a borrar.
     * @param $colName string colección de la que eliminar los documentos.
     */
    public function mRemoveDocInCollectionForPN($pn, $colName) {
        $col = $this->db->selectCollection($colName);
        $doc = $col->findOne(array('pn' => new MongoId($pn)));
        return $col->remove($doc);
    }

    /*
     * Elimina la sesión indicada.
     *
     * @param $sessionid string sesión a eliminar.
     */
    public function mRemoveAdminSession($sessionid) {
        $col = $this->db->selectCollection('admin_sessions');
        $col->remove(array('sessionid' => $sessionid));

    }
}
