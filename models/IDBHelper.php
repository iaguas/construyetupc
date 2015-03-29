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
     * Obtiene la lista de correos recibidos en la landing page.
     *
     * @return mixed array de correos.
     */
    public function mGetEmailsLanding();

    /**
     * Obtiene un sólo documento de una colección con un ID.
     *
     * @param $colName nombre de la colección.
     * @param $id identificador del documento (string).
     * @return array|null array que contiene el documento.
     */
    public function mGetDocumentByColAndId($colName, $id);

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
     * @param $collection colección donde insertarlo.
     */
    public function mInsertDocument($document, $collection);

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



}
