<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
    call_user_func_array(mysqli_stmt_bind_result, $fields);
}

$query = "SELECT dni, nombre, apellido1, apellido2, login, email, familia, activo, tutoria "
        . "FROM profesores "
        . "WHERE dni=?";
/*
  $result = $conexion->query($query);

  while ($row = $result->fetch_assoc()) {
  $data[] = $row;
  }
  echo json_encode($data);

 */
$dni = $_REQUEST['dni'];
if ($stmt = $conexion->prepare($query)) {
    $stmt->bind_param('s',$dni);

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

