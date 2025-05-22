<?php
header('Content-Type: application/json');
include("conexion.php"); 

if ($conn) {
    $jaulac = "SELECT * FROM jaulainf WHERE JAULAID=101 AND FECHA=CURRENT_DATE";
    $jaular = mysqli_query($conn, $jaulac);
    if (mysqli_num_rows($jaular) > 0) {
        $data = mysqli_fetch_assoc($jaular);
        echo json_encode($data);
    } else {
        echo json_encode(null);
    }
} else {
    echo json_encode(["error" => "No se pudo conectar a la base de datos."]);
}
?>