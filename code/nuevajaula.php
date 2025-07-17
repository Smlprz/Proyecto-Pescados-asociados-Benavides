<?php
include("conexion.php");

// Verificar que todos los campos requeridos están presentes
$camposRequeridos = ['jaulaid', 'centro', 'cosecha', 'muerteesp', 'temperatura', 'oxigeno'];
$datosFaltantes = [];

foreach ($camposRequeridos as $campo) {
    if (!isset($_POST[$campo]) || $_POST[$campo] === '') {
        $datosFaltantes[] = $campo;
    }
}

if (!empty($datosFaltantes)) {
    $response = [
        'status' => 'error',
        'message' => 'Campos requeridos faltantes',
        'missing_fields' => $datosFaltantes,
        'received_data' => $_POST
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Asignar valores
$jaulaid = intval($_POST['jaulaid']);
$centroid = intval($_POST['centro']);
$cosechainicial = intval($_POST['cosecha']);
$muerteesp = floatval($_POST['muerteesp']);
$temperatura = floatval($_POST['temperatura']);
$oxigeno = floatval($_POST['oxigeno']);

// Calcular biomasa inicial (0.5 gramos por alevín)
$biomasa = $cosechainicial * 0.0005; // Convertir a kg

try {
    // Iniciar transacción
    $conn->begin_transaction();

    // 1. Insertar en JAULA
    $stmt1 = $conn->prepare("INSERT INTO JAULA (JAULAID, MUERTEESP, COSECHAINICIAL, CENTROID) VALUES (?, ?, ?, ?)");
    $stmt1->bind_param("iiii", $jaulaid, $muerteesp, $cosechainicial, $centroid);
    $stmt1->execute();

    // 2. Insertar datos iniciales en JAULAINF
    $fecha = date("Y-m-d");
    $muerteact = 0; // Inicialmente no hay muertes

    $stmt2 = $conn->prepare("INSERT INTO JAULAINF (JAULAID, BIOMASA, OXIGENO, TEMPERATURA, MUERTEACT, FECHA) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("idddis", $jaulaid, $biomasa, $oxigeno, $temperatura, $muerteact, $fecha);
    $stmt2->execute();

    // Confirmar transacción
    $conn->commit();

    $response = [
        'status' => 'success',
        'message' => 'Jaula registrada exitosamente',
        'data' => [
            'jaula_id' => $jaulaid,
            'biomasa_kg' => $biomasa,
            'mortalidad_esperada' => $muerteesp,
            'muertes_esperadas' => round($cosechainicial * ($muerteesp/100)),
            'parametros' => [
                'temperatura' => $temperatura,
                'oxigeno' => $oxigeno,
                'fecha_registro' => $fecha
            ]
        ]
    ];

} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    
    $response = [
        'status' => 'error',
        'message' => 'Error al registrar la jaula: ' . $e->getMessage(),
        'error_code' => $e->getCode()
    ];
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
