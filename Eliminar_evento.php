<?php
include 'conexion.php'; 

$response = array();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT fecha_inicio, fecha_ejecucion, fecha_final FROM eventos WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $fecha_inicio, $fecha_ejecucion, $fecha_final);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $fecha_actual = date('Y-m-d'); 

        if ($fecha_inicio <= $fecha_actual) {
            if ($fecha_ejecucion >= $fecha_inicio || $fecha_actual < $fecha_final) {

            $sql = "DELETE FROM eventos WHERE id = ?";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $id);
                if (mysqli_stmt_execute($stmt)) {
                    $response['status'] = 'success';
                    $response['message'] = 'Evento eliminado exitosamente.';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Error al eliminar el evento: ' . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error al preparar la consulta de eliminación: ' . mysqli_error($conn);
            }
        }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No se puede eliminar el evento porque la fecha de ejecución ha pasado.';
        }
    
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error al preparar la consulta: ' . mysqli_error($conn);
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'ID de evento no válido.';
}

mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($response);

?>
