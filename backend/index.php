<?php
require '../vendor/autoload.php';


$app = new \Slim\App;

    
$app->post('/register', function ($request, $response, $args) {

    $data = $request->getParsedBody();
    $db = new PDO('mysql:host=localhost;dbname=proyecto', 'root', '');
    $sql = "INSERT INTO empleados (nombre, email, password, nivel) VALUES (:nombre, :email, :password, :nivel)";


    try {

        $stmt = $db->prepare($sql);


        // Sanea los datos de entrada

        $nombre = filter_var($data['nombre'], FILTER_SANITIZE_STRING);

        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);

        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $nivel = filter_var($data['nivel'], FILTER_VALIDATE_INT);


        // Utiliza bindValue en lugar de bindParam

        $stmt->bindValue(':nombre', $nombre);

        $stmt->bindValue(':email', $email);

        $stmt->bindValue(':password', $password);

        $stmt->bindValue(':nivel', $nivel);


        $stmt->execute();


        return $response->withJson(['message' => 'Registro exitoso', 'success' => true]);


    } catch (PDOException $e) {

        return $response->withJson(['message' => 'Error en el registro: ' . $e->getMessage()]);

    }

});


$app->post('/login', function ($request, $response, $args) {

    $data = $request->getParsedBody();
    $db = new PDO('mysql:host=localhost;dbname=proyecto', 'root', '');
    $sql = "SELECT * FROM empleados WHERE email = :email AND password = :password";


    try {

        $stmt = $db->prepare($sql);


        // Sanea los datos de entrada

        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);

        $password = $data['password'];


        // Utiliza bindValue en lugar de bindParam

        $stmt->bindValue(':email', $email);

        $stmt->bindValue(':password', $password);


        $stmt->execute();


        $empleado = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($empleado) {

            // Hashea la contraseña antes de devolver los datos del usuario

            $empleado['password'] = password_hash($empleado['password'], PASSWORD_DEFAULT);

            return $response->withJson(['message' => 'Inicio de sesión exitoso', 'empleado' => $empleado, 'success' => true]);

        } else {

            return $response->withJson(['message' => 'Error en el inicio de sesión: Usuario o contraseña incorrectos']);

        }


    } catch (PDOException $e) {

        return $response->withJson(['message' => 'Error en el inicio de sesión: ' . $e->getMessage()]);

    }

});

$app->get('/empleados', function ($request, $response, $args) {

    $db = new PDO('mysql:host=;dbname=proyecto', 'root', '');
    $db = new PDO('mysql:host=localhost;dbname=proyecto', 'root', '');
    $sql = "SELECT * FROM empleados";


    try {

        $stmt = $db->query($sql);


        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // Sanea los datos de salida

        $empleados = array_map('htmlspecialchars', $empleados);


        return $response->withJson(['empleados' => $empleados, 'success' => true]);


    } catch (PDOException $e) {

        return $response->withJson(['message' => 'Error al obtener empleados: ' . $e->getMessage()]);

    }

});



$app->get('/proyectos/{id}', function ($request, $response, $args) {

    $id = (int) $args['id']; // Convertir a entero para evitar ataques de inyección SQL
    $db = new PDO('mysql:host=localhost;dbname=proyecto', 'root', '');
    $sql = "SELECT proyectos.*, empleados.nombre AS empleado_nombre FROM proyectos INNER JOIN empleados ON proyectos.empleado_id = empleados.id WHERE proyectos.id = :id";


    try {

        $db = new PDO('mysql:host=localhost;dbname=proyecto', 'usuario', 'contraseña');


        $stmt = $db->prepare($sql);


        $stmt->bindParam(':id', $id);


        $stmt->execute();


        $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($proyecto) {

            // Sanea los datos de salida

            $proyecto = array_map('htmlspecialchars', $proyecto);

            return $response->withJson(['proyecto' => $proyecto]);

        } else {

            return $response->withJson(['message' => 'Proyecto no encontrado', 'success' => true]);

        }


    } catch (PDOException $e) {

        return $response->withJson(['message' => 'Error al obtener proyecto: ' . $e->getMessage()]);

    }

});










$app->delete('/proyectos/{id}', function ($request, $response, $args) { 
    $id = $args['id'];  
    $db = new PDO('mysql:host=localhost;dbname=proyecto', 'root',   '');
    $sql = "DELETE FROM proyectos WHERE id = :id"; 
    try { 
        $db = new PDO('mysql:host=localhost;dbname=proyecto', 'usuario', 'contraseña'); 
        $stmt = $db->prepare($sql); 
        $stmt->bindParam(':id', $id); 
        $stmt->execute(); 
        if ($stmt->rowCount() > 0) { 
            return $response->withJson(['message' => 'Proyecto eliminado exitosamente', 'success' => true]); 
        } else { 
            return $response->withJson(['message' => 'Proyecto no encontrado']); 
        } 
    } catch (PDOException $e) { 
        return $response->withJson(['message' => 'Error al eliminar proyecto: ' . $e->getMessage()]); } 
    });


$app->run();
?>