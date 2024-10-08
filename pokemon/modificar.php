<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'pokedex');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT imagen, tipo, numero_identificador, nombre, descripcion FROM pokemon WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pokemon = $result->fetch_assoc();
    } else {
        echo "Pokémon no encontrado.";
        exit();
    }
    $stmt->close();
} else {
    echo "ID no válido.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $numero_identificador = $_POST['numero_identificador'];
    $descripcion = $_POST['descripcion'];

    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagenTmp = $_FILES['imagen']['tmp_name'];
        $imagenNombre = basename($_FILES['imagen']['name']);
        $imagenDestino = '../images/' . $imagenNombre;

        if (move_uploaded_file($imagenTmp, $imagenDestino)) {
            $rutaImagenBD = 'images/' . $imagenNombre;
        } else {
            echo "Error al subir la nueva imagen.";
            exit();
        }
    } else {
        $rutaImagenBD = $pokemon['imagen'];
    }

    // Consulta para actualizar los datos del Pokémon
    $sql = "UPDATE pokemon SET nombre = ?, tipo = ?, numero_identificador = ?, imagen = ?, descripcion = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssissi', $nombre, $tipo, $numero_identificador, $rutaImagenBD, $descripcion, $id);

    if ($stmt->execute()) {
        echo "Pokémon modificado con éxito.";
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error al modificar el Pokémon: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Pokémon</title>
</head>
<body>
<?php

include_once("../header.php");
?>

<div class="container mt-5">
    <h2 class="text-center">Modificar Pokémon</h2>

    <form method="post" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $pokemon['nombre']; ?>" required>
        </div>

        <div class="mb-3">
            <div class="form-group">
                <label for="tipo">Tipo del Pokémon</label>
                <select name="tipo" id="tipo" class="form-control" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="Fuego">Fuego</option>
                    <option value="Agua">Agua</option>
                    <option value="Planta">Planta</option>
                    <option value="Roca">Roca</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="numero_identificador" class="form-label">Número Identificador:</label>
            <input type="number" class="form-control" id="numero_identificador" name="numero_identificador" value="<?php echo $pokemon['numero_identificador']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripcion:</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo $pokemon['descripcion']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Nueva Imagen (opcional):</label>
            <input type="file" class="form-control" id="imagen" name="imagen">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success">Modificar</button>
            <a href="../index.php" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>
</body>
</html>
