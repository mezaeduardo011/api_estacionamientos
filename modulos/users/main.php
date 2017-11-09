<?php
use Slim\Http\Request;
use Slim\Http\Response;
use Catatumbo\Core\Common;
use Catatumbo\Core\Constant;
use Catatumbo\Driverdb\Database;
use Catatumbo\Driverdb\ManageDataService;

// Conexion contra base de datos
try {
    $connec = new Database('catatumbo');
    $sd =  new ManageDataService();
} catch(\PDOException $e) {
    die(print_r(json_encode(array('code' => 3, 'message'=> "Failed to connect to database ".$e->getMessage()))));
}
// Se encarga de extraer el nombre de la carpeta donde se instanciará
$module=$common->getFileExecute(__DIR__);



//uthUser = $req->headers('PHP_AUTH_USER');

/**
* @apiService {get} #module/getUsersListar, Obtiene todos los usuario del sistema
* @apiVersion 0.1.0
* @apiName getUsersListar
* @apiGroup #group
*
* @apiRSuccess {Integer} code,  Código 200 conforme todo ha ido bien.
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
* @apiRError {Integer} code,  Código 204 no todo ha ido bien.
* @apiRError {Varchar} message,  #MESSAGE_ERRORSS
*
* @apiRErrorExample Error-Response:
*     HTTP/1.1 400 OK
*     {
*       "code": 0,
*       "message":'#MESSAGE_ERRORSS',
*       "data":      
*     }
*/


// """ Method encargado de enviar las notificacione de mensaje de textos """
    $app->get('/'.$module.'/getUsersListar', function(Request $request, Response $response, array $args) use ($connec,$sd){

        $route = $request->getAttribute('route');
        $conn = end($connec);
        $sql = "SELECT u.*, p.usertype_name FROM users as u 
                INNER JOIN usertypes as p on p.id = u.usertype_id";
        return $sd->setData($conn,$sql,$connec,$response);
        
    });


