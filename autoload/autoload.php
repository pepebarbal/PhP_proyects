<?php
/**
 * Created by PhpStorm.
 * User: JC Mora
 * Date: 11/08/18
 * Time: 14:01
 */

/**
 * Function autoload para cargar las clases del proyecto.
 *
 * @param $clase
 * Nombre de clase a cargar.
 */
function calendario_autoload($clase) {
    $carpetasPhp = array(
        'ajax',
        'config',
        'includes',
    );
    foreach ($carpetasPhp as $carpeta) {
        $projectRoot = dirname(__DIR__);
        $filename = $projectRoot . '/' . $carpeta . '/' . $clase . '.php';
        if (file_exists($filename)) {
            require_once $filename;
        }
    }
}

spl_autoload_register('calendario_autoload',true, true);
