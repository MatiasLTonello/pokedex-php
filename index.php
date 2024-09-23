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
         <!-- Barra de búsqueda -->
         <form method="GET" action="index.php" class="d-flex justify-content-center mb-4">
             <input type="text" name="search" class="form-control w-50" placeholder="Ingrese el nombre, tipo o número de pokémon">
             <button type="submit" class="btn btn-primary ml-2">¿Quién es este pokémon?</button>
         </form>
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

             // Filtrar los resultados según la búsqueda
             $search = isset($_GET['search']) ? $_GET['search'] : '';

             $sql = "SELECT imagen, tipo, numero_identificador, nombre,id FROM pokemon";

             // Si hay un valor de búsqueda, usar una consulta preparada
             if (!empty($search)) {
                 // Preparar la consulta con comodines LIKE
                 $sql .= " WHERE nombre LIKE ? OR tipo LIKE ? OR numero_identificador LIKE ?";

                 // Preparar la consulta SQL
                 $stmt = $conn->prepare($sql);

                 // Agregar los comodines de búsqueda (%)
                 $search_param = '%' . $search . '%';

                 // Enlazar los parámetros a la consulta preparada
                 $stmt->bind_param('sss', $search_param, $search_param, $search_param);

                 $stmt->execute();

                 $result = $stmt->get_result();
             } else {
                 $result = $conn->query($sql);
             }

             if ($result->num_rows > 0) {
                 while($row = $result->fetch_assoc()) {
                     // Seleccionar la imagen del tipo de Pokémon
                     $tipo = strtolower($row['tipo']); // Convertir el tipo a minúsculas para coincidir con el nombre de archivo
                     $tipo_image_path = "./public/tipo_$tipo.webp"; // Ruta de la imagen basada en el tipo

                     echo "<tr>";
                     echo "<td><img src='" . $row['imagen'] . "' alt='Imagen de " . $row['nombre'] . "' style='width: 50px; height: 50px;'></td>";
                     echo "<td><img src='$tipo_image_path' alt='Tipo " . $row['tipo'] . "' style='width: 50px; height: 50px;'></td>"; // Imagen del tipo
                     echo "<td>" . $row['numero_identificador'] . "</td>";
                     echo "<td>" . $row['nombre'] . "</td>";

                     if (isset($_SESSION['usuario'])) {
                         echo "<td>
                  <a href='/pokecrud/pokemon/modificar.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Modificación</a>
                  <a href='/pokecrud/pokemon/baja.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Baja</a>
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
                 <a href="pokemon/nuevo_pokemon.php" class="btn btn-primary">Nuevo Pokémon</a>
             </div>
         <?php endif; ?>
     </div>
</body>
</html>