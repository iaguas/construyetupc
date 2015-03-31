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
     * @param $colName nombre de la colección.
     * @param $id identificador del documento (string).
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
     * @param $name nombre de la colección.
     * @return mixed
     */
    public function mCreateCollection($name);

    /**
     * Inserta un documento en una colección.
     *
     * @param $document documento a insertar.
     * @param $colName colección donde insertarlo.
     */
    public function mInsertDocument($document, $colName);

    /**
     * Inserta un documento JSON en una colección.
     *
     * @param $json documento JSON.
     * @param $colName nombre de la colección (string).
     */
    public function mInsertJson($json, $colName);

    /**
     * Inserta un correo en la colección "emails_landing".
     *
     * @param string email a insertar.
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
     * @param $colName colección a vaciar (string).
     */
    public function mRemoveAllInCollection($colName);

    /**
     * Elimina uno o más documentos de una colección.
     *
     * @param $doc patrón que tienen que cumplir los documentos a borrar.
     *             Ejemplo: array('name' => 'power-supply')
     * @param $colName colección de la que eliminar los documentos.
     */
    public function mRemoveDocsInCollection($doc, $colName);

}
