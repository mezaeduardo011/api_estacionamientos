<?php
namespace Catatumbo\Core;
use Catatumbo\Core\Random;
use Catatumbo\Core\Constant;
class Common implements Constant
{

	/**
	 * Method encargado de hacer impreciones completa del registropasado
	 * @param Array $data, valor pasado que desea imprimir
	 * @return string resultado de los datos
	 */
	public static function pp($data){
		echo '<pre>';print_r($data);die();
	}

	public static function getFileExecute($dirExecute){
		$result=explode('/', $dirExecute);
		$ruta=end($result);
		return $ruta;
	}

	public static function utf8enc($array) {
		if (!is_array($array)) return;
		$helper = array();
		foreach ($array as $key => $value) $helper[utf8_encode($key)] = is_array($value) ? self::utf8enc($value) : utf8_encode($value);
		return $helper;
	}

	public static function utf8dec($array) {
		if (!is_array($array)) return;
		$helper = array();
		foreach ($array as $key => $value) $helper[utf8_decode($key)] = is_array($value) ? self::utf8enc($value) : utf8_decode($value);
		return $helper;
	}

	
	public static function now(){
		// Fecha y hora
		return $timestamp = date('Y-m-d H:i:s');
	}

	/**
	 * Method encargado de instanciar una clase random para generar un codigo aleatorio de string
	 * la posibilidad que se repita en las iteracion es de 0% en una prueba de 50,546 registros masivos  
	 * @param $num integer cantidad de resultado que quieres mostrar, ejemplo si mandas 1 te devolvera 2
	 * return string codido generado
	 */
	public static function randomString($num){
		$rand = new Random();
		$resul=$rand->alfaNumerico($num);
		return $resul;
	}

	public static function multiExplode($delimiters,$string,$nameMod,$apiRestSi) {
		$variable = explode($delimiters[0],$string);
		foreach ($variable as $key => $value) {
			$variable = explode($delimiters[1],$value);
			foreach ($variable as $key => $value) {
	    	//echo $value;
				if(strpos($value,'@apiService')){
					$repTag = array('#group','#module','*','#MESSAGE_NOTIFIC', '#MESSAGE_INSETSU', '#MESSAGE_ERRORSS', '#MESSAGE_INFORSS', '#MESSAGE_SIGNINS', '#MESSAGE_ACTIVAT', '#MESSAGE_ACTIVNO', '#MESSAGE_LIST_00','#MESSAGE_HTTP200','#MESSAGE_HTTP204','#MESSAGE_HTTP404','#MESSAGE_HTTP500');
					$repRea = array($nameMod,$apiRestSi.''.$nameMod,'',Constant::MESSAGE_NOTIFIC, Constant::MESSAGE_INSETSU, Constant::MESSAGE_ERRORSS, Constant::MESSAGE_INFORSS, Constant::MESSAGE_SIGNINS, Constant::MESSAGE_ACTIVAT, Constant::MESSAGE_ACTIVNO, Constant::MESSAGE_LIST_00, Constant::MESSAGE_HTTP200, Constant::MESSAGE_HTTP204, Constant::MESSAGE_HTTP404, Constant::MESSAGE_HTTP500);
					$a=str_replace($repTag,$repRea,$value);
					@$ary[]=$a;
				}
			}
		}
		return  @$ary;
	}

	public static function getTabsAnnotations($bloque){
		return $return = explode('@', $bloque);
	}

