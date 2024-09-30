<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    // Redirigir si el usuario no está autenticado
    header("Location: index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agregar Nuevo Pokémon</title>
</head>
<body>
<?php

include_once("../header.php");
?>

<div class="container mt-4">
    <h2 class="text-center">Agregar Nuevo Pokémon</h2>
    <form action="guardar_pokemon.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre del Pokémon</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

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

        <div class="form-group">
            <label for="numero_identificador">Número Identificador</label>
            <input type="number" name="numero_identificador" id="numero_identificador" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripcion</label>
            <input type="TEXT" name="descripcion" id="descripcion" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen del Pokémon</label>
            <input type="file" name="imagen" id="imagen" class="form-control" required>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Agregar Pokémon</button>
            <a href="index.php" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>
</body>
</html>
