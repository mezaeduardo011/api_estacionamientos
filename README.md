# Catatumbo 
Catatumbo es una un componente completo que te permite desarrollar tus Apis-Rest de una forma sensilla y ordenada,
ademas siguiendo las estructura de notaciones del componente podrás optener una documentación de tus servicios de una forma bien detallada y excelente para la vista del usuario.

El objetivo de que se pueda integrar en cualquier proyecto, la capa de datos original esta basada en PDO, pero esta version esta conectada a datos desde el objeto comun modificado del desarrollo de hornero, esta con los estandares:

	
	PSR-4  Estándar de carga automática
	PSR-7  Interfaz de mensajes HTTP.


Es importante mensionar que se usa el microframwrork llamado Slim Framework en su version 3.5


### Dirección del Repositorio bitbucket.org 

* Versión original
* [_gbolivar](https://github.com/CaribesTIC/catatumbo.git)

* Versión modificada para JPH
* [_gbolivar](https://gitlab.com/jph_lions/apiJRH.git)

### Estructura de Directorio del especializada para crear tus Api Rest-Full 


### Contiene las lib necesarias para su funcionamiento ejemplos: Slim

```[terminal]
\config
\lib
\log
\modulos
\public
\src
\template
```



## Estructura de Directorio de la Carpeta Configuracion ###

### Archivo configuraracion de apache api.Catatumbo
* /config/
*	app.ini
*	database.ini
*	system.ini




### Archivo de Indicaciones del Proyecto ###
/catatumbo/README.md

 [_author](http://www.gregoriobolivar.com.ve)

### INSTALL

Proceso de instalación de Api-Rest
Es Obligatorio tener composer instalado en el servidor
###### 1 - git clone https://gitlab.com/jph_lions/apiJRH.git
###### 2 - cd  apiJRH
###### 3 - composer install
###### 4 - Es necesario que crees un hostvirtual para el funcionamiento del apis debe apuntar a la carpeta public del proyecto
###### Autenticación Basic user: admin, clave:admin

