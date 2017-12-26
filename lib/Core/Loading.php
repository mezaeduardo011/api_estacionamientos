<?php
namespace Catatumbo\Core;
/**
 * Clase encargada de gestionar todas la carga del sistema
 * @Author: Gregorio Bolívar <elalconxvii@gmail.com>
 * @Author: Blog: <http://gbbolivar.wordpress.com>
 * @Creation Date: 25/07/2017
 * @version: 0.7
 */
class Loading implements Constant
{
    public function auth()
    {

        $mw = function ($request, $response, $next) {
            $response->getBody()->write('BEFORE');
            $response = $next($request, $response);
            $response->getBody()->write('AFTER');
            return $response;
        };

    }
    public  function init($app)
    {

        $path = Constant::DIR_MODULE;
        include_once (Constant::DIR_SRC."common".DIRECTORY_SEPARATOR."main.php");
        $config = parse_ini_file(Constant::DIR_CONFIG.'app.ini', true);
        $dir = opendir($path);
        $data[]=null;
        // Leer todos los ficheros de la carpeta
        while ($elemento = readdir($dir)){
            // Tratamos los elementos . y .. que tienen todas las carpetas
            if( $elemento != "." && $elemento != ".." && $elemento != "common" && $elemento != "authentication"){
                // Verificar si es una Carpeta
                if( is_dir($path.$elemento) ){
                    // Muestro la carpeta
                    $file = $path."".$elemento.DIRECTORY_SEPARATOR."main.php";
                    $getFile=file_get_contents($file);
                    $getItem = $common->multiExplode(array("/**",'*/'),$getFile,$elemento,$config['default']['apiRestSi']);
                    //$common->printAll($getItem);

                    if(!is_null($getItem)){
                        $data[$elemento]=$getItem;
                    }
                    include_once($file);

                }
            }
        }
        $_SESSION['data']=$data;
    }

}
