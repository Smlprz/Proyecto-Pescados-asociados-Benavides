<?php
include("conexion.php");

$jaulaid = 101;

$sql = "SELECT FECHA, MUERTEACT FROM JAULAINF WHERE JAULAID = ? ORDER BY FECHA";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $jaulaid);
$stmt->execute();
$result = $stmt->get_result();

$fechas = [];
$muertes = [];
while ($row = $result->fetch_assoc()) {
    $fechas[] = $row['FECHA'];
    $muertes[] = $row['MUERTEACT'];
}

$muerteActual = 0;
if (count($muertes) >= 2) {
    $muerteActual = $muertes[count($muertes) - 1] - $muertes[count($muertes) - 2];
}

$sql2 = "SELECT COSECHAINICIAL, MUERTEESP FROM JAULA WHERE JAULAID = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $jaulaid);
$stmt2->execute();
$result2 = $stmt2->get_result();
$row2 = $result2->fetch_assoc();

$cosecha = $row2['COSECHAINICIAL'];
$muerteEsperada = $row2['MUERTEESP'];

$porcentajeActual = $cosecha > 0 ? ($muerteActual / $cosecha) * 100 : 0;
$porcentajeEsperado = $muerteEsperada;

echo json_encode([
    "fechas" => $fechas,
    "muertes" => $muertes,
    "porcentajeActual" => $porcentajeActual,
    "porcentajeEsperado" => $porcentajeEsperado
]);
?>
