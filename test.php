<?php
// Incluir la clase de conexión
require_once 'ConexionBD.php';

// Obtener una instancia de la clase de conexión
$conexionBD = ConexionBD::obtenerInstancia();

// Intentar ejecutar una consulta simple para verificar la conexión
try {
    // Preparar una consulta simple (por ejemplo, seleccionar la versión de MySQL)
    $stmt = $conexionBD->obtenerBD()->query('SELECT VERSION()');

    // Si la consulta se ejecuta correctamente, significa que hay conexión
    echo "¡Conexión exitosa a la base de datos!";
} catch (PDOException $e) {
    // Si hay un error al ejecutar la consulta, mostrar un mensaje de error
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}
?>
