CREATE DATABASE proyecto;


USE proyecto;


CREATE TABLE empleados (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(255) NOT NULL,

    email VARCHAR(255) UNIQUE NOT NULL,

    password VARCHAR(255) NOT NULL,

    nivel INT NOT NULL

);


CREATE TABLE proyectos (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(255) NOT NULL,

    descripcion TEXT,

    fecha_inicio DATE,

    fecha_fin DATE,

    empleado_id INT,

    FOREIGN KEY (empleado_id) REFERENCES empleados(id)

);