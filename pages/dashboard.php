<?php
session_start();
require_once "../php/conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.html");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];

$stmt = $conexion->prepare(
    "SELECT i.id, i.nombre, i.email, i.telefono, t.nombre AS taller_nombre
     FROM inscripciones i
     LEFT JOIN talleres t ON i.taller_id = t.id
     WHERE i.usuario_id = ?"
);
$stmt->execute([$usuario_id]);
$inscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtActividades = $conexion->prepare(
    "SELECT a.id, ac.nombre, ac.descripcion, t.nombre AS taller_nombre
     FROM actividad_inscripciones a
     LEFT JOIN actividades ac ON a.actividad_id = ac.id
     LEFT JOIN talleres t ON ac.taller_id = t.id
     WHERE a.usuario_id = ?"
);
$stmtActividades->execute([$usuario_id]);
$actividades_inscritas = $stmtActividades->fetchAll(PDO::FETCH_ASSOC);
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
                    <a href="../index.php" class="brand-logo">ACTE-2026</a>

                    <a href="#" data-target="mobile-demo" class="sidenav-trigger hide-on-large-only">
                        <i class="material-icons">menu</i>
                    </a>

                    <ul class="right hide-on-med-and-down">
                        <li><a href="../index.php">Inicio</a></li>
                        <li><a href="../index.php#talleres">Talleres</a></li>
                        <li><a href="../index.php#actividades">Actividades</a></li>
                        <li><a href="../index.php#contacto">Contacto</a></li>
                        <li><a class="waves-effect red btn" href="../php/logout.php">Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <ul class="sidenav" id="mobile-demo">
            <li><a href="../index.php">Inicio</a></li>
            <li><a href="../index.php#talleres">Talleres</a></li>
            <li><a href="../index.php#actividades">Actividades</a></li>
            <li><a href="../index.php#contacto">Contacto</a></li>
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
                <?php if (!empty($inscripciones)): ?>
                    <table class="highlight responsive-table">
                        <thead>
                            <tr>
                                <th>Taller</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscripciones as $inscripcion): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($inscripcion['taller_nombre'] ?? 'No definido'); ?></td>
                                    <td><?php echo htmlspecialchars($inscripcion['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($inscripcion['email']); ?></td>
                                    <td><?php echo htmlspecialchars($inscripcion['telefono']); ?></td>
                                    <td>
                                        <form method="POST" action="../php/cancel_inscripcion.php" style="display:inline;">
                                            <input type="hidden" name="inscripcion_id" value="<?php echo htmlspecialchars($inscripcion['id']); ?>">
                                            <button class="waves-effect waves-light btn red" type="submit" onclick="return confirm('¿Cancelar esta inscripción?');">Cancelar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay inscripciones registradas todavía.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="container">
            <h4 class="left-align">Inscripciones a Actividades</h4>

            <div class="card-panel">
                <?php if (!empty($actividades_inscritas)): ?>
                    <table class="highlight responsive-table">
                        <thead>
                            <tr>
                                <th>Taller</th>
                                <th>Actividad</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($actividades_inscritas as $actividad): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($actividad['taller_nombre'] ?? 'No definido'); ?></td>
                                    <td><?php echo htmlspecialchars($actividad['nombre']); ?></td>
                                    <td>
                                        <form method="POST" action="../php/cancel_actividad_inscripcion.php" style="display:inline;">
                                            <input type="hidden" name="actividad_inscripcion_id" value="<?php echo htmlspecialchars($actividad['id']); ?>">
                                            <button class="waves-effect waves-light btn red" type="submit" onclick="return confirm('¿Cancelar esta inscripción de actividad?');">Cancelar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay actividades registradas todavía.</p>
                <?php endif; ?>
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