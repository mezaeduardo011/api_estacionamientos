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

     * @apiService {post} #module/agencyAffiliation, Registrar un usuario.
     * @apiVersion 2.1.0
     * @apiName agencyAffiliation
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
     *     HTTP/1.1 400 OK
     *     {
     *       "code": 1,
     *       "message":'#MESSAGE_ERRORSS'       
     *     }
     */

    $app -> post('/'.$module.'/usersCreate', function(Request $request, Response $response, array $args) use ($connec,$common,$sd){
        $conn = end($connec);
        $sd->setDataParam($request->getParsedBody());
    });



