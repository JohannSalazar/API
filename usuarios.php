<?php
require_once 'conexionBD.php';

class usuarios {
    const ESTADO_CLAVE_NO_AUTORIZADA = 410;
    const ESTADO_AUSENCIA_CLAVE_API = 411;

    public static function autorizar(){
        $cabeceras = apache_request_headers();

        if (isset($cabeceras["Authorization"])) {
            $claveApi = $cabeceras["Authorization"];

            if (usuarios::validarClaveApi($claveApi)) {
                return usuarios::obtenerIdUsuario($claveApi);
            } else {
                throw new ExcepcionApi(
                    self::ESTADO_CLAVE_NO_AUTORIZADA, "Clave de API no autorizada", 401);
            }
        } else {
            throw new ExcepcionApi(
                self::ESTADO_AUSENCIA_CLAVE_API,
                utf8_encode("Se requiere Clave del API para autenticación"));
        }
    }

    private static function validarClaveApi($claveApi) {
        /*
         $comando = "SELECT COUNT(" . self::ID_USUARIO . ")" .
            " FROM " . self::NOMBRE_TABLA .
            " WHERE " . self::CLAVE_API . "=?";

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
        $sentencia->bindParam(1, $claveApi);
        $sentencia->execute();
        return $sentencia->fetchColumn(0) > 0;
        */

        if ($claveApi == "ASDFGH0987654321") {
            return true;
        } else {
            return false;
        }
    }

    private static function obtenerIdUsuario($claveApi){
       /*
        $comando = "SELECT " . self::ID_USUARIO .
            " FROM " . self::NOMBRE_TABLA .
            " WHERE " . self::CLAVE_API . "=?";

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
        $sentencia->bindParam(1, $claveApi);

        if ($sentencia->execute()) {
            $resultado = $sentencia->fetch();
            return $resultado['idUsuario'];
        } else
            return null;
       */

        if ($claveApi == "ASDFGH0987654321") {
            return 100;
        } else {
            return null;
        }
    }

    // Método para crear un nuevo usuario
    public static function crearUsuario($nombre, $email, $claveApi) {
        try {
            $conexion = ConexionBD::obtenerInstancia()->obtenerBD();
            
            // Preparar consulta SQL para insertar un nuevo usuario
            $consulta = $conexion->prepare("INSERT INTO usuarios (nombre, email, claveApi) VALUES (?, ?, ?)");
            
            // Ejecutar la consulta con los parámetros proporcionados
            $consulta->execute([$nombre, $email, $claveApi]);
            
            // Verificar si se insertó correctamente
            if ($consulta->rowCount() > 0) {
                return "Usuario creado exitosamente.";
            } else {
                return "Error al crear usuario.";
            }
        } catch (PDOException $e) {
            // Capturar y manejar excepciones
            return "Error: " . $e->getMessage();
        }
    }

    // Método para obtener un usuario por su ID
    public static function obtenerUsuarioPorId($idUsuario) {
        try {
            $conexion = ConexionBD::obtenerInstancia()->obtenerBD();
            
            // Preparar consulta SQL para obtener un usuario por su ID
            $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE idUsuario = ?");
            
            // Ejecutar la consulta con el ID proporcionado
            $consulta->execute([$idUsuario]);
            
            // Obtener el resultado de la consulta
            $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
            
            // Verificar si se encontró el usuario
            if ($usuario) {
                return $usuario;
            } else {
                return "Usuario no encontrado.";
            }
        } catch (PDOException $e) {
            // Capturar y manejar excepciones
            return "Error: " . $e->getMessage();
        }
    }

    // Método para obtener todos los usuarios
    public static function obtenerTodosLosUsuarios() {
        try {
            $conexion = ConexionBD::obtenerInstancia()->obtenerBD();
            
            // Preparar consulta SQL para obtener todos los usuarios
            $consulta = $conexion->prepare("SELECT * FROM usuarios");
            
            // Ejecutar la consulta
            $consulta->execute();
            
            // Obtener todos los usuarios
            $usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);
            
            // Verificar si se encontraron usuarios
            if ($usuarios) {
                return $usuarios;
            } else {
                return "No se encontraron usuarios.";
            }
        } catch (PDOException $e) {
            // Capturar y manejar excepciones
            return "Error: " . $e->getMessage();
        }
    }

    // Método para actualizar los datos de un usuario
   // Método para actualizar el nombre de un usuario
public static function actualizarUsuario($idUsuario, $nombre) {
    try {
        $conexion = ConexionBD::obtenerInstancia()->obtenerBD();
        
        // Preparar consulta SQL para actualizar el nombre de un usuario
        $consulta = $conexion->prepare("UPDATE usuarios SET nombre = ? WHERE idUsuario = ?");
        
        // Ejecutar la consulta con el nuevo nombre y el ID del usuario
        $consulta->execute([$nombre, $idUsuario]);
        
        // Verificar si se actualizó correctamente
        if ($consulta->rowCount() > 0) {
            return "Nombre de usuario actualizado exitosamente.";
        } else {
            return "Error al actualizar nombre de usuario.";
        }
    } catch (PDOException $e) {
        // Capturar y manejar excepciones
        return "Error: " . $e->getMessage();
    }
}


    // Método para eliminar un usuario por su ID
    public static function eliminarUsuario($idUsuario) {
        try {
            $conexion = ConexionBD::obtenerInstancia()->obtenerBD();
            
            // Preparar consulta SQL para eliminar un usuario por su ID
            $consulta = $conexion->prepare("DELETE FROM usuarios WHERE idUsuario = ?");
            
            // Ejecutar la consulta con el ID del usuario
            $consulta->execute([$idUsuario]);
            
            // Verificar si se eliminó correctamente
            if ($consulta->rowCount() > 0) {
                return "Usuario eliminado exitosamente.";
            } else {
                return "Error al eliminar usuario.";
            }
        } catch (PDOException $e) {
            // Capturar y manejar excepciones
            return "Error: " . $e->getMessage();
        }
    }
}
?>

