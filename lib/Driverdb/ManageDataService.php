<?php
namespace Catatumbo\Driverdb;
use Catatumbo\Core\Constant;

class ManageDataService {

	static private $manageDataBase;

	public function __construct() {
		 $config = new ConfigDatabase();
		 //echo $config->catatumbo()->driv;
	}	

    public static function getData($conn,$sql,$connec,$response) {
    	try{
            $query = $conn->prepare($sql);
            $result=$query->execute();
            $data = $query->fetchAll(\PDO::FETCH_ASSOC);
            $connec->close_con();
            /** Verifico si hay mas de un registro */
            $rows = count($data); 

            /** Verifico si el registro fue procesado exitosamente */
            if($rows > 0){
                $data = array('code' => 200, 'message'=>'', 'data'=>$data);
                return $response->withJson($data, 200);  
            }else{
                $data = array('code' => 204, 'message'=> Constant::MESSAGE_LIST_00 );
                return $response->withJson($data, 204);
            }
        }catch(\PDOException $e){
                 $data = array('code' => 500, 'message'=>'', 'data'=>$data);
                return $response->withJson($data, 500);
        }
       
    }
    
    public static function getDataCustom($conn,$sql,$connec,$response) {
    	try{
            $query = $conn->prepare($sql);
            $result=$query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
            $connec->close_con();
            /** Verifico si hay mas de un registro */
            $rows = count($data); 

            /** Verifico si el registro fue procesado exitosamente */
            if($rows > 0){
                $data = array('code' => 200, 'message'=>'', 'data'=>$data);
                return $response->withJson($data, 200);  
            }else{
                $data = array('code' => 204, 'message'=> Constant::MESSAGE_LIST_00 );
                return $response->withJson($data, 204);
            }
        }catch(\PDOException $e){
                 $data = array('code' => 500, 'message'=>'', 'data'=>$data);
                return $response->withJson($data, 500);
        }
    }    

    public static function getFieldsTable($conn,$sql,$connec) {
        $query = $conn->prepare($sql);
        $result=$query->execute();
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        $connec->close_con();
        return $data;
    }    

    public static function sendData($conn,$sql,$connec,$response) {
        try{
            $query = $conn->prepare($sql);
            $result=$query->execute();
            $connec->close_con();
            if($result){
                $data = array('code' => 200, 'message'=>'', 'data'=>$data);
                return $response->withJson($data, 200);  
            }else{
                $data = array('code' => 204, 'message'=> Constant::MESSAGE_LIST_00 );
                return $response->withJson($data, 204);
            }
        }catch(\PDOException $e){
                 $data = array('code' => 500, 'message'=>'', 'data'=>$data);
                return $response->withJson($data, 500);
        }
    }

	public static function setDataParam($params){
        foreach($params as $key => $param){
		    eval("function $key(){
		        return '$param';
		    }");
        }


	}

	public static function saveAll($conn,$connec,$tabla){
		/*se obtienen los nombres de todas las columnas menos la columna id
		* de la tabla que se envia por parametro
		*para esto se usa la funcion getFieldsTable() que devolvera dichas columnas*/		
		$sql = "SHOW COLUMNS FROM ".$tabla." WHERE Field != 'id'";  
        $campos = self::getFieldsTable($conn,$sql,$connec);
        //se creara el string de los campos a insertar ($camposInsert)  y los valores ($camposValues)
        $camposInsert = "(";
        $camposValues = "(";
        /*se recorrera un for del tamaño de los campos guardados en la variable $campos
         para construir dicho string*/
        for ($i=0; $i <count($campos); $i++) {
        	/*se crea la variable $tipoCampo para verificar si el tipo de campo
        	de la tabla es int*/
        	$tipoCampo = substr($campos[$i]['Type'], 0,3);
			$camposInsert .= $campos[$i]['Field'].",";
			//si el campo es int no se colocara comillas al valor
        	if($tipoCampo=="int"){
        		$camposValues .= $campos[$i]['Field']().",";
        	}else{
        	//si el $tipoCampo no es int se concatenan comillas para los valores
        		$camposValues .= "'".$campos[$i]['Field']()."'".",";
        	}         	
        }
        /* una vez se haya recorrido todo el for
        *se quitara la coma al final de cada string creado en $camposInsert y $camposValues
        *quedando asi de la siguiente manera
        * $campos = (campo1,campo2,campo3)
        * $values = (valor1,valor2,valor3);*/        
        $campos = substr($camposInsert,0, -1).")";
        $values = substr($camposValues,0, -1).");";
        /*se concatena los 2 string creados $campos y $values para crear el sql
        * junto con la tabla que se paso por parametro
        * quedando de la siguiente manera
        * "INSERT INTO tabla (campo1,campo2,campo3) VALUES (valor1,valor2,valor3);"*/        
        $sqlInsert = "INSERT INTO ".$tabla." ".$campos." VALUES ".$values;
        //se envia el sql completo a la funcion sendData.
        self::sendData($conn,$sqlInsert,$connec);
	}


	public static function updateAll($conn,$connec,$tabla){
		/*se obtienen los nombres de todas las columnas menos la columna id
		* de la tabla que se envia por parametro
		*para esto se usa la funcion getFieldsTable() que devolvera dichas columnas*/		
		$sql = "SHOW COLUMNS FROM ".$tabla." WHERE Field != 'id'";  
        $campos = self::getFieldsTable($conn,$sql,$connec);
        //se creara el string de los campos a actualizar ($camposUpdate)
        $camposUpdate = "";
        /*se recorrera un for del tamaño de los campos guardados en la variable $campos
         para construir dicho string*/
        for ($i=0; $i <count($campos); $i++) {
        	/*se crea la variable $tipoCampo para verificar si el tipo de campo
        	de la tabla es int*/
        	$tipoCampo = substr($campos[$i]['Type'], 0,3);
			//si el campo es int no se colocara comillas al valor
        	if($tipoCampo=="int"){
        		$camposUpdate .= $campos[$i]['Field']." = ".$campos[$i]['Field']().",";
        	}else{
        	//si el $tipoCampo no es int se concatenan comillas para los valores
        		$camposUpdate .= $campos[$i]['Field']." = "."'".$campos[$i]['Field']()."'".",";
        	}         	
        }
        /* una vez se haya recorrido todo el for
        *se quitara la coma al final de cada string creado en $camposUpdate 
        *quedando asi de la siguiente manera
        * $camposUpdate = "campo1 = 'valor1',campo2 = 'valor1',campo3 = valor3"*/
        $campos = substr($camposUpdate,0, -1);        
        /*se concatena los string creados $campos junto con la tabla para crear el sql
        * quedando de la siguiente manera
        * "UPDATE usuarios SET campo1 = 'valor1',campo2 = 'valor1',campo3 = valor3 WHERE id = 1"*/        
        $sqlUpdate = "UPDATE ".$tabla." SET ".$campos." WHERE id = 1";
        //se envia el sql completo a la funcion sendData.
        self::sendData($conn,$sqlUpdate,$connec);
	}

    public static function showMessage($message) {
        print_r(json_encode(array('error' => 1, 'message'=> $message )));
    }	
}

?> 