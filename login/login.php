<?php
session_start();

// Conectar a la base de datos (asegúrate de ajustar los parámetros de conexión)
$conn = new mysqli('localhost', 'root', '', 'pokedex');

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verifica si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario encontrado, ahora verificamos la contraseña
        $user = $result->fetch_assoc();
        if ($password == $user['password']) {

            $_SESSION['usuario'] = $user['username'];

            header("Location: ../index.php");
            exit();
        } else {
            // Contraseña incorrecta
            echo "Contraseña incorrecta.";
        }
    } else {
        // Usuario no encontrado
        echo "Usuario no encontrado.";
    }
}

$conn->close();
?>