/**
* @apiService {get} #module/getUsers/{id}, Obtiene un usuario mediante el id.
* @apiVersion 0.1.0
* @apiName getUsers
* @apiGroup #group
*
* @apiParam {Integer} id, Identificador de registro debe ingresar id.
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
    $app -> get('/'.$module.'/getUsers/{id}', function(Request $request, Response $response, array $args) use ($connec,$sd){
        $route = $request->getAttribute('route');
        $id = $route->getArgument('id');
        $conn = end($connec);
        $sql = "SELECT u.*, p.usertype_name FROM users as u 
                INNER JOIN usertypes as p on p.id = u.usertype_id
                WHERE u.id = '$id'";
        $sd->getData($conn,$sql,$connec);
    });


    /**
     * @apiService {post} #module/usersCreate, Registrar un usuario.
     * @apiVersion 2.1.0
     * @apiName usersCreate
     * @apiGroup #group
     * 
     * @apiParam  Varchar(11) {code}, Requerido NULL codigo de usuario.             
     * @apiParam  Int(11) {usertype_id}, Requerido NOT NULL tipo de usuario.
     * @apiParam  Varchar(50) {first_name}, Requerido NOT NULL Primer nombre de usuario.            
     * @apiParam  Varchar(50) {middle_name}, Requerido NULL  Segundo nombre de usuario.            
     * @apiParam  Varchar(50) {last_name}, Requerido NOT NULL  Primer apellido del usuario.        
     * @apiParam  Varchar(50) {mother_surname}, Requerido NULL Seundo apellido del usuario.            
     * @apiParam  Date  {birth_date}, Requerido NULL Fecha de cumple.              
     * @apiParam  Varchar(20) {phone_home}, Requerido NULL Telefono de casa.              
     * @apiParam  Varchar(20) {phone_mobile}, Requerido NOT NULL Telefono movil.               
     * @apiParam  Varchar(20) {phone_work}, Requerido NULL Telefono del trabajo            
     * @apiParam  Varchar(20) {fax}, Requerido NULL Fax.            
     * @apiParam  Varchar(50) {company_name}, Requerido NULL Nombre de la compañia.               
     * @apiParam  Varchar(128) {email}, Requerido NULL Correo.             
     * @apiParam  Varchar(128) {password}, Requerido NOT NULL  Clave.                
     * @apiParam  Varchar(250) {tokenhash}, Requerido NOT NULL  Hash de Seguridad.              
     * @apiParam  Int(11) {status}, Requerido NOT NULL  Estatud del usuario.                      
     * @apiParam  Datetime {created}, Requerido NOT NULL  Fecha de Creacion.        
     * @apiParam  Int(11) {createdby}, Requerido NULL Cual usuario hace el registro.    
     * @apiParam  Datetime {modified}, Requerido NULL Fecha Modificacion.        
     * @apiParam  Int(11) {modifiedby}, Requerido NULL Quien hizo la modificacion.  
     *
     * @apiRSuccess {Integer} code,  Código 0 conforme todo ha ido bien.
     * @apiRSuccess {Varchar} message,  #MESSAGE_INSETSU
     *
     * @apiRSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 0,
     *       "code": '#MESSAGE_CODE',
     *       "message": '#MESSAGE_INSETSU'
     *     }
     *
     * @apiRError {Integer} code,  Código 1 no todo ha ido bien.
     * @apiRError {Varchar} message,  #MESSAGE_ERRORSS
     *
     * @apiRErrorExample Error-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 1,
     *       "message":'#MESSAGE_ERRORSS'       
     *     }
     */
    $app -> post('/'.$module.'/usersCreate', function(Request $request, Response $response, array $args) use ($connec,$common,$sd){

        $conn = end($connec);

        $sd->setDataParam($request->getParsedBody());

        /*if(contrasena() == confirmacion_contrasena()){
            $sd->saveAll($conn,$connec,"usuarios");
        }else{
            $sd->showMessage("Las contraseñas ingresadas no coinciden");
        }*/ 
                       
    });



    /**
     * @apiService {post} #module/updateUsuario, Actualizar datos de usuario.
     * @apiVersion 2.1.0
     * @apiName insertUsuario
     * @apiGroup #group
     *
     * @apiParam {integer} id, id del usuario al que se actualizaran los datos.
     * @apiParam {varchar(45)} correo, correo para uso de usuario en el sistema.
     * @apiParam {Varchar(20)} contraseña, contraseña del usuario.
     * @apiParam {Varchar(20)} confirmacion_contraseña,confirmacion de contraseña ingresada.
     * @apiParam {integer} id_perfil, id 1 para ingresar un cliente. id 2 para un freelance.
     *
     * @apiRSuccess {Integer} code,  Código 0 conforme todo ha ido bien.
     * @apiRSuccess {Varchar} message,  #MESSAGE_INSETSU
     *
     * @apiRSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 0,
     *       "code": '#MESSAGE_CODE',
     *       "message": '#MESSAGE_INSETSU'
     *     }
     *
     * @apiRError {Integer} code,  Código 1 no todo ha ido bien.
     * @apiRError {Varchar} message,  #MESSAGE_ERRORSS
     *
     * @apiRErrorExample Error-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 1,
     *       "message":'#MESSAGE_ERRORSS'       
     *     }
     */
    $app -> post('/'.$module.'/usersUpdate', function(Request $request, Response $response, array $args) use ($connec,$common,$sd){
        $conn = end($connec);

        $sd->setDataParam($request->getParsedBody());

        if(contrasena() == confirmacion_contrasena()){
            $sd->updateAll($conn,$connec,"usuarios");
        }else{
            $sd->showMessage("Las contraseñas ingresadas no coinciden");
        } 

    });


    /**
     * @apiService {post} #module/deleteUsuario, Eliminar a un usuario.
     * @apiVersion 2.1.0
     * @apiName insertUsuario
     * @apiGroup #group
     *
     * @apiParam {integer} id, id del usuario que se eliminara.
     *
     * @apiRSuccess {Integer} code,  Código 0 conforme todo ha ido bien.
     * @apiRSuccess {Varchar} message,  #MESSAGE_INSETSU
     *
     * @apiRSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 0,
     *       "code": '#MESSAGE_CODE',
     *       "message": '#MESSAGE_INSETSU'
     *     }
     *
     * @apiRError {Integer} error,  Código 1 no todo ha ido bien.
     * @apiRError {Varchar} message,  #MESSAGE_ERRORSS
     *
     * @apiRErrorExample Error-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 1,
     *       "message":'#MESSAGE_ERRORSS'       
     *     }
     */
    $app -> post('/'.$module.'/usersDelete', function(Request $request, Response $response, array $args) use ($connec,$common){
        $conn = end($connec);
        $id = $request->getParam('id');
        $sql = "DELETE FROM users WHERE id = $id";
        ManageDataService::sendData($conn,$sql,$connec);

    });    