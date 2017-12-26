<?php
use Catatumbo\Core\Common;
use Catatumbo\Core\Constant;
use Catatumbo\Driverdb\Database;
use Catatumbo\Driverdb\ManageDataService;
use Slim\Http\Request;
use Slim\Http\Response;


try {
    $common = new Common();
    $config = parse_ini_file(Constant::DIR_CONFIG.'app.ini', true);

    $connec = new Database('catatumbo');
    $module=$common->getFileExecute(__DIR__);

    $c = new \Slim\Container();

    $app->add(function (Request $request, Response $response, $next) use ($c) {
        // First execute anything else
        $response = $next($request, $response);
        //print_r($response->getStatusCode()); die('kkj');
        // Check if the response should render a 404
        if (404 === $response->getStatusCode()) {
            // A 404 should be invoked
            $handler = $c['notFoundHandler'];
            //return $handler($request, $response);
            $data = array('code' => 404, 'message'=> 'Pagina no encontrada.' );
            return $response->withJson($data, 404);
        }

        // Any other request, pass on current response
        return $response->withStatus(200);
    });


    $app->get('/', function(Request $request, Response $response, array $args) use ($app,$config,$common){
        return $this->renderer->render($response,'viewHome.php', array('system'=>$config['default'],'common'=>$common));
        //return $this->renderer->render($response, 'index.phtml', $args);
    });


    $app->get('/documents', function(Request $request, Response $response, array $args) use ($app,$config,$common){
        $data=$_SESSION['data'];
        return $this->renderer->render($response,'viewDocuments.php', array('apiDoc' => $data,'system'=>$config['default'],'common'=>$common));
    });

    /*$app->get('/login', function(Request $request, Response $response, array $args) use ($app,$config,$common,$connec){
        $mensaje = '';
        return $this->renderer->render($response,'viewLogin.php', array('system'=>$config['default'],'common'=>$common,'mensaje'=>$mensaje));
    });

    $app->post('/proceforms', function(Request $request, Response $response, array $args) use ($app,$config,$common,$connec){
        $conn = end($connec);
        if($request->isPost())
        {
            $post = (object)$request->getParams();
            if(isset($post->user) && isset($post->passwort))
            {
                $app->redirect('login');
            }else{
                // Capturando los datos para el registro de los usuarios
                $user=$request->getParam('user');
                $password=$request->getParam('password');


            // Query para extraer todas las metas de la pagina web
                $sql = "SELECT usuario, clave FROM usuarios WHERE usuario='".$user."' AND clave='".$password."' ";
                $query = $conn->prepare($sql);
                $result=$query->execute();
                /** Verifico si hay mas de un registro
                $rows = count($query->fetchAll(PDO::FETCH_ASSOC));
                $connec->close_con();
                if($rows==1){
                    return $response->withStatus(302)->withHeader('Location', '/admin');
                }else{
                    $mensaje='Usuario y clave incorrecto intente nuevemente.';
                    return $this->renderer->render($response,'viewLogin.php', array('system'=>$config['default'],'common'=>$common,'mensaje'=>$mensaje));

                }

            }
        }
    });


    $app->get('/admin', function (Request $request, Response $response, array $args) use ($app,$config,$common,$connec) {
        return $this->renderer->render($response, 'viewBase.php', array('system'=>$config['default'],'common'=>$common));
    });

    $app->get('/logout', function () {
        //Remove auth cookie and redirect to login page
    });*/

} catch(\PDOException $e) {
    die(print_r(json_encode(array('code' => 3, 'message'=> "Failed to connect to database ".$e->getMessage()))));
}
