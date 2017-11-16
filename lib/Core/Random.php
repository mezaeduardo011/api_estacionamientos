<?php
namespace Catatumbo\Core;
  /**
  * @propiedad:Catatumbo
  * @Autor: Gregorio Bolivar
  * @email: elalconxvii@gmail.com
  * @Fecha de Creacion: 21/02/2012
  * @Auditado por: Gregorio J Bolívar B
  * @Modified Date: 10/04/2016
  * @Descripción: Codigo encargado de generar un string aleatorio
  * @package: Random.php
  * @version: 5.0
  */
  class Random {

    /**
     * string de valores posibles que seran usados para generar un alfanumerico aleatorio
     * @var string str
     * @access private
     */
    private $alfa = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890;-:<>|!#$%{[()]}?¡¿*';

    /**
     * string de valores posibles que seran usados para generar letras aleatorio
     * @var string str
     * @access private
     */
    private $text = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * string de valores posibles que seran usados para generar un numeros aleatorio
     * @var string str
     * @access private
     */
    private $nume = '1234567890';

    /**
     * Contenedor posibles que seran usados cuando se genere el numero
     * @var string str
     * @access private
     */
    private $cont;

    public function __construct() {
      $this->alfa;
      $this->text;
      $this->nume;
    }

    public function alfaNumerico($cant) {
     $this->cont = '';
     for ($i = 0; $i < $cant; $i++) {
      $this->cont .= substr($this->alfa, rand(0, 80), 1);
    }
    
    switch (rand(0,4)) {
      case '0':
      $min=36;$max=38;  // Simbolos 1
      break;
      case '1':
      $min=40;$max=43;  // Simbolos 2
      break;
      case '2':
      $min=48;$max=57;  // Numeros 0 al 9
      break;
      case '3':
      $min=65;$max=90;  // Letras Mayusculas A al Z
      break;      
      case '4':
      $min=97;$max=122; // Letras Minuscula a al z
      break;
      case '5':
      $min=123;$max=126;  // Simbolos 3
      break;
    }

    // str_shuffle — Reordena aleatoriamente una cadena
    $this->result = str_shuffle($this->cont).chr(rand($min,$max));
    return $this->result;
  }

  public function __destruct() {
    unset($this->result);
  }

}
?>
