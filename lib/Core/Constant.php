<?php
namespace Catatumbo\Core;

/**
 * Clase encargada de gestionar todas las contantes de toda la estructura del sistema
 * @Author: Gregorio Bolívar <elalconxvii@gmail.com>
 * @Author: Blog: <http://gbbolivar.wordpress.com>
 * @Creation Date: 25/07/2017
 * @version: 0.7
 */

interface Constant 
{

    const DIR_MODULE = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'modulos'.DIRECTORY_SEPARATOR;
    const DIR_CONFIG = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR;
    const DIR_THEME = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR;

    const VERSION = "1.0";
    const FW = "Catatumbo 2";


    const MESSAGE_CREATE = "Exito: Se ha Registrado correctamente.";
    const MESSAGE_UPDATE = "Exito: Se ha Actualizado correctamente.";
    const MESSAGE_DELETE = "Exito: Se ha Eliminado correctamente.";
    const MESSAGE_NOTIFIC = "Éxito: el registro fue exitoso, me enviaron un correo electrónico para activar su cuenta de usuario.";
    const MESSAGE_INSETSU = "Listado sin registros asociado.";
    const MESSAGE_ERRORSS = "Error: se encontro error en el proceso .";
    const MESSAGE_INFORSS = "Notificación: datos ya grabados.";
    const MESSAGE_SIGNINS = "Usuario y la contraseña no están registrados.";
    const MESSAGE_ACTIVAT = "Cuenta activada exitosamente";
    const MESSAGE_ACTIVNO = "Cuenta no activada .";
    const MESSAGE_LIST_00 = "Sin registro asociado";
    const MESSAGE_HTTP200 = "200 OK";
    const MESSAGE_HTTP204 = "204 No hay contenido";
    const MESSAGE_HTTP400 = '400 Error capa de Validacion'
    const MESSAGE_HTTP501 = "401 Es obigatorio su autenticacion";
    const MESSAGE_HTTP404 = "404 Ruta no encontrada";
    const MESSAGE_HTTP500 = "500 Error Interno";


}

?>
