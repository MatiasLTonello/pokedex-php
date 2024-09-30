<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'pokedex');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $numero_identificador = $_POST['numero_identificador'];
    $descripcion = $_POST['descripcion'];

    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagenTmp = $_FILES['imagen']['tmp_name'];
        $imagenNombre = basename($_FILES['imagen']['name']);
        $imagenDestino = '../images/' . $imagenNombre;

        if (move_uploaded_file($imagenTmp, $imagenDestino)) {
            $sql = "INSERT INTO pokemon (nombre, tipo, numero_identificador, imagen, descripcion) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $rutaImagenBD = 'images/' . $imagenNombre;

            $stmt->bind_param('ssiss', $nombre, $tipo, $numero_identificador, $rutaImagenBD, $descripcion);

            if ($stmt->execute()) {
                echo "Pokémon agregado con éxito.";
                header("Location: ../index.php");
                exit();
            } else {
                echo "Error al agregar el Pokémon: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        echo "Error en el archivo de imagen.";
    }

    $conn->close();
} else {
    echo "Método de solicitud no válido.";
}
?>