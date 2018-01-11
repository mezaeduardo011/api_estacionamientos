<?php
use Slim\Http\Request;
use Slim\Http\Response;
use Catatumbo\Core\Common;
use Catatumbo\Core\Constant;
use Catatumbo\Complements\Database\Base;
//use Catatumbo\Driverdb\ManageDataService;

// Conexion contra base de datos
/*try {*/
$connec = new Base('catatumbo');

$connec->tabla = 'incidencias';
$connec->campoid = array('id');
$connec->campos = array('legajo','fecha','medicion','observacion','idUsuario','alcohol','anfetamina','cocaina','marihuana','metanfetamina','opiaceos','fenciclidina');


// Se encarga de extraer el nombre de la carpeta donde se instanciará
$module=$common->getFileExecute(__DIR__);

/**
* @apiService {post} #module/altaIncidencias, Permite registrar una incidencia en el sistema de JRP+
* @apiVersion 0.1.0
* @apiName altaIncidencias
* @apiGroup #group
*
* @apiParam  Varchar(15) {legajo}, Requerido NULL codigo de usuario.
* @apiParam  datetime2(7) {fecha}, Requerido NOT NULL tipo de usuario.
* @apiParam  Varchar(10) {medicion}, Requerido NOT NULL Primer nombre de usuario.
* @apiParam  Varchar(500) {observacion}, Requerido NOT NULL Primer nombre de usuario.
* @apiParam  int {idUsuario}, Requerido NULL  Segundo nombre de usuario.
*
* @apiRSuccess {Integer} code,  Código 200 conforme todo ha ido bien.
* @apiRSuccess {Varchar} message, Si existen registro con la condición no trae mensaje, de lo contrario debe mostrar #MESSAGE_LIST_00  
* @apiRSuccess {Json} data, debe extraer un json con el usuario
*
* @apiRSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*     {
*       "code": 0,
*       "message": '' OR '#MESSAGE_CREATE',
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
    $app->post('/'.$module.'/altaIncidencias', function(Request $request, Response $response, array $args) use ($connec){

        $campos = $request->getQueryParams();

        $connec->fijarValores($campos);
        $result = $connec->guardar();

        if(!is_null($result)){
            $data = array('code' => 200, 'message'=>Constant::MESSAGE_ERRORSS);
            return $response->withJson($data, 200);
        }else{
            $data = array('code' => 204, 'message'=> Constant::MESSAGE_CREATE,'id'=>$connec->lastId() );
            return $response->withJson($data, 204);
        }

    });


/**
* @apiService {get} #module/getIncidencias/{id}, Obtiene una incidencia mediante el id.
* @apiVersion 0.1.0
* @apiName getIncidencias
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
    $app -> get('/'.$module.'/getIncidencias/{id}', function(Request $request, Response $response, array $args) use ($connec){
        $route = $request->getAttribute('route');
        $id = $route->getArgument('id');

        $sql = "SELECT * FROM ".$connec->tabla." WHERE id=".$id.';';
        $result = $connec->executeQuery($sql);

        if(!is_null($result)){
            $data = array('code' => 200, 'data'=>$result);
            return $response->withJson($data, 200);
        }else{
            $data = array('code' => 204, 'message'=> Constant::MESSAGE_LIST_00 );
            return $response->withJson($data, 204);
        }
    });


/**
 * @apiService {post} #module/bajaIncidencias, Procesar una baja de incidencia
 * @apiVersion 0.1.0
 * @apiName bajaIncidencias
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
$app -> post('/'.$module.'/bajaIncidencias', function(Request $request, Response $response, array $args) use ($connec){
    $id = $request->getParam('id');

    $connec->fijarValor('id',$id);
    $result = $connec->borrar();

    if(is_null($result)){
        $data = array('code' => 200, 'message'=> Constant::MESSAGE_DELETE);
        return $response->withJson($data, 200);
    }else{
        $data = array('code' => 204, 'message'=> Constant::MESSAGE_LIST_00 );
        return $response->withJson($data, 204);
    }
});