	public static function getTabsService($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'Service')){
				$p1=explode(',', str_replace('apiService','',$value));
				$p2=explode(' ', trim($p1[0]));
				$dataTabs['method'] = $p2[0];
				$dataTabs['metsin'] = str_replace(array('{','}'),array('',''),$p2[0]);
				$dataTabs['servic'] = $p2[1];
				$dataTabs['descri'] = $p1[1];
			}
		}
		return (object)$dataTabs;
	}

	public static function getTabsVersion($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'Version')){
				$p1=explode(' ', str_replace('apiVersion','',$value));
			}
		}
		$dataTabs = $p1[1];
		return $dataTabs;
	}

	public static function getTabsParameter($domain){
		$dataTabs = array();
		foreach ($domain as $key => $value) {
			if(stripos($value,'Param')){
				$p1=explode(',', str_replace('apiParam','',$value));
				$p2=explode(' ', trim($p1[0]));
				$param = $p2[1];
				$dataTabs[$param]=array('type'=>$p2[0], 'param'=>$param,'detalles'=>$p1[1]);
			}
		}
		return (object)$dataTabs;
		
	}

	public static function getTabsParameterSuccess($domain){
		$dataTabs = array();
		foreach ($domain as $key => $value) {
			if(stripos($value,'RSuccess') and !stripos($value, 'RSuccessExample')){
				$p1=explode(',', str_replace('apiRSuccess','',$value));
				$p2=explode(' ', trim($p1[0]));
				$param = $p2[1];
				$dataTabs[$param]=array('type'=>$p2[0], 'param'=>$param,'detalles'=>$p1[1]);
			}
		}
		return (object)$dataTabs;
		
	}

	public static function getTabsParameterSuccessResponse($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'SuccessExample')){
				$dataTabs['response']=str_replace(array('apiSuccessExample','Success-Response:'),array('',''),$value);
			}
		}
		return (object)$dataTabs;
	}

	public static function getTabsParameterError($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'RError') and !stripos($value, 'RErrorExample')){
				$p1=explode(',', str_replace('apiRError','',$value));
				$p2=explode(' ', trim($p1[0]));
				$param = $p2[1];
				$dataTabs[$param]=array('type'=>$p2[0], 'param'=>$param,'detalles'=>$p1[1]);
			}
		}
		return (object)$dataTabs;
	}

	public static function getTabsParameterErrorResponse($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'RErrorExample')){
				$dataTabs['response']=str_replace(array('apiRErrorExample','Error-Response:'),array('',''),$value);
			}
		}
		return (object)$dataTabs;
	}


	/**
	 *  Method encargado de gestionar con la api restfull
	 *  @dataBasic Encargada de tener la configuracion donde se encuentra el api rest + usuario y clave
	 *  @data Encargada de contener un arreglo de los datos que seran enviado al api rest
	 */
	public static function clientRestBase($dataBasic, $data){
		/*  @$dataBasic
		 	[apRest] => http://192.168.0.103:8084/rumberos/register
		    [tocken] => 123456
		    [secret] => 123456
		    [method] => POST
		*/

		    $dataJson = '';
		    // Validar si el ApiRest esta activo
		    $connec = self::validateRowsForm('URL',$dataBasic['apRest']);
		    if(!$connec){
		    	$dataJson['error'] = 1;
		    	$dataJson['message'] = 'Error, Al establecer conexion al APiRestFull del CUT.';
		    	return json_encode($dataJson); 
		    }
		    $handle = curl_init();

		    curl_setopt($handle, CURLOPT_URL, $dataBasic['apRest']);
		    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		    //curl_setopt($handle, CURLOPT_USERPWD, $dataBasic['tocken'].":".$dataBasic['secret']);
		    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

		    switch($dataBasic['method'])
		    {

		    	case 'GET':
		    	break;

		    	case 'POST':
		    	curl_setopt($handle, CURLOPT_POST, true);
		    	curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
		    	break;

		    	case 'PUT':
		    	curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
		    	curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
		    	break;

		    	case 'DELETE':
		    	curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
		    	break;
		    }

		    $response = curl_exec($handle);
		    curl_close($handle);

		    return $response;
		}

			/**
			 * Methos encardado de validar los diferentes campos del api
			 * @param String $type, Tipos de validador
			 * @param String $data, valor del campo
			 * @param String $name, Nombre del campo para cuando retorne los mensajes lo procese con essto
			 * @param Object $response, Response del las acciones del apis
			 */
		    public static function validateRows(string $type,  $data , $name = 'input' , $response)
		    {
		        switch($type)
		        {
		            case 'REQ': // Dato requerido
		                $retorno=($data == '')? FALSE: TRUE;
		                $msj = "El valor de entrada del campor $name, es requerido.";		                
		                break;

		            case 'NUM': // Solo numericos
		                $retorno=(filter_var($data, FILTER_VALIDATE_INT) === FALSE)? FALSE: TRUE;
		                $msj = "El valor de entrada del campor $name, debe contener solo numeros.";		                
		                break;

		            case 'LET': // Solo letras
		                $retorno=(filter_var($data, FILTER_VALIDATE_INT) === FALSE)? FALSE: TRUE;
		                $msj = "El valor de entrada del campor $name, debe contener solo letras.";		                
		                break;

		            case 'STR': // Solo String alfanumerico
		                $retorno=(filter_var($data, FILTER_SANITIZE_STRING) === FALSE)? FALSE: TRUE;
		                $msj = "El valor de entrada del campor $name, debe ser un string.";		                
		                break;

		            case 'EMA': // Solo correos electrinico
		                $retorno=(filter_var($data, FILTER_VALIDATE_EMAIL) === FALSE)? FALSE: TRUE;
		                $msj = "El valor de entrada del campor $name, debe ser un correo electronico.";		                
		                break;

		            case 'URL': // Solo direcciones de internet
		                $retorno=(filter_var($data, FILTER_VALIDATE_URL,FILTER_FLAG_QUERY_REQUIRED) === FALSE)? FALSE: TRUE;
		                $msj = "El valor de entrada del campor $name, debe ser solo direcciones de internet (URL).";		                
		                break;

		            case 'BOO': // Identificar si el registro es booleano
		                $retorno=(filter_var($data, FILTER_VALIDATE_BOOLEAN) === FALSE)? FALSE: TRUE;
		                $msj = "El valor de entrada del campor $name, debe ser un valor boolean.";		                
		                break;
		            case 'URLACT':
		                $retorno=(@get_headers($data))? TRUE : FALSE;
		                $msj = "El valor de entrada del campor $name, debe ser un enlace de internet que se encuentre activo.";		                
		                break;
		            case 'IP':
		                $retorno=(filter_var($data, FILTER_VALIDATE_IP));
		                $msj = "El valor de entrada del campor $name, debe ser una direccion ip.";		                
		                break;
		        }
		        if($retorno){
		        	 $data = array('code' => 400, 'message'=> $msj );
            		 return $response->withJson($data, 400);
		        }
		    }

		/**
		* Funcion encargada de validar si un enlace es valido pero 
		* buscando la funcion en otro servidor, cuando no esta activo
		* opciones de file_get_contents
		* @param $url string Enlace que deseas validar
		* @return $return Json metatags, title, validate
		*/
		public static function file_get_contents_curl($url){
			// Web donde ira a buscar la validacion
			$apiRest='http://dategeekgroup.com.ve/';
        	// Agregar el method POST porque es dato de registro
			$dataBasic = array('method' => 'POST');

			$dataPost = array(
				'typ'=>'validateUrl',
				'url'=>$url
				);
        // Fucionar las rutas para poder enviarla como config al apiRest
			$dataBasic = array_merge(array('apRest' => $apiRest.'validateUrl.php'), $dataBasic);

        // Consultar api rest encargado de gestionar con la capa de base de datos
			$return = self::clientRestBase($dataBasic, $dataPost);
			return $return;
		}

				/**
		* Funcion encargada de validar si un enlace es valido pero buscando la funcion local
		* @param $url string Enlace que deseas validar
		* @return $return Json metatags, title, validate
		*/
		public static function file_get_contents_local($url){
			// Procedimiento para validar si la url es valida y esta disponible
			$a=@fopen($url,"r");;
			if ($a){
				$validate = true;
				// Procedimiento para extraer los metatags de la url enviada
				$b=get_meta_tags($url);
				$responce['metatags'] = $b; 
				// Extraer el Titulo de la pagina
				$str = file_get_contents($url);
				if(strlen($str)>0){
					preg_match("@<title>(.*)</title>@",$str,$title);
					$responce['title'] = $title[1];
				}
			}else{
				$validate = false;
			}
			$responce['validate'] = $validate; 
			return $responce;
		}


	}
	?>