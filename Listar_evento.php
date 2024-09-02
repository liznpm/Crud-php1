<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulos = $_POST['titulo'];
    $descripciones = $_POST['descripcion'];
    $fechas_inicio = $_POST['fecha_inicio'];
    $fechas_final = $_POST['fecha_final'];
    $fechas_ejecucion = $_POST['fecha_ejecucion'];

    for ($i = 0; $i < count($titulos); $i++) {
        $titulo = $titulos[$i];
        $descripcion = $descripciones[$i];
        $fecha_inicio = $fechas_inicio[$i];
        $fecha_final = $fechas_final[$i];
        $fecha_ejecucion = $fechas_ejecucion[$i];

        $sql = "INSERT INTO eventos (titulo, descripcion, fecha_inicio, fecha_final, fecha_ejecucion)
                VALUES (?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssss", $titulo, $descripcion, $fecha_inicio, $fecha_final, $fecha_ejecucion);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['form_processed'] = true;
            } else {
                echo "<div class='alert alert-danger'>Error al agregar el evento '$titulo': " . mysqli_stmt_error($stmt) . "</div>";
            }
            mysqli_stmt_close($stmt);
        }
    }

    if (isset($_SESSION['form_processed']) && $_SESSION['form_processed']) {
        $_SESSION['form_processed'] = false;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$sql = "SELECT * FROM eventos";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Lista de Eventos</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha Final</th>
                    <th>Fecha de Ejecución</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $titulo = $row['titulo'];
                        $descripcion = $row['descripcion'];
                        $fecha_inicio = $row['fecha_inicio'];
                        $fecha_final = $row['fecha_final'];
                        $fecha_ejecucion = $row['fecha_ejecucion'];

                        $fecha_actual = date('Y-m-d'); // Fecha actual

                        if ($fecha_inicio == '0000-00-00' || empty($fecha_inicio)) {
                            $estado = "No Iniciado";
                            $color = "#FFFF00"; // Fondo amarillo para no iniciado
                        } elseif ($fecha_inicio <= $fecha_final) {
                            $estado = "Finalizado";
                            $color = "#00FF00"; // Fondo verde para finalizado
                            
                        } elseif ($fecha_inicio <= $fecha_ejecucion ) {
                            $estado = "En Proceso";
                            $color = "#0000FF"; // Fondo azul para en proceso
                        
                        }
                        
                        ?>
                        <tr id="evento-<?php echo $row['id']; ?>" style="background-color: <?php echo $color; ?>;">
                            <td><?php echo htmlspecialchars($titulo); ?></td>
                            <td><?php echo htmlspecialchars($descripcion); ?></td>
                            <td><?php echo htmlspecialchars($fecha_inicio); ?></td>
                            <td><?php echo htmlspecialchars($fecha_final); ?></td>
                            <td><?php echo htmlspecialchars($fecha_ejecucion); ?></td>
                            <td><?php echo htmlspecialchars($estado); ?></td>
                            <td>
                                <!-- Botón de Editar -->
                                <a href="Editar_evento.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <!-- Botón de Eliminar -->
                                <button class="btn btn-danger btn-sm" 
                                        onclick="eliminarEvento(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No hay eventos.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function eliminarEvento(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este evento?')) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "Eliminar_evento.php?id=" + id, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('evento-' + id).style.display = 'none';
                        } else {
                            alert(response.message);
                        }
                    }
                };
                xhr.send();
            }
        }
    </script>
</body>
</html>
