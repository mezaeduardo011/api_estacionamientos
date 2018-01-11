<?php
namespace Catatumbo\Core;

class Auth
{
    static function mainBasic($user,$clave,$ip='*'){
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="'.$_SERVER['SERVER_NAME'].'"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Autenticacion Basic Cancelada';
            exit;
        } else {
            if ($_SERVER['PHP_AUTH_USER']==$user AND $_SERVER['PHP_AUTH_PW']==$clave ) {
                return true;
            }else{
                header('WWW-Authenticate: Basic realm="'.$_SERVER['SERVER_NAME'].'');
                header('HTTP/1.0 401 Unauthorized');
                die ("Not authorized");
            }
        }
    }

    static function main(){

    }
}