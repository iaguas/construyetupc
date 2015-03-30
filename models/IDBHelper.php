<?php
/**
 * Interfaz IDBHelper
 *
 * Proporciona la interfaz para la interacción con la base de datos MongoDB.
 */
interface IDBHelper {

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
     * Obtiene la lista de correos recibidos en la landing page.
     *
     * @return mixed array de correos.
     */
    public function mGetEmailsLanding();

    /**
     * Inserta un correo en la colección "emails_landing".
     *
     * @param string email a insertar.
     */
    public function mInsertEmailLanding($email);

}
