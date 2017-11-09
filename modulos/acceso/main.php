<?php
use Slim\Http\Request;
use Slim\Http\Response;
use Catatumbo\Core\Common;
use Catatumbo\Core\Constant;
use Catatumbo\Driverdb\Database;
use Catatumbo\Driverdb\ManageDataService;


try {
    $connec = new Database('catatumbo');
    $sd =  new ManageDataService;
} catch(PDOException $e) {
    die(print_r(json_encode(array('code' => 503, 'message'=> "Failed to connect to database ".$e->getMessage()))));
}
// Se encarga de extraer el nombre de la carpeta donde se instanciará
$module=$common->getFileExecute(__DIR__);


/**
* @apiService {post} #module/getLogin, Permite a los conductores (Usuarios de tipo usertype_id = 3) iniciar session en la app
* @apiVersion 0.1.0
* @apiName getLogin
* @apiGroup #group
* 
*  @apiParam {integer} email, Correo del usuario para iniciar session.
*  @apiParam {integer} password, Clave del usuario para iniciar session.
*
* @apiRSuccess {Integer} code,  Código 0 conforme todo ha ido bien.
* @apiRSuccess {Varchar} message, Si existen registro con la condición no trae mensaje, de lo contrario debe mostrar #MESSAGE_LIST_00  
* @apiRSuccess {Json} data, debe extraer un json con el usuario
*
* @apiRSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*     {
*       "code": 0,
*       "message": '' OR '#MESSAGE_LIST_00',
*       "data": [{}]
*     }
*
* @apiRError {Integer} code,  Código 1 no todo ha ido bien.
* @apiRError {Varchar} message,  #MESSAGE_ERRORSS
*
* @apiRErrorExample Error-Response:
*     HTTP/1.1 200 OK
*     {
*       "code": 0,
*       "message":'#MESSAGE_ERRORSS',
*       "data":      
*     }
*/

    $app -> post('/'.$module.'/getLogin', function(Request $request, Response $response, array $args) use ($connec,$sd){
        $route = $request->getAttribute('route');
        $conn = end($connec);
        $email = $request->getParam('email');
        $password = $request->getParam('password');
        $sql = "SELECT * FROM users AS a WHERE a.email='$email' AND a.password='$password ' AND a.status=1 AND a.usertype_id=3";
        $sd->getData($conn,$sql,$connec);
    });


/**
* @apiService {post} #module/setResetpassword, Permite a los usuarios enviar un token numerico de 8 digitos al correo electronico insertado por el usuario. 
* @apiVersion 0.1.0
* @apiName setResetpassword
* @apiGroup #group
* 
*  @apiParam {integer} email, Correo del usuario para restablecer la clave
*
* @apiRSuccess {Integer} code,  Código 0 conforme todo ha ido bien.
* @apiRSuccess {Varchar} message, Si existen registro con la condición no trae mensaje, de lo contrario debe mostrar #MESSAGE_LIST_00  
* @apiRSuccess {Json} data, debe extraer un json con el usuario
*
* @apiRSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*     {
*       "code": 0,
*       "message": '' OR '#MESSAGE_LIST_00',
*       "data": [{}]
*     }
*
* @apiRError {Integer} code,  Código 1 no todo ha ido bien.
* @apiRError {Varchar} message,  #MESSAGE_ERRORSS
*
* @apiRErrorExample Error-Response:
*     HTTP/1.1 200 OK
*     {
*       "code": 0,
*       "message":'#MESSAGE_ERRORSS',
*       "data":      
*     }
*/

// """ Method encargado de enviar las notificacione de mensaje de textos """
    $app -> post('/'.$module.'/setResetpassword', function(Request $request, Response $response, array $args) use ($connec,$sd){
        $route = $request->getAttribute('route');
        $conn = end($connec);
        $email = $request->getParam('email');
        $sql = "SELECT * FROM users AS a WHERE a.email='$email' AND a.status=1 AND a.usertype_id=3";
        $sd->getData($conn,$sql,$connec);

    });



