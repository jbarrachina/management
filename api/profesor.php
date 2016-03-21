<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * 
 * jose@jose-Lenovo-G50-70:/var/www/html/cursoangularjs/Server/api$ composer require slim/slim "^3.0"
 * 
 * http://localhost/cursoangularjs/Server/api/hello.php/hello/jose
 * 
 * and open the template in the editor.
 */


require 'vendor/autoload.php';
require 'connection.php';

$app = new Slim\App();

$app->get('/hello/{name}', function ($request, $response, $args) {
    $response->write("Hello, " . $args['name']);
    return $response;
});

$app->get('/', function() {
    echo "Pagina de gestión API REST de mi aplicación.";
});

// Cuando accedamos por get a la ruta /usuarios ejecutará lo siguiente:
$app->get('/profesores', function() use($db) {
    // Si necesitamos acceder a alguna variable global en el framework
    // Tenemos que pasarla con use() en la cabecera de la función. Ejemplo: use($db)
    // Va a devolver un objeto JSON con los datos de usuarios.
    // Preparamos la consulta a la tabla.
    $consulta = $db->prepare("SELECT dni, nombre, apellido1, apellido2, login, email, familia, activo, tutoria "
            . "FROM profesores "
            . "ORDER BY apellido1, apellido2, nombre ");
    $consulta->execute();
    // Almacenamos los resultados en un array asociativo.
    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    // Devolvemos ese array asociativo como un string JSON.
    echo json_encode($resultados);
});


$app->get('/profesores/{dni}', function($request, $response, $args) use($db) {
    // Va a devolver un objeto JSON con los datos de usuarios.
    // Preparamos la consulta a la tabla.
    // En PDO los parámetros para las consultas se pasan con :nombreparametro (casualmente 
    // coincide con el método usado por Slim).
    // No confundir con el parámetro :dni que si queremos usarlo tendríamos 
    // que hacerlo con la variable $dni
    $consulta = $db->prepare("SELECT dni, nombre, apellido1, apellido2, login, email, familia, activo, tutoria "
            . "FROM profesores "
            . "WHERE dni=:param1");

    // En el execute es dónde asociamos el :param1 con el valor que le toque.
    $consulta->execute(array(':param1' => $args['dni']));

    // Almacenamos los resultados en un array asociativo.
    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

    // Devolvemos ese array asociativo como un string JSON.
    echo json_encode($resultados[0]); //quiero un elemento del array
});

$app->put('/profesores/{dni}', function($request, $response, $args) use($db) {
    $data = $request->getParsedBody();
    $sql = "UPDATE profesores SET "
            . "dni = ?,"
            . "nombre = ?,"
            . "apellido1 = ?,"
            . "apellido2 = ?,"
            . "login = ?,"
            . "email = ?,"
            . "familia = ?,"
            . "activo = ?,"
            . "tutoria = ? "
            . "WHERE dni = ?";
    $consulta = $db->prepare($sql);
    // En el execute es dónde asociamos el :param1 con el valor que le toque.
    $consulta->execute(array(
        $data['dni'],
        $data['nombre'],
        $data['apellido1'],
        $data['apellido2'],
        $data['login'],
        $data['email'],
        $data['familia'],
        $data['activo'],
        $data['tutoria'],
        $data['dni']
    ));
});

$app->post('/profesores', function($request, $response, $args) use($db) {
    $data = $request->getParsedBody();
    $sql = "INSERT INTO profesores SET "
            . "dni = ?,"
            . "nombre = ?,"
            . "apellido1 = ?,"
            . "apellido2 = ?,"
            . "login = ?,"
            . "email = ?,"
            . "familia = ?,"
            . "activo = ?,"
            . "tutoria = ? ";
    $consulta = $db->prepare($sql);
    // En el execute es dónde asociamos el :param1 con el valor que le toque.
    $consulta->execute(array(
        $data['dni'],
        $data['nombre'],
        $data['apellido1'],
        $data['apellido2'],
        $data['login'],
        $data['email'],
        $data['familia'],
        $data['activo'],
        $data['tutoria']
    ));
    
    //Creamos una línea por profesor nuevo en el ficher swap/swap.txt
    if($fp=fopen('swap/swap.txt',a)){
        fwrite($fp,$data['login']."\n");
        fclose($fp);
    }
    
});

$app->run();
