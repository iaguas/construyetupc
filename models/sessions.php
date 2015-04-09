<?php
/**
 *	Fichero: sessions.php
 * 	Descripcion: Contiene toda la funcionalidad relacionada con la gestión de sesiones.
 *		El nombre de las funciones empiezan por la letra "s" (hace referencia al modelo de sesiones para que se más sencillo localizarlo).
 */

/**
 * Comprueba si el usuario tiene definida la variable que almacena la lista de componentes elegidos. Si no la tiene
 * la crea.
 */
function sCheckSessionVar(){
    if (!isset($_SESSION['partList'])){
        $_SESSION['partList'] = [
            'cpu' => null,
            'gpu' => null,
            'cpu-cooler' => null,
            'memory' => null,
            'case' => null,
            'monitor' => null,
            'motherboard' => null,
            'power-supply' => null,
            'optical-drive' => null,
            'storage' => null
        ];
    }
}

/**
 * Añade un componente a la lista de partes elegidas
 */
function sAddPart(){
    if (isset($_GET["cpuId"])){
        $_SESSION['partList']['cpu'] = $_GET["cpuId"];
    }elseif (isset($_GET["gpuId"])){
        $_SESSION['partList']['gpu'] = $_GET["gpuId"];
    }elseif (isset($_GET["cpu-coolerId"])){
        $_SESSION['partList']['cpu-cooler'] = $_GET["cpu-coolerId"];
    }elseif (isset($_GET["memoryId"])){
        $_SESSION['partList']['memory'] = $_GET["memoryId"];
    }elseif (isset($_GET["caseId"])){
        $_SESSION['partList']['case'] = $_GET["caseId"];
    }elseif (isset($_GET["monitorId"])){
        $_SESSION['partList']['monitor'] = $_GET["monitorId"];
    }elseif (isset($_GET["motherboardId"])){
        $_SESSION['partList']['motherboard'] = $_GET["motherboardId"];
    }elseif (isset($_GET["power-supplyId"])){
        $_SESSION['partList']['power-supply'] = $_GET["power-supplyId"];
    }elseif (isset($_GET["optical-driveId"])){
        $_SESSION['partList']['optical-drive'] = $_GET["optical-driveId"];
    }elseif (isset($_GET["storageId"])){
        $_SESSION['partList']['storage'] = $_GET["storageId"];
    }
}

/**
 * Elimina un componente de la lista de partes elegidas
 */
function sRemovePart(){
    if (isset($_GET["part"])) {
        $categoryToRemove = $_GET["part"];
        switch ($categoryToRemove) {
            case 'cpu':
                $_SESSION['partList']['cpu'] = null;
                break;
            case 'cpu-cooler':
                $_SESSION['partList']['cpu-cooler'] = null;
                break;
            case 'gpu':
                $_SESSION['partList']['gpu'] = null;
                break;
            case 'memory':
                $_SESSION['partList']['memory'] = null;
                break;
            case 'case':
                $_SESSION['partList']['case'] = null;
                break;
            case 'monitor':
                $_SESSION['partList']['monitor'] = null;
                break;
            case 'motherboard':
                $_SESSION['partList']['motherboard'] = null;
                break;
            case 'power-supply':
                $_SESSION['partList']['power-supply'] = null;
                break;
            case 'optical-drive':
                $_SESSION['partList']['optical-drive'] = null;
                break;
            case 'storage':
                $_SESSION['partList']['storage'] = null;
                break;
        }
    }
}