/**
* @apiService {post} #module/setResetpasswordConfirm, Valida el token insertado por el usuario.
* @apiVersion 0.1.0
* @apiName setResetpasswordConfirm
* @apiGroup #group
* 
*  @apiParam {integer} code, Codigo del usuario para restablecer la clave
*
* @apiRSuccess {Integer} code,  Código 0 conforme todo ha ido bien.
* @apiRSuccess {Varchar} message, Si existen registro con la condición no trae mensaje, de lo contrario debe mostrar #MESSAGE_LIST_00  
* @apiRSuccess {Json} data, debe extraer un json con el usuario
*
* @apiRSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*     {
*       "code": 0,
*       "message": '' OR '#MESSAGE_LIST_00',
*       "data": [{}]
*     }
*
* @apiRError {Integer} error,  Código 1 no todo ha ido bien.
* @apiRError {Varchar} message,  #MESSAGE_ERRORSS
*
* @apiRErrorExample Error-Response:
*     HTTP/1.1 200 OK
*     {
*       "code": 0,
*       "message":'#MESSAGE_ERRORSS',
*       "data":      
*     }
*/

// """ Method encargado de enviar las notificacione de mensaje de textos """
    $app -> post('/'.$module.'/setResetpasswordConfirm', function(Request $request, Response $response, array $args) use ($connec,$sd){
        $route = $request->getAttribute('route');
        $conn = end($connec);
        $email = $request->getParam('email');
        $sql = "SELECT * FROM users AS a WHERE a.email='$email' AND a.status=1 AND a.usertype_id=3";
        $sd->getData($conn,$sql,$connec);

    });


/**
* @apiService {post} #module/setChangepasswowd, Permite al usuario actualizar su contraseña, 
* @apiVersion 0.1.0
* @apiName setChangepasswowd
* @apiGroup #group
* 
*  @apiParam {integer} code, Codigo del usuario para restablecer la clave
*
* @apiRSuccess {Integer} code,  Código 0 conforme todo ha ido bien.
* @apiRSuccess {Varchar} message, Si existen registro con la condición no trae mensaje, de lo contrario debe mostrar #MESSAGE_LIST_00  
* @apiRSuccess {Json} data, debe extraer un json con el usuario
*
* @apiRSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*     {
*       "code": 200,
*       "message": '' OR '#MESSAGE_LIST_00',
*       "data": [{}]
*     }
*
* @apiRError {Integer} code,  Código 1 no todo ha ido bien.
* @apiRError {Varchar} message,  #MESSAGE_ERRORSS
*
* @apiRErrorExample Error-Response:
*     HTTP/1.1 200 OK
*     {
*       "code": 400,
*       "message":'#MESSAGE_ERRORSS',
*       "data":      
*     }
*/

// """ Method encargado de enviar las notificacione de mensaje de textos """
    $app -> post('/'.$module.'/setChangepasswowd', function(Request $request, Response $response, array $args) use ($connec,$sd){
        $route = $request->getAttribute('route');
        $conn = end($connec);
        $email = $request->getParam('email');
        $sql = "SELECT * FROM users AS a WHERE a.email='$email' AND a.status=1 AND a.usertype_id=3";
        $sd->getData($conn,$sql,$connec);

    });


    /**
* @apiService {post} #module/setPendingtips, Obtiene el total de los tips pendientes por solicitar.
* @apiVersion 0.1.0
* @apiName setPendingtips
* @apiGroup #group
* 
*  @apiParam {integer} code, Codigo del usuario para restablecer la clave
*
* @apiRSuccess {Integer} code,  Código 0 conforme todo ha ido bien.
* @apiRSuccess {Varchar} message, Si existen registro con la condición no trae mensaje, de lo contrario debe mostrar #MESSAGE_LIST_00  
* @apiRSuccess {Json} data, debe extraer un json con el usuario
*
* @apiRSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*     {
*       "code": 0,
*       "message": '' OR '#MESSAGE_LIST_00',
*       "data": [{}]
*     }
*
* @apiRError {Integer} code,  Código 1 no todo ha ido bien.
* @apiRError {Varchar} message,  #MESSAGE_ERRORSS
*
* @apiRErrorExample Error-Response:
*     HTTP/1.1 200 OK
*     {
*       "error": 0,
*       "message":'#MESSAGE_ERRORSS',
*       "data":      
*     }
*/

// """ Method encargado de enviar las notificacione de mensaje de textos """
    $app -> post('/'.$module.'/setPendingtips', function(Request $request, Response $response, array $args) use ($connec,$sd){
        $route = $request->getAttribute('route');
        $conn = end($connec);
        $email = $request->getParam('email');
        $sql = "SELECT * FROM users AS a WHERE a.email='$email' AND a.status=1 AND a.usertype_id=3";
        $sd->getData($conn,$sql,$connec);

    });

?>