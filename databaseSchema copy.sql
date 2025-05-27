DROP DATABASE IF EXISTS gestion_citas_medicas;

CREATE DATABASE IF NOT EXISTS gestion_citas_medicas;
USE gestion_citas_medicas;

CREATE TABLE especialidades (
    id_especialidad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

CREATE TABLE medicos (
    id_medico INT AUTO_INCREMENT PRIMARY KEY,
    id_especialidad INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    cedula_profesional VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_especialidad) REFERENCES especialidades(id_especialidad)
);

CREATE TABLE pacientes (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    genero ENUM('M', 'F', 'Otro') NOT NULL,
    email VARCHAR(100) UNIQUE,
    telefono VARCHAR(20) NOT NULL,
    direccion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE horarios_disponibles (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    id_medico INT NOT NULL,
    dia_semana ENUM('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    FOREIGN KEY (id_medico) REFERENCES medicos(id_medico),
    UNIQUE KEY unique_horario_medico (id_medico, dia_semana, hora_inicio)
);

CREATE TABLE citas (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_medico INT NOT NULL,
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    motivo TEXT,
    estado ENUM('Programada', 'Confirmada', 'Completada', 'Cancelada') DEFAULT 'Programada',
    observaciones TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente),
    FOREIGN KEY (id_medico) REFERENCES medicos(id_medico)
);

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    claveAPI VARCHAR(255),
    id_medico INT NULL,
    id_paciente INT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME,
    FOREIGN KEY (id_medico) REFERENCES medicos(id_medico),
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente)
);

-- Los inserts permanecen igual
INSERT INTO especialidades (nombre, descripcion) VALUES
('Medicina General', 'Atención médica primaria y preventiva'),
('Cardiología', 'Especialidad en el sistema cardiovascular'),
('Pediatría', 'Atención médica para niños y adolescentes'),
('Dermatología', 'Especialidad en enfermedades de la piel'),
('Ginecología', 'Salud reproductiva femenina'),
('Traumatología', 'Especialidad en lesiones del sistema locomotor'),
('Neurología', 'Especialidad en trastornos del sistema nervioso'),
('Oftalmología', 'Especialidad en salud visual'),
('Psiquiatría', 'Especialidad en salud mental'),
('Odontología', 'Especialidad en salud bucodental');

INSERT INTO medicos (id_especialidad, nombre, apellidos, cedula_profesional, email, telefono) VALUES
(1, 'Carlos', 'González Pérez', 'MG-12345', 'carlos.gonzalez@clinica.com', '555-123-4567'),
(2, 'Laura', 'Martínez Rodríguez', 'CA-23456', 'laura.martinez@clinica.com', '555-234-5678'),
(3, 'Ricardo', 'López Sánchez', 'PE-34567', 'ricardo.lopez@clinica.com', '555-345-6789'),
(4, 'Sofía', 'Ramírez García', 'DE-45678', 'sofia.ramirez@clinica.com', '555-456-7890'),
(5, 'Miguel', 'Torres Díaz', 'GI-56789', 'miguel.torres@clinica.com', '555-567-8901'),
(6, 'Ana', 'Fernández López', 'TR-67890', 'ana.fernandez@clinica.com', '555-678-9012'),
(7, 'Javier', 'Gómez Ruiz', 'NE-78901', 'javier.gomez@clinica.com', '555-789-0123'),
(8, 'Elena', 'Castro Moreno', 'OF-89012', 'elena.castro@clinica.com', '555-890-1234'),
(9, 'Daniel', 'Vargas Jiménez', 'PS-90123', 'daniel.vargas@clinica.com', '555-901-2345'),
(10, 'Patricia', 'Ortega Navarro', 'OD-01234', 'patricia.ortega@clinica.com', '555-012-3456');

INSERT INTO pacientes (nombre, apellidos, fecha_nacimiento, genero, email, telefono, direccion) VALUES
('Juan', 'Pérez Soto', '1985-03-15', 'M', 'juan.perez@email.com', '555-111-2222', 'Calle Principal 123'),
('María', 'García López', '1990-07-20', 'F', 'maria.garcia@email.com', '555-222-3333', 'Avenida Central 456'),
('Pedro', 'Martínez Gómez', '1978-12-03', 'M', 'pedro.martinez@email.com', '555-333-4444', 'Boulevard Norte 789'),
('Ana', 'López Hernández', '1995-05-25', 'F', 'ana.lopez@email.com', '555-444-5555', 'Calle Sur 234'),
('Luis', 'Rodríguez Castro', '1982-09-10', 'M', 'luis.rodriguez@email.com', '555-555-6666', 'Avenida Este 567'),
('Carmen', 'Torres Vargas', '1973-06-28', 'F', 'carmen.torres@email.com', '555-666-7777', 'Calle Poniente 890'),
('Roberto', 'Díaz Morales', '1988-02-12', 'M', 'roberto.diaz@email.com', '555-777-8888', 'Boulevard Sur 123'),
('Laura', 'Fernández Rivas', '1992-11-07', 'F', 'laura.fernandez@email.com', '555-888-9999', 'Avenida Norte 456'),
('Miguel', 'Sánchez Ortega', '1980-04-18', 'M', 'miguel.sanchez@email.com', '555-999-0000', 'Calle Oriente 789'),
('Sofía', 'Ramírez Mendoza', '1997-08-30', 'F', 'sofia.ramirez@email.com', '555-000-1111', 'Boulevard Oeste 234'),
('Jorge', 'Gómez Estrada', '1975-01-22', 'M', 'jorge.gomez@email.com', '555-112-2233', 'Avenida Principal 567'),
('Claudia', 'Jiménez Rojas', '1991-10-05', 'F', 'claudia.jimenez@email.com', '555-223-3344', 'Calle Central 890'),
('Fernando', 'Ortiz Castillo', '1983-07-14', 'M', 'fernando.ortiz@email.com', '555-334-4455', 'Boulevard Principal 123'),
('Isabel', 'Núñez Pacheco', '1979-03-27', 'F', 'isabel.nunez@email.com', '555-445-5566', 'Avenida Sur 456'),
('Raúl', 'Herrera Méndez', '1994-06-09', 'M', 'raul.herrera@email.com', '555-556-6677', 'Calle Norte 789');

INSERT INTO horarios_disponibles (id_medico, dia_semana, hora_inicio, hora_fin) VALUES
(1, 'Lunes', '08:00:00', '14:00:00'),
(1, 'Miércoles', '08:00:00', '14:00:00'),
(1, 'Viernes', '08:00:00', '12:00:00'),
(2, 'Martes', '09:00:00', '15:00:00'),
(2, 'Jueves', '09:00:00', '15:00:00'),
(3, 'Lunes', '14:00:00', '20:00:00'),
(3, 'Miércoles', '14:00:00', '20:00:00'),
(3, 'Viernes', '14:00:00', '18:00:00'),
(4, 'Martes', '08:00:00', '16:00:00'),
(4, 'Jueves', '08:00:00', '16:00:00'),
(5, 'Lunes', '07:00:00', '13:00:00'),
(5, 'Martes', '14:00:00', '20:00:00'),
(5, 'Viernes', '08:00:00', '14:00:00'),
(6, 'Miércoles', '09:00:00', '17:00:00'),
(6, 'Sábado', '08:00:00', '13:00:00'),
(7, 'Lunes', '12:00:00', '20:00:00'),
(7, 'Jueves', '12:00:00', '20:00:00'),
(8, 'Martes', '07:00:00', '15:00:00'),
(8, 'Viernes', '07:00:00', '15:00:00'),
(9, 'Lunes', '16:00:00', '20:00:00'),
(9, 'Miércoles', '16:00:00', '20:00:00'),
(9, 'Viernes', '16:00:00', '20:00:00'),
(10, 'Martes', '08:00:00', '13:00:00'),
(10, 'Jueves', '14:00:00', '19:00:00'),
(10, 'Sábado', '09:00:00', '14:00:00');

