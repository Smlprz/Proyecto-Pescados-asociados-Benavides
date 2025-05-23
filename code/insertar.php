<?php
include("conexion.php");
if (isset($_POST['muerteact']) && isset($_POST['biomasa'])) {
    $muerteact = $_POST['muerteact'];
    $biomasa = $_POST['biomasa'];

    if ($conn) {
        $query = "SELECT TEMPERATURA, OXIGENO, JAULAID FROM jaulainf WHERE JAULAID = 101 ORDER BY FECHA DESC LIMIT 1";
        $res = mysqli_query($conn, $query);

        if (mysqli_num_rows($res) > 0) {
            $prevData = mysqli_fetch_assoc($res);
            $temperatura = $prevData['TEMPERATURA'];
            $oxigeno = $prevData['OXIGENO'];
            $jaulaid = $prevData['JAULAID'];
            $insert = "
                INSERT INTO jaulainf (JAULAID, FECHA, BIOMASA, MUERTEACT, TEMPERATURA, OXIGENO)
                VALUES ($jaulaid, CURRENT_DATE, '$biomasa', '$muerteact', '$temperatura', '$oxigeno')
            ";

            if (mysqli_query($conn, $insert)) {
                echo json_encode(['success' => true, 'message' => 'Registro insertado correctamente.']);
            } else {
                echo json_encode(['error' => 'Error al insertar: ' . mysqli_error($conn)]);
            }

        } else {
            echo json_encode(['error' => 'No se encontraron datos anteriores para copiar.']);
        }

    } else {
        echo json_encode(['error' => 'Error de conexión con la base de datos.']);
    }
} else {
    echo json_encode(['error' => 'Faltan parámetros: muerteact y/o biomasa.']);
}
?>