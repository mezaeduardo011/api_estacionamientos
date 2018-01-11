<?php
namespace Catatumbo\Complements\Database;
/**
 * Configuracion de las conexiones bb Hornero 4
 * @propiedad: Hornero 4
 * @utor: Gregorio Bolivar <elalconxvii@gmail.com>
 * @created: 10/01/2018
 * @version: 1.0
 */ 

trait ConfigDatabase
{

  public $motor;
  public $host;
  public $port;
  public $db;
  public $user;
  public $pass;
  public $encoding;
  function __construct()
  {
   $this->motor;
   $this->host;
   $this->port;
   $this->db;
   $this->user;
   $this->pass;
   $this->encoding;
 }

  /** Inicio  del method  de catatumbo  */
  public function catatumbo()
  {
   // Driver de Conexion con la de base de datos
   $this->motor = 'sql';
   // IP o HOST de comunicacion con el servidor de base de datos
    $this->host = 'localhost';
   // Puerto de comunicacion con el servidor de base de datos
   $this->port = '3305';
   // Nombre base de datos
   $this->db = 'LionsAsistencia';
   // Usuario de acceso a la base de datos
   $this->user = 'sa';
   // Clave de acceso a la base de datos
   $this->pass = 's3rv3r..*';
   // Codificacion de la base de datos
   $this->encoding = 'UTF-8';
   return $this;
  }
  /** Fin del caso del method de catatumbo */
}

?>