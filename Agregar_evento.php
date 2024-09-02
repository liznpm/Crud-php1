<?php
include 'conexion.php';

if (isset($_POST['submit'])) {
    $titulo = $_POST['titulo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_final = $_POST['fecha_final'];
    $fecha_ejecucion = $_POST['fecha_ejecucion'];

    $estado = '';
    $fecha_actual = date('Y-m-d');

if ($fecha_inicio == '0000-00-00' || empty($fecha_inicio)) {
    $estado = "No Iniciado";
    $color = "#FFFF00"; 
} elseif ($fecha_inicio <= $fecha_final) {
    $estado = "Finalizado";
    $color = "#00FF00"; 
    
} elseif ($fecha_inicio <= $fecha_actual ) {
    $estado = "En Proceso";
    $color = "#0000FF"; 

}



    $sql = "INSERT INTO eventos (titulo, fecha_inicio, fecha_final, fecha_ejecucion, estado) 
            VALUES ('$titulo', '$fecha_inicio', '$fecha_final', '$fecha_ejecucion', '$estado')";

    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success'>Evento agregado exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<body>



    <div class="container mt-5 mr-5">
        <h2 class="mb-4">Agregar Eventos</h2>
        <form action="agregar_evento.php" method="POST">
            <div id="eventos">
                <div class="col-md-6 mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" name="titulo[]" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea name="descripcion[]" class="form-control"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
                        <input type="date" name="fecha_inicio[]" class="form-control" >
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="fecha_final" class="form-label">Fecha Final:</label>
                        <input type="date" name="fecha_final[]" class="form-control" >
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="fecha_ejecucion" class="form-label">Fecha de Ejecución:</label>
                        <input type="date" name="fecha_ejecucion[]" class="form-control"> 
                      

                    </div>
                </div>
            </div>
            
            <input type="submit" value="Agregar Eventos" class="btn btn-primary">

        <?php include 'listar_evento.php'; ?>

        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('agregar').onclick = function() {
        const eventosDiv = document.getElementById('eventos');
        const nuevoEvento = eventosDiv.firstElementChild.cloneNode(true);
        eventosDiv.appendChild(nuevoEvento);
    };
    </script>
</body>
</html>
