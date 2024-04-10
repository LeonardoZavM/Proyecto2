<?php
include 'vars.php';

# Verificar si vienen los parámetros requeridos
if (empty($_GET["id"])) {
    http_response_code(400);
    exit("Falta id"); # Terminar el script definitivamente
}

if (empty($_GET["Nombre"])) {
    http_response_code(400);
    exit("Falta nombre"); # Terminar el script definitivamente
}

if (empty($_GET["Stock"])) {
    http_response_code(400);
    exit("Falta stock"); # Terminar el script definitivamente
}


$conex = new PDO("sqlite:" . $nombre_fichero);
$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$producto = [
    "id" => $_GET["id"],
    "nombre" => $_GET["nombre"],
    "stock" => $_GET["stock"]
];

try {
    # Preparar la consulta para actualizar los datos del producto
    $sentencia = $conex->prepare("UPDATE productos SET nombre=:nombre, tipo=:tipo, stock=:stock, stockmax=:stockmax WHERE id=:id;");
    $resultado = $sentencia->execute($producto);

    # Si se actualizaron los datos correctamente, responder con un mensaje de éxito
    if ($resultado) {
        http_response_code(200);
        echo "Datos actualizados correctamente";
    } else {
        # Si hubo algún error al actualizar los datos, responder con un mensaje de error
        http_response_code(400);
        echo "Error al actualizar los datos";
    }
} catch(PDOException $exc) {
    # Si hubo una excepción, responder con un mensaje de error
    http_response_code(400);
    echo "Lo siento, ocurrió un error: " . $exc->getMessage();
}

# Cerrar la conexión a la base de datos
$conex = null;
?>