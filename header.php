<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi sitio web</title>
    <!-- Puedes incluir aquí tus estilos y scripts -->
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
</head>
<body>
<header>
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/pokecrud/index.php">
                <img src="/pokecrud/public/logo.svg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                Logo
            </a>
            <span class="navbar-text fs-4 fw-bold">
        Pokedex
      </span>
            <?php if(isset($_SESSION['usuario'])): ?>
                <p>Usuario ADMIN</p>
                <a href="logout/logout.php" class="btn btn-outline-danger">Cerrar sesión</a>
            <?php else: ?>
                <form class="d-flex" action="login/login.php" method="POST">
                    <input class="form-control me-2" type="text" name="usuario" placeholder="Usuario" aria-label="Usuario" required>
                    <input class="form-control me-2" type="password" name="password" placeholder="Password" aria-label="Password" required>
                    <button class="btn btn-outline-success" type="submit">Ingresar</button>
                </form>
            <?php endif; ?>
        </div>
    </nav></header>