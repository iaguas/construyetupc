<?php 
/**
*	Fichero: Vista.php
* 	Descripcion: Archivo que contiene todo lo relacionado con la vista del patrón MVC.
*		El nombre de las funciones empiezan por la letra "v" (hace referencia a la vista para que se más sencillo localizarlo).
*		Después de dicha letra, las palabras empiezan con mayúsculas.
*/

    /**
     * Muestra la Langing Page.
     */
	function vLandingPage() {
        $landing = file_get_contents("views/landing.html");
        echo $landing;
    }

	/**
	 * Muestra la página principal.
	 */
	function vMainPage() {
		$page = file_get_contents("views/index.html");
		echo $page;
	}

    /**
     * Muestra la lista de componentes.
     */
    function vPartList() {
        $page = file_get_contents("partlist.php");
        echo $page;
    }
	
	/**
	 * Muestra la lista de selección de modelo de componente
	 */
	function vShowComponentSelection($component){
		$page = file_get_contents("views/components/cpu.html");
		echo $page;
	}
	
?>