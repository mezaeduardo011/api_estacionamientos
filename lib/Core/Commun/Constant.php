<?php
namespace Catatumbo\Core\Commun;

/**
 * Clase encargada de gestionar todas las contantes de toda la estructura del sistema
 * Recomendable no modificarla puede influir el funcionamiento del sistema
 * @Author: Gregorio Bolívar <elalconxvii@gmail.com>
 * @Author: Blog: <http://gbbolivar.wordpress.com>
 * @Creation Date: 25/07/2017
 * @version: 0.9
 */

interface Constant 
{
    const DIR_INT = __DIR__ . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
    const DIR_INT2 = __DIR__ . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
    const DIR_SRC = Constant::DIR_INT.'src'.DIRECTORY_SEPARATOR;
    const DIR_CONFIG = Constant::DIR_INT."config".DIRECTORY_SEPARATOR;
    const DIR_THEME = Constant::DIR_INT.'theme'.DIRECTORY_SEPARATOR;
    const DIR_WEB = Constant::DIR_INT.'web'.DIRECTORY_SEPARATOR;

    const VERSION = "4";
    const FW = "Hornero";

    // Constant necesaria para la generación de la aplicaciones
    const APP_CACHE = DIRECTORY_SEPARATOR.'Cache'; // Store
    const APP_ROUTE = DIRECTORY_SEPARATOR.'Router'; // Store
    const APP_MODEL = DIRECTORY_SEPARATOR.'Model'; // Model
    const APP_CONTR = DIRECTORY_SEPARATOR.'Controller'; // Constrolador
    const APP_VIEWS = DIRECTORY_SEPARATOR.'Templates'; // Responce
    const APP_VHOME = DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'home'; // Responce
    const APP_VISTA = DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'vistas'; // Responce
    const APP_TKEYS = DIRECTORY_SEPARATOR.'extends';
    const PHP_VER_REQ = '7.0.0'; // Minimo

    // Request Methods
    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';

    // Server Path Web
    const PATH_SERVE = 'web'.DIRECTORY_SEPARATOR;

    // Valores del Registro de log de Acciones
    const LOG_CONS = 'Consulta de Registro';
    const LOG_ALTA = 'Alta de Registro';
    const LOG_MODI = 'Actualizacion de Registro';
    const LOG_BAJA = 'Baja de Registro';

    // Valores del LogFile
    const LOG_DIR = '../logs/';

}

?>
