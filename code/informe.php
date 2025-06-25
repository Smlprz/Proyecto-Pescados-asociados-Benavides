<?php
include("conexion.php");

$query = mysqli_query($conn, "SELECT FECHA, MUERTEACT FROM JAULAINF WHERE JAULAID = 101");

$fechas = [];
$muertes = [];

while ($row = mysqli_fetch_assoc($query)) {
    $fechas[] = $row['FECHA'];
    $muertes[] = $row['MUERTEACT'];
}

echo json_encode([
    "labels" => $fechas,
    "data" => $muertes
]);
?>
