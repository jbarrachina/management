<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'connection.php';

function stmt_bind_assoc(&$stmt, &$out) {
    $data = mysqli_stmt_result_metadata($stmt);
    $fields = array();
    $out = array();

    $fields[0] = $stmt;
    $count = 1;

    while ($field = mysqli_fetch_field($data)) {
        $fields[$count] = &$out[$field->name];
        $count++;
    }
    call_user_func_array("mysqli_stmt_bind_result", $fields);
}

$query = "SELECT dni, nombre, apellido1, apellido2, login, email, familia, activo, tutoria "
        . "FROM profesores "
        . "ORDER BY apellido1, apellido2, nombre ";

$result = $conexion->query($query);

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);

/*

if ($stmt = $conexion->prepare($query)) {
    if (!$stmt->execute()) {
        die('Error de ejecución de la consulta. ' . $conexion->error);
    }
    $row[] = array();
    stmt_bind_assoc($stmt, $row);

// loop through all result rows
    while ($stmt->fetch()) {
        echo json_encode($row);
    }
}    

*/