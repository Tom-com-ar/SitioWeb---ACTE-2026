<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Usuario</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header>
        <nav>
            <div class="nav-wrapper black">
                <div class="container">
                    <a href="../index.html" class="brand-logo">ACTE-2026</a>

                    <a href="#" data-target="mobile-demo" class="sidenav-trigger">
                        <i class="material-icons">menu</i>
                    </a>

                    <ul class="right hide-on-med-and-down">
                        <li><a href="../index.html">Inicio</a></li>
                        <li><a href="../index.html#talleres">Talleres</a></li>
                        <li><a href="../index.html#actividades">Actividades</a></li>
                        <li><a href="../index.html#contacto">Contacto</a></li>
                        <li><a class="waves-effect red btn" href="../php/logout.php">Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <ul class="sidenav" id="mobile-demo">
            <li><a href="../index.html">Inicio</a></li>
            <li><a href="../index.html#talleres">Talleres</a></li>
            <li><a href="../index.html#actividades">Actividades</a></li>
            <li><a href="../index.html#contacto">Contacto</a></li>
            <li><a class="waves-effect red btn" href="../php/logout.php">Cerrar Sesión</a></li>
        </ul>
    </header>

    <main>
        <div class="container">
            <h4 class="left-align">Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?></h4>
            <h5 class="left-align">Datos del Usuario</h5>

            <div class="card-panel">
                <p><strong>Nombre completo:</strong> <?php echo htmlspecialchars(trim($_SESSION["usuario_nombre"] . ' ' . ($_SESSION["usuario_apellido"] ?? ''))); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION["usuario_email"]); ?></p>
                <p><strong>ID usuario:</strong> <?php echo htmlspecialchars($_SESSION["usuario_id"]); ?></p>
            </div>
        </div>

        <div class="container">
            <h4 class="left-align">Inscripciones</h4>

            <div class="card-panel">
                <p>No hay inscripciones registradas todavía.</p>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../js/main.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const elems = document.querySelectorAll('.sidenav');
            M.Sidenav.init(elems, {
                edge: 'left',
                draggable: true
            });

            const links = document.querySelectorAll('.sidenav a');
            links.forEach(link => {
                link.addEventListener('click', () => {
                    const instance = M.Sidenav.getInstance(document.querySelector('.sidenav'));
                    instance.close();
                });
            });
        });
    </script>

</body>

</html>