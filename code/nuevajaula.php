<?php
include("conexion.php");
if (
    isset($_POST['jaulaid']) &&
    isset($_POST['centro']) &&
    isset($_POST['biomasa']) &&
    isset($_POST['muerteesp']) &&
    isset($_POST['cosecha'])
) {
    $jaulaid = $_POST['jaulaid'];
    $centroid = $_POST['centro'];
    $biomasa = $_POST['biomasa'];
    $muerteesp = $_POST['muerteesp'];
    $cosechainicial = $_POST['cosecha'];

    // Insertar en JAULA
    $stmt1 = $conn->prepare("INSERT INTO JAULA (JAULAID, MUERTEESP, COSECHAINICIAL, CENTROID) VALUES (?, ?, ?, ?)");
    $stmt1->bind_param("iiii", $jaulaid, $muerteesp, $cosechainicial, $centroid);
    $stmt1->execute();

    // Insertar datos iniciales en JAULAINF
    $fecha = date("Ymd");
    $oxigeno = 10;
    $temperatura = 8;
    $muerteact = 0;

    $stmt2 = $conn->prepare("INSERT INTO JAULAINF (JAULAID, BIOMASA, OXIGENO, TEMPERATURA, MUERTEACT, FECHA) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("iiiiis", $jaulaid, $biomasa, $oxigeno, $temperatura, $muerteact, $fecha);
    $stmt2->execute();

    echo "✅ Jaula insertada correctamente.";
} else {
    echo "❌ Todos los campos son obligatorios.";
}

$conn->close();
?>
