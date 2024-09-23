<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    // Redirigir si el usuario no está autenticado
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'pokedex');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $numero_identificador = $_POST['numero_identificador'];

    // Validar y subir la imagen
    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagenTmp = $_FILES['imagen']['tmp_name'];
        $imagenNombre = basename($_FILES['imagen']['name']);
        $imagenDestino = '../images/' . $imagenNombre;

        // Mover la imagen a la carpeta 'images'
        if (move_uploaded_file($imagenTmp, $imagenDestino)) {
            // Preparar la consulta SQL para insertar los datos
            $sql = "INSERT INTO pokemon (nombre, tipo, numero_identificador, imagen) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $rutaImagenBD = 'images/' . $imagenNombre;

            // Enlazar parámetros para evitar inyección SQL
            $stmt->bind_param('ssis', $nombre, $tipo, $numero_identificador, $rutaImagenBD);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "Pokémon agregado con éxito.";
                header("Location: ../index.php"); // Redirigir de nuevo a la lista
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