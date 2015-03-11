<?php
/**
*	Fichero: index.php
* 	Descripcion: Archivo que contiene todo lo relacionado con el controlador del patrón MVC.
*/
	
	require_once("models/model.php");
    require_once("view.php");

	/**
	 * Control de la acción que se lleva a cabo.
	 */
	if (isset($_GET["action"])){
		$action = $_GET["action"]; 
	}	
	else {
		if (isset($_POST["action"])){
			$action = $_POST["action"];
		}
		else {
			$action = "landing";
            // Descomentar cuando quitemos la Landing Page
            //$action = "main";
		}
	}	

	/**
	 * Control de la id.
	 */
	if (isset($_GET["id"])){
		$id = $_GET["id"]; 
	}	
	else {
		if (isset($_POST["id"])) {
			$id = $_POST["id"];
		}
		else {
			$id = 1;
		}
	}

	/**
	 * Cargamos la página principal
	 */
	switch($action) {
        // Landing page
        case 'landing':
            vLandingPage();
            break;

        // Main page
        case 'main':
            switch ($id){
            case 1:
                vMainPage();
                break;
            }
            break;
	}
?>