<?php
include 'conexion.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM eventos WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $evento = $result->fetch_assoc();

        if (!$evento) {
            echo "<div class='alert alert-danger'>Evento no encontrado.</div>";
            exit();
        }
    } else {
        echo "<div class='alert alert-danger'>Error en la preparación de la consulta: " . $conn->error . "</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>ID inválido.</div>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_final = $_POST['fecha_final'];
    $fecha_ejecucion = $_POST['fecha_ejecucion'];

    $fecha_actual = date('Y-m-d');

    if ($fecha_inicio <= $fecha_actual) {
        if ($fecha_ejecucion >= $fecha_inicio || $fecha_actual < $fecha_ejecucion) {
            $sql = "UPDATE eventos SET titulo=?, descripcion=?, fecha_inicio=?, fecha_final=?, fecha_ejecucion=? WHERE id=?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sssssi", $titulo, $descripcion, $fecha_inicio, $fecha_final, $fecha_ejecucion, $id);
                if ($stmt->execute()) {
                    header("Location: agregar_evento.php?msg=Evento actualizado exitosamente");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Error en la preparación de la consulta: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>No se puede actualizar el evento porque la fecha de ejecución ha pasado.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No se puede actualizar el evento porque la fecha de inicio no puede ser posterior a la fecha actual.</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Editar Evento</h2>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($evento['id']); ?>">

            <div class="mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($evento['titulo']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea name="descripcion" class="form-control"><?php echo htmlspecialchars($evento['descripcion']); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
                    <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($evento['fecha_inicio']); ?>" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_final" class="form-label">Fecha Final:</label>
                    <input type="date" name="fecha_final" value="<?php echo htmlspecialchars($evento['fecha_final']); ?>" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_ejecucion" class="form-label">Fecha de Ejecución:</label>
                    <input type="date" name="fecha_ejecucion" value="<?php echo htmlspecialchars($evento['fecha_ejecucion']); ?>" class="form-control">
                </div>
            </div>

           

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="agregar_evento.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
