<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pokedex</title>
</head>
<body>
     <?php
        session_start();
        include_once("header.php");
     ?>

     <div class="container mt-4">
         <h2 class="text-center">Lista de Pokémon</h2>
         <table class="table table-bordered text-center">
             <thead class="table-light">
             <tr>
                 <th>Imagen</th>
                 <th>Tipo</th>
                 <th>Número</th>
                 <th>Nombre</th>
                 <?php if(isset($_SESSION['usuario'])): ?>
                     <th>Acciones</th>
                 <?php endif; ?>             </tr>
             </thead>
             <tbody>
             <?php
             $conn = new mysqli('localhost', 'root', '', 'pokedex');

             if ($conn->connect_error) {
                 die("Conexión fallida: " . $conn->connect_error);
             }

             $sql = "SELECT imagen, tipo, numero_identificador, nombre FROM pokemon";
             $result = $conn->query($sql);

             if ($result->num_rows > 0) {
                 while($row = $result->fetch_assoc()) {
                     echo "<tr>";
                     echo "<td><img src='" . $row['imagen'] . "' alt='Imagen de " . $row['nombre'] . "' style='width: 50px; height: 50px;'></td>";
                     echo "<td>" . $row['tipo'] . "</td>";
                     echo "<td>" . $row['numero_identificador'] . "</td>";
                     echo "<td>" . $row['nombre'] . "</td>";
                     if (isset($_SESSION['usuario'])) {
                         echo "<td>
                      <a href='modificar.php?id=" . $row['numero_identificador'] . "' class='btn btn-warning btn-sm'>Modificación</a>
                      <a href='baja.php?id=" . $row['numero_identificador'] . "' class='btn btn-danger btn-sm'>Baja</a>
                    </td>";
                     }
                     echo "</tr>";
                 }
             } else {
                 echo "<tr><td colspan='5'>No hay Pokémon registrados</td></tr>";
             }

             $conn->close();
             ?>
             </tbody>
         </table>

         <?php if(isset($_SESSION['usuario'])): ?>
             <div class="text-center mt-4">
                 <a href="nuevo_pokemon.php" class="btn btn-primary">Nuevo Pokémon</a>
             </div>
         <?php endif; ?>
     </div>
</body>
</html>