<?php

require_once "ConexionBD.php";
require_once "ExceptionApi.php";

class botones
{
    // Datos de la tabla "usuario"
    const NOMBRE_TABLA = "boton";
    const ID_BOTON = "id";
    const MATERIAL = "material";
    const COLOR = "color";
    const OJALES = "ojales";
    const ESTADO_CREACION_EXITOSA = "Creación con éxito";
    const ESTADO_CREACION_FALLIDA  = "Creación fallida";
    const ESTADO_ERROR_BD = -1;

    public static function get($peticion)
    {
        echo "Ejecutaste el método GET de la clase botones: " . $peticion[0];
    }  

    public static function post($peticion)
    {
        // Procesar post
        //this->crear($obj);

        if ($peticion[0] == 'crear') {
            $cuerpo = file_get_contents('php://input');
            $datosBoton = json_decode($cuerpo);

            return self::crear($datosBoton);
        } else {
            throw new ExcepcionApi(self::ESTADO_URL_INCORRECTA, "Url mal formada", 400);
        }
    }  


    //private 
    public function crear($datosBoton)
    {
        $material = $datosBoton->material;
        $color = $datosBoton->color;
        $ojales = $datosBoton->ojales;

        try {
            $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

            // Sentencia INSERT
            $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .
                self::MATERIAL . "," .
                self::COLOR . "," .
                self::OJALES . ")" .
                " VALUES(?,?,?)";

            $sentencia = $pdo->prepare($comando);

            $sentencia->bindParam(1, $material);
            $sentencia->bindParam(2, $color);
            $sentencia->bindParam(3, $ojales);
            
            $resultado = $sentencia->execute();

            if ($resultado) {
                return self::ESTADO_CREACION_EXITOSA;
            } else {
                return self::ESTADO_CREACION_FALLIDA;
            }
        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }

    } 
   
}







