CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    edad INT,
    sexo VARCHAR(10),
    fumador BOOLEAN,
    mascotas BOOLEAN,
    limpieza INT, -- del 1 al 5
    sociabilidad INT, -- del 1 al 5
    presupuesto INT,
    horario VARCHAR(20),
    zona_deseada VARCHAR(100),
    preferencia_edad_min INT,
    preferencia_edad_max INT,
    preferencia_sexo VARCHAR(10)
);

INSERT INTO usuarios (nombre, edad, sexo, fumador, mascotas, limpieza, sociabilidad, presupuesto, horario, zona_deseada, preferencia_edad_min, preferencia_edad_max, preferencia_sexo)
VALUES 
('Carlos', 25, 'hombre', 0, 1, 4, 3, 500, 'diurno', 'Madrid', 22, 30, 'mujer'),
('Lucía', 24, 'mujer', 0, 1, 5, 4, 500, 'diurno', 'Madrid', 20, 28, 'indiferente'),
('Pedro', 30, 'hombre', 1, 0, 2, 2, 600, 'nocturno', 'Madrid', 25, 35, 'mujer');