<?php
/**
 * Interfaz IDBHelper
 *
 * Proporciona la interfaz para la interacción con la base de datos MongoDB.
 */
interface IDBHelper {

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
    public function mGetDocumentByColAndId($colName, $id);

    /**
     * Obtiene la lista de correos recibidos en la landing page.
     *
     * @return mixed array de correos.
     */
    public function mGetEmailsLanding();

    /**
     * Determina si el email a registrar en la Landing Page se encuentra registrado o no.
     *
     * @param $email string correo a buscar.
     * @return true/false, dependiendo de si se ha hallado el email o no.
     */
    public function mMatchEmailLanding($email);

    /**
     * Obtiene la lista de correos recibidos en la landing page,
     * que respondan a un consulta concreta.
     *
     * @param $query mixed consulta (where de SQL).
     * @return mixed array de correos almacenados en Mongo.
     */
    public function mGetEmailsByQuery($query);

    /**
     * Obtiene las categorías de hardware y demás datos sobre las mismas.
     *
     * @return MongoCursor categorías y datos sobre las mismas.
     */
    public function mGetHardwareCategories();

    /************************/
    /* Métodos de inserción */
    /************************/

    /**
     * Crea una colección en la BD.
     *
     * @param $name string nombre de la colección.
     * @return mixed
     */
    public function mCreateCollection($name);

    /**
     * Inserta un documento en una colección.
     *
     * @param $document array documento a insertar.
     * @param $colName string colección donde insertarlo.
     */
    public function mInsertDocument($document, $colName);

    /**
     * Inserta un documento JSON en una colección.
     *
     * @param $json string documento JSON.
     * @param $colName string nombre de la colección (string).
     */
    public function mInsertJson($json, $colName);

    /**
     * Inserta un correo en la colección "emails_landing".
     *
     * @param $email string email a insertar.
     */
    public function mInsertEmailLanding($email);

    /****************************/
    /* Métodos de actualización */
    /****************************/



    /**********************/
    /* Métodos de borrado */
    /**********************/

    /**
     * Vacía una colección.
     *
     * @param $colName string colección a vaciar.
     */
    public function mRemoveAllInCollection($colName);

    /**
     * Elimina uno o más documentos de una colección.
     *
     * @param $doc array patrón que tienen que cumplir los documentos a borrar.
     *             Ejemplo: array('name' => 'power-supply')
     * @param $colName string colección de la que eliminar los documentos.
     */
    public function mRemoveDocsInCollection($doc, $colName);

}
