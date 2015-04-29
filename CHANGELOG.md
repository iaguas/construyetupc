#	ConstruyeTuPC CHANGELOG

####	+ | Añadido
####	- | Eliminado
####	* | Modificado
####	! | Bug encontrado

29/04/2015
+ Landing Page terminada

20/04/2015
+ Añadida suscripción por AngularJS para la Landing Page
! AngularJS no muestra correctamente las notificaciones, dado que no captura el mensaje AJAX que se envía con la última línea del fichero php

11/04/2015
+ Añadidas páginas de especificación de modelo de componente con la comparativa de precios

10/04/2015
* Adaptadas las rutas para URL amigables

05/04/2015
+ Añadidos los casos necesarios en el controlador para poder añadir todas las partes a la lista de componentes.

31/03/2015
* Arreglado método de insertar emails de la Landing Page.
+ Añadidos métodos para eliminar documentos de una colección o vaciarla.
+ Añadido método para insertar documentos en Mongo utilizando un fichero JSON.
+ Añadidos diseños base para las páginas de listado de componentes elegidos y selección de modelo de componente
+ Funcionalidad para añadir y quitar un componente de la lista de seleccionados (únicamente para CPU)

29/03/2015
* Mejorada visualmente la Landing Page.
* Limpieza de archivos no usados y código.
+ Añadida jerarquía de clases para el acceso a base de datos. Modificado model.php para que haga uso de estas clases.

12/03/2015
+ Añadida versión completa de Landing Page
+ Añadido el código para atender la URL: [http://www.construyetupc.es?action=partlist](http://www.construyetupc.es?action=partlist), el nombre del archivo que se
ejecuta es "partlist.php".

09/03/2015
+ Añadido el patrón MVC con una Landing Page y la Main Page.





