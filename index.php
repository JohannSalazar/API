<?php
	require_once "botones.php";
    require_once "VistaJson.php";
    require_once "VistaXML.php";

    // Constantes de estado
    const ESTADO_URL_INCORRECTA = 2;
    const ESTADO_EXISTENCIA_RECURSO = 3;
    const ESTADO_METODO_NO_PERMITIDO = 4;

    $vista = new VistaJson();

    set_exception_handler(function ($exception) use ($vista) {
        $cuerpo = array(
            "estado" => $exception->estado,
            "mensaje" => $exception->getMessage()
        );
        if ($exception->getCode()) {
            $vista->estado = $exception->getCode();
        } else {
            $vista->estado = 500;
        }

        $vista->imprimir($cuerpo);
    }
    );


    // Extraer segmento de la url
    if (isset($_GET['PATH_INFO']))
        $peticion = explode('/', $_GET['PATH_INFO']);
    else
        throw new ExcepcionApi(ESTADO_URL_INCORRECTA, utf8_encode("No se reconoce la petición"));

/*
    PATH_INFO = "/botones/agregar/1";

    $peticion = ["botones", "agregar", "1"]; 
    $recurso = "botones";
    $peticion = ["agregar", "1"]; 
    
*/

    // Obtener recurso
    $recurso = array_shift($peticion);
    $recursos_existentes = array('contactos', 'usuarios', 'botones');

    // Comprobar si existe el recurso
    if (!in_array($recurso, $recursos_existentes)) {
        throw new ExcepcionApi(ESTADO_EXISTENCIA_RECURSO,
            "No se reconoce el recurso al que intentas acceder: " . $recurso );
    }

    $metodo = strtolower($_SERVER['REQUEST_METHOD']);

    // Filtrar método
    switch ($metodo) {
        case 'get':
        case 'post':
        case 'put':
        case 'delete':
            //echo "recurso = " . $recurso . " método = " . $metodo;
            if (method_exists($recurso, $metodo)) {
                
                $respuesta = call_user_func(array($recurso, $metodo), $peticion);
                $vista->imprimir($respuesta);
                 
            }
          break; 
        default:
            // Método no aceptado
            $vista->estado = 405;
            $cuerpo = [
                "estado" => ESTADO_METODO_NO_PERMITIDO,
                "mensaje" => utf8_encode("Método no permitido: " . $metodo)
            ];
        
        $vista->imprimir($cuerpo);

}

exit;

//Este código siguiente ya no se ejecuta, era para probar la funcionalidad de la clase botones




	$jsonBoton = '{
					"material": "lino",
					"color": "blanco",
					"ojales": "6"
				}';

	$objBoton = json_decode($jsonBoton);
	$boton = new Botones();
	$respuesta = $boton->crear($objBoton);

/*
{
    "estado": 400,
    "datos": "Creación con éxito"
}
*/

    //Leer queryparam formato para definir el formato de la respuesta http
    $formato = "";
    if (isset($_GET["formato"])){
        $formato = $_GET["formato"];
    }

     if ($formato =="" || $formato == "JSON"){
        $respuestaJSon = new VistaJson();
        //$arreglo = ["estado"=>$respuestaJSon->estado,
        //            "datos"=>$respuesta];
        $objeto = new stdClass();
        $objeto->estado = $respuestaJSon->estado;
        $objeto->datos = $respuesta;
        $respuestaJSon->imprimir($objeto);
        //echo "Fin de la prueba";
     }
    else {//XML
             $respuestaXml = new VistaXML();
             /*
                 $arreglo = ["estado"=>$respuestaXml->estado,
                         "datos"=>$respuesta];    */
             $objeto = new stdClass();
              $objeto->estado = $respuestaXml->estado;
              $objeto->datos = $respuesta;

              $respuestaXml->imprimir($objeto);
         }

