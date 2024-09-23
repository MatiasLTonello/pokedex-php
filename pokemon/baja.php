<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'pokedex');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Preparar la consulta para eliminar el Pokémon
    $id = $_GET['id'];

    // Usar una consulta preparada para evitar inyección SQL
    $sql = "DELETE FROM pokemon WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "Pokémon eliminado con éxito.";
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error al eliminar el Pokémon: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID de Pokémon no especificado.";
}
?>