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

$connec->tabla = 'tickets';
$connec->campoid = array('id');
$connec->campos = array('fecha_entrada');


// Se encarga de extraer el nombre de la carpeta donde se instanciará
$module=$common->getFileExecute(__DIR__);

/**
 * @apiService {post} #module/registroTicket, API para el registro de tickets de estacionamiento.
 * @apiVersion 0.1.0
 * @apiName altaIncidencias
 * @apiGroup #group
 *
 * @apiParam Integer id,  Código 200 conforme todo ha ido bien.
 * @apiParam Integer id_sucursal, Si existen registro con la condición no trae mensaje, de lo contrario debe mostrar #MESSAGE_LIST_00
 * @apiParam Varchar(24) fecha_entrada, debe extraer un json con el usuario
 * @apiParam Varchar(50) token, debe extraer un json con el usuario
 *
 * @apiRSuccess {JSON} message, Si existen registro con la condición no trae mensaje, de lo contrario debe mostrar #MESSAGE_LIST_00
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
$app->post('/'.$module.'/registroTicket', function(Request $request, Response $response, array $args) use ($connec){

    $campos = $_POST;

    $sql = "INSERT INTO tickets(id,id_sucursal,fecha_entrada,token) 
            VALUES('".$campos['id']."','".$campos['id_sucursal']."','". $campos['fecha_entrada'] ."','".$campos['token']."')";

    $result = $connec->executeQuery($sql);

    if(is_null($result)){
        $data = array('code' => 200, 'message'=>Constant::MESSAGE_ERRORSS);
        return $response->withJson($data, 200);
    }else{
        $data = array('code' => 204, 'message'=> Constant::MESSAGE_CREATE,'id'=>$connec->lastId() );
        return $response->withJson($data, 204);
    }

});



/**
 * @apiService {post} #module/obtenerTickets, API para obtener todos los tickets registrados.
 * @apiVersion 0.1.0
 * @apiName altaIncidencias
 * @apiGroup #group
 *
 * @apiRSuccess {JSON} message, Si existen registro con la condición no trae mensaje, de lo contrario debe mostrar #MESSAGE_LIST_00
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

$app->get('/'.$module.'/obtenerTickets', function(Request $request, Response $response, array $args) use ($connec){

    $route = $request->getAttribute('route');
    $conn = end($connec);

    $sql = "SELECT * FROM ".$connec->tabla.";";
    $result = $connec->executeQuery($sql);

    var_dump($result);

});

