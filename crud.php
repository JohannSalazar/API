<?php
require_once 'conexionBD.php';
require_once 'ExceptionApi.php';
require_once 'usuarios.php';

function manejarSolicitud() {
    $metodo = $_SERVER['REQUEST_METHOD'];

    switch ($metodo) {
        case 'POST':
            agregarUsuario();
            break;
        case 'GET':
            obtenerUsuarios();
            break;
        case 'PUT':
            actualizarUsuario();
            break;
        case 'DELETE':
            eliminarUsuario();
            break;
        default:
            http_response_code(405); // Método no permitido
            echo json_encode(array('mensaje' => 'Método no permitido'));
            break;
    }
}

function agregarUsuario() {
    try {
        usuarios::autorizar();
    } catch (ExcepcionApi $e) {
        http_response_code($e->getCode());
        echo json_encode(array('mensaje' => $e->getMessage()));
        return;
    }

    $datosUsuario = json_decode(file_get_contents('php://input'), true);

    if (empty($datosUsuario['nombre']) || empty($datosUsuario['email']) || empty($datosUsuario['claveApi'])) {
        http_response_code(400); // Solicitud incorrecta
        echo json_encode(array('mensaje' => 'Faltan datos requeridos'));
        return;
    }

    $resultado = usuarios::crearUsuario($datosUsuario['nombre'], $datosUsuario['email'], $datosUsuario['claveApi']);

    http_response_code(201); // Creado exitosamente
    echo json_encode(array('mensaje' => $resultado));
}

function obtenerUsuarios() {
    try {
        usuarios::autorizar();
    } catch (ExcepcionApi $e) {
        http_response_code($e->getCode());
        echo json_encode(array('mensaje' => $e->getMessage()));
        return;
    }

    $usuarios = usuarios::obtenerTodosLosUsuarios();

    http_response_code(200); // OK
    echo json_encode($usuarios);
}

function actualizarUsuario() {
    try {
        usuarios::autorizar();
    } catch (ExcepcionApi $e) {
        http_response_code($e->getCode());
        echo json_encode(array('mensaje' => $e->getMessage()));
        return;
    }

    $datosUsuario = json_decode(file_get_contents('php://input'), true);

    if (empty($datosUsuario['idUsuario']) || empty($datosUsuario['nombre'])) {
        http_response_code(400); // Solicitud incorrecta
        echo json_encode(array('mensaje' => 'Faltan datos requeridos'));
        return;
    }

    $resultado = usuarios::actualizarUsuario($datosUsuario['idUsuario'], $datosUsuario['nombre']);

    if (strpos($resultado, 'exitosamente') !== false) {
        http_response_code(200); // OK
    } else {
        http_response_code(500); // Error interno del servidor
    }
    echo json_encode(array('mensaje' => $resultado));
}

function eliminarUsuario() {
    try {
        usuarios::autorizar();
    } catch (ExcepcionApi $e) {
        http_response_code($e->getCode());
        echo json_encode(array('mensaje' => $e->getMessage()));
        return;
    }

    $datosUsuario = json_decode(file_get_contents('php://input'), true);

    if (empty($datosUsuario['idUsuario'])) {
        http_response_code(400); // Solicitud incorrecta
        echo json_encode(array('mensaje' => 'Falta el ID del usuario a eliminar'));
        return;
    }

    $resultado = usuarios::eliminarUsuario($datosUsuario['idUsuario']);

    if (strpos($resultado, 'exitosamente') !== false) {
        http_response_code(200); // OK
    } else {
        http_response_code(500); // Error interno del servidor
    }
    echo json_encode(array('mensaje' => $resultado));
}

manejarSolicitud();
?>