INSERT INTO citas (id_paciente, id_medico, fecha, hora_inicio, hora_fin, motivo, estado) VALUES
(1, 1, '2025-05-26', '09:00:00', '09:30:00', 'Chequeo general', 'Programada'),
(2, 3, '2025-05-26', '15:00:00', '15:30:00', 'Dolor de garganta', 'Confirmada'),
(3, 5, '2025-05-27', '08:00:00', '08:30:00', 'Control de presión arterial', 'Programada'),
(4, 2, '2025-05-27', '10:00:00', '10:30:00', 'Consulta de rutina', 'Confirmada'),
(5, 4, '2025-05-28', '09:00:00', '09:30:00', 'Alergia en la piel', 'Programada'),
(6, 6, '2025-05-28', '10:00:00', '10:30:00', 'Dolor en la rodilla', 'Confirmada'),
(7, 8, '2025-05-29', '08:00:00', '08:30:00', 'Revisión de vista', 'Programada'),
(8, 7, '2025-05-29', '14:00:00', '14:30:00', 'Dolor de cabeza frecuente', 'Confirmada'),
(9, 9, '2025-05-30', '17:00:00', '17:30:00', 'Ansiedad', 'Programada'),
(10, 10, '2025-05-30', '09:00:00', '09:30:00', 'Revisión dental', 'Confirmada'),
(1, 2, '2025-05-20', '11:00:00', '11:30:00', 'Consulta por dolor en el pecho', 'Completada'),
(2, 3, '2025-05-21', '16:00:00', '16:30:00', 'Control de crecimiento', 'Completada'),
(3, 1, '2025-05-22', '10:00:00', '10:30:00', 'Seguimiento hipertensión', 'Completada'),
(4, 5, '2025-05-23', '09:00:00', '09:30:00', 'Consulta ginecológica', 'Completada'),
(5, 4, '2025-05-24', '11:00:00', '11:30:00', 'Seguimiento tratamiento dermatológico', 'Completada');

INSERT INTO usuarios (username, password, email, claveAPI, id_medico, id_paciente) VALUES
('admin', '$2y$10$iMa3ZbKpYl4HwtxTvH0/JezjKSd1Q2VEjAVlVa62.NT5eZT8zGtKK', 'admin@clinica.com', 'APIKEY-ADMIN-001', NULL, NULL),
('carlos.gonzalez', '$2y$10$iMa3ZbKpYl4HwtxTvH0/JezjKSd1Q2VEjAVlVa62.NT5eZT8zGtKK', 'carlos.gonzalez@clinica.com', 'APIKEY-MEDICO-001', 1, NULL),
('laura.martinez', '$2y$10$iMa3ZbKpYl4HwtxTvH0/JezjKSd1Q2VEjAVlVa62.NT5eZT8zGtKK', 'laura.martinez@clinica.com', 'APIKEY-MEDICO-002', 2, NULL),
('recepcion1', '$2y$10$iMa3ZbKpYl4HwtxTvH0/JezjKSd1Q2VEjAVlVa62.NT5eZT8zGtKK', 'recepcion1@clinica.com', 'APIKEY-RECEP-001', NULL, NULL),
('juan.perez', '$2y$10$iMa3ZbKpYl4HwtxTvH0/JezjKSd1Q2VEjAVlVa62.NT5eZT8zGtKK', 'juan.perez@email.com', 'APIKEY-PACIENTE-001', NULL, 1),
('maria.garcia', '$2y$10$iMa3ZbKpYl4HwtxTvH0/JezjKSd1Q2VEjAVlVa62.NT5eZT8zGtKK', 'maria.garcia@email.com', 'APIKEY-PACIENTE-002', NULL, 2);
