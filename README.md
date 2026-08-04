Importar desde phpmyadmin la base de datos "database/scheme.sql" ya que se requiere para que la App funcione.

La hoja de estilos es la usada en los últimos 2 cursos.

El botón "Ver reportes" llama a reportes.php que a su vez llama al cliente(cURL) genérico para la consulta a la API de JSONPlaceholder. Era mas fácil usar fetch desde JS.


Para el "Ver Reportes" podría haber generado un endpoint que consultara la tabla "escaneos" y armar reportes "reales" pero no hubiera podido hacer uso de una API externa. 
