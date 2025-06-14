-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS gestion_citas_medicas;
USE gestion_citas_medicas;

-- Tabla de Especialidades
CREATE TABLE especialidades (
    id_especialidad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de Médicos
CREATE TABLE medicos (
    id_medico INT AUTO_INCREMENT PRIMARY KEY,
    id_especialidad INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    cedula_profesional VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_especialidad) REFERENCES especialidades(id_especialidad)
);

-- Tabla de Pacientes
CREATE TABLE pacientes (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    genero ENUM('M', 'F', 'Otro') NOT NULL,
    email VARCHAR(100) UNIQUE,
    telefono VARCHAR(20) NOT NULL,
    direccion TEXT,
    password VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de Horarios Disponibles
CREATE TABLE horarios_disponibles (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    id_medico INT NOT NULL,
    dia_semana ENUM('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_medico) REFERENCES medicos(id_medico),
    UNIQUE KEY unique_horario_medico (id_medico, dia_semana, hora_inicio)
);

-- Tabla de Citas
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

-- Tabla de Historiales Clínicos
CREATE TABLE historiales_clinicos (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_cita INT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    diagnostico TEXT NOT NULL,
    tratamiento TEXT,
    observaciones TEXT,
    id_medico INT NOT NULL,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente),
    FOREIGN KEY (id_cita) REFERENCES citas(id_cita),
    FOREIGN KEY (id_medico) REFERENCES medicos(id_medico)
);

-- Tabla de Medicamentos/Tratamientos
CREATE TABLE medicamentos (
    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de relación entre historiales y medicamentos
CREATE TABLE historial_medicamentos (
    id_historial INT NOT NULL,
    id_medicamento INT NOT NULL,
    dosis VARCHAR(100) NOT NULL,
    frecuencia VARCHAR(100) NOT NULL,
    duracion VARCHAR(100) NOT NULL,
    observaciones TEXT,
    PRIMARY KEY (id_historial, id_medicamento),
    FOREIGN KEY (id_historial) REFERENCES historiales_clinicos(id_historial),
    FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento)
);

-- Tabla de Pagos
CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_cita INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM('Efectivo', 'Tarjeta', 'Transferencia', 'Seguro médico') NOT NULL,
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('Pendiente', 'Pagado', 'Cancelado') DEFAULT 'Pendiente',
    referencia VARCHAR(100),
    FOREIGN KEY (id_cita) REFERENCES citas(id_cita)
);

-- Tabla de Roles para seguridad
CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
);

-- Tabla de Usuarios del sistema
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    id_rol INT NOT NULL,
    id_medico INT NULL,
    id_paciente INT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol),
    FOREIGN KEY (id_medico) REFERENCES medicos(id_medico),
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente)
);

-- Tabla de Logs de acceso
CREATE TABLE logs_acceso (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    accion VARCHAR(255) NOT NULL,
    ip VARCHAR(50),
    detalles TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- INSERTS PARA POBLAR LAS TABLAS

-- Insertar Especialidades
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

-- Insertar Médicos
INSERT INTO medicos (id_especialidad, nombre, apellidos, cedula_profesional, email, telefono, password) VALUES
(1, 'Carlos', 'González Pérez', 'MG-12345', 'carlos.gonzalez@clinica.com', '555-123-4567', '$2y$10$abcdefghijklmnopqrstuv'),
(2, 'Laura', 'Martínez Rodríguez', 'CA-23456', 'laura.martinez@clinica.com', '555-234-5678', '$2y$10$abcdefghijklmnopqrstuv'),
(3, 'Ricardo', 'López Sánchez', 'PE-34567', 'ricardo.lopez@clinica.com', '555-345-6789', '$2y$10$abcdefghijklmnopqrstuv'),
(4, 'Sofía', 'Ramírez García', 'DE-45678', 'sofia.ramirez@clinica.com', '555-456-7890', '$2y$10$abcdefghijklmnopqrstuv'),
(5, 'Miguel', 'Torres Díaz', 'GI-56789', 'miguel.torres@clinica.com', '555-567-8901', '$2y$10$abcdefghijklmnopqrstuv'),
(6, 'Ana', 'Fernández López', 'TR-67890', 'ana.fernandez@clinica.com', '555-678-9012', '$2y$10$abcdefghijklmnopqrstuv'),
(7, 'Javier', 'Gómez Ruiz', 'NE-78901', 'javier.gomez@clinica.com', '555-789-0123', '$2y$10$abcdefghijklmnopqrstuv'),
(8, 'Elena', 'Castro Moreno', 'OF-89012', 'elena.castro@clinica.com', '555-890-1234', '$2y$10$abcdefghijklmnopqrstuv'),
(9, 'Daniel', 'Vargas Jiménez', 'PS-90123', 'daniel.vargas@clinica.com', '555-901-2345', '$2y$10$abcdefghijklmnopqrstuv'),
(10, 'Patricia', 'Ortega Navarro', 'OD-01234', 'patricia.ortega@clinica.com', '555-012-3456', '$2y$10$abcdefghijklmnopqrstuv');

-- Insertar Pacientes
INSERT INTO pacientes (nombre, apellidos, fecha_nacimiento, genero, email, telefono, direccion, password) VALUES
('Juan', 'Pérez Soto', '1985-03-15', 'M', 'juan.perez@email.com', '555-111-2222', 'Calle Principal 123', '$2y$10$abcdefghijklmnopqrstuv'),
('María', 'García López', '1990-07-20', 'F', 'maria.garcia@email.com', '555-222-3333', 'Avenida Central 456', '$2y$10$abcdefghijklmnopqrstuv'),
('Pedro', 'Martínez Gómez', '1978-12-03', 'M', 'pedro.martinez@email.com', '555-333-4444', 'Boulevard Norte 789', '$2y$10$abcdefghijklmnopqrstuv'),
('Ana', 'López Hernández', '1995-05-25', 'F', 'ana.lopez@email.com', '555-444-5555', 'Calle Sur 234', '$2y$10$abcdefghijklmnopqrstuv'),
('Luis', 'Rodríguez Castro', '1982-09-10', 'M', 'luis.rodriguez@email.com', '555-555-6666', 'Avenida Este 567', '$2y$10$abcdefghijklmnopqrstuv'),
('Carmen', 'Torres Vargas', '1973-06-28', 'F', 'carmen.torres@email.com', '555-666-7777', 'Calle Poniente 890', '$2y$10$abcdefghijklmnopqrstuv'),
('Roberto', 'Díaz Morales', '1988-02-12', 'M', 'roberto.diaz@email.com', '555-777-8888', 'Boulevard Sur 123', '$2y$10$abcdefghijklmnopqrstuv'),
('Laura', 'Fernández Rivas', '1992-11-07', 'F', 'laura.fernandez@email.com', '555-888-9999', 'Avenida Norte 456', '$2y$10$abcdefghijklmnopqrstuv'),
('Miguel', 'Sánchez Ortega', '1980-04-18', 'M', 'miguel.sanchez@email.com', '555-999-0000', 'Calle Oriente 789', '$2y$10$abcdefghijklmnopqrstuv'),
('Sofía', 'Ramírez Mendoza', '1997-08-30', 'F', 'sofia.ramirez@email.com', '555-000-1111', 'Boulevard Oeste 234', '$2y$10$abcdefghijklmnopqrstuv'),
('Jorge', 'Gómez Estrada', '1975-01-22', 'M', 'jorge.gomez@email.com', '555-112-2233', 'Avenida Principal 567', '$2y$10$abcdefghijklmnopqrstuv'),
('Claudia', 'Jiménez Rojas', '1991-10-05', 'F', 'claudia.jimenez@email.com', '555-223-3344', 'Calle Central 890', '$2y$10$abcdefghijklmnopqrstuv'),
('Fernando', 'Ortiz Castillo', '1983-07-14', 'M', 'fernando.ortiz@email.com', '555-334-4455', 'Boulevard Principal 123', '$2y$10$abcdefghijklmnopqrstuv'),
('Isabel', 'Núñez Pacheco', '1979-03-27', 'F', 'isabel.nunez@email.com', '555-445-5566', 'Avenida Sur 456', '$2y$10$abcdefghijklmnopqrstuv'),
('Raúl', 'Herrera Méndez', '1994-06-09', 'M', 'raul.herrera@email.com', '555-556-6677', 'Calle Norte 789', '$2y$10$abcdefghijklmnopqrstuv');

-- Insertar Horarios Disponibles
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

-- Insertar Citas
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

-- Insertar Medicamentos
INSERT INTO medicamentos (nombre, descripcion) VALUES
('Paracetamol', 'Analgésico y antipirético'),
('Ibuprofeno', 'Antiinflamatorio no esteroideo'),
('Amoxicilina', 'Antibiótico betalactámico'),
('Omeprazol', 'Inhibidor de la bomba de protones'),
('Loratadina', 'Antihistamínico'),
('Atorvastatina', 'Estatina para reducir el colesterol'),
('Metformina', 'Antidiabético oral'),
('Losartán', 'Antagonista del receptor de angiotensina II'),
('Alprazolam', 'Ansiolítico'),
('Levotiroxina', 'Hormona tiroidea sintética');

-- Insertar Historiales Clínicos para citas completadas
INSERT INTO historiales_clinicos (id_paciente, id_cita, diagnostico, tratamiento, observaciones, id_medico) VALUES
(1, 11, 'Ansiedad leve', 'Reposo y técnicas de relajación', 'Programar seguimiento en 1 mes', 2),
(2, 12, 'Desarrollo normal', 'Continuar con plan de alimentación', 'Próximo control en 3 meses', 3),
(3, 13, 'Hipertensión controlada', 'Continuar con medicación actual', 'Reducir consumo de sal', 1),
(4, 14, 'Examen ginecológico normal', 'No requiere tratamiento', 'Próxima revisión anual', 5),
(5, 15, 'Dermatitis atópica en remisión', 'Continuar con crema hidratante', 'Evitar alérgenos conocidos', 4);

-- Insertar relación entre historiales y medicamentos
INSERT INTO historial_medicamentos (id_historial, id_medicamento, dosis, frecuencia, duracion, observaciones) VALUES
(1, 9, '0.5mg', 'Una vez al día', '2 semanas', 'Tomar antes de dormir'),
(2, 2, '100mg/5ml', 'Cada 8 horas si hay fiebre', '3 días', 'Administrar después de las comidas'),
(3, 8, '50mg', 'Una vez al día', 'Indefinido', 'Tomar por la mañana'),
(5, 5, '10mg', 'Una vez al día', '1 mes', 'Tomar por la mañana');

-- Insertar Pagos
INSERT INTO pagos (id_cita, monto, metodo_pago, estado, referencia) VALUES
(11, 800.00, 'Efectivo', 'Pagado', 'EFECTIVO-20250522-001'),
(12, 900.00, 'Tarjeta', 'Pagado', 'TARJ-20250521-002'),
(13, 600.00, 'Transferencia', 'Pagado', 'TRANS-20250522-003'),
(14, 1200.00, 'Seguro médico', 'Pagado', 'SEG-20250523-004'),
(15, 800.00, 'Efectivo', 'Pagado', 'EFECTIVO-20250524-005'),
(1, 600.00, 'Efectivo', 'Pendiente', NULL),
(2, 900.00, 'Tarjeta', 'Pendiente', NULL),
(3, 600.00, 'Seguro médico', 'Pendiente', NULL),
(4, 800.00, 'Transferencia', 'Pendiente', NULL),
(5, 800.00, 'Efectivo', 'Pendiente', NULL);

-- Insertar Roles
INSERT INTO roles (nombre, descripcion) VALUES
('admin', 'Administrador del sistema con acceso total'),
('medico', 'Médico con acceso a sus pacientes y citas'),
('recepcionista', 'Personal para gestión de citas y pacientes'),
('paciente', 'Acceso limitado a su información y citas');

-- Insertar Usuarios
INSERT INTO usuarios (username, password, email, id_rol, id_medico, id_paciente) VALUES
('admin', '$2y$10$abcdefghijklmnopqrstuv', 'admin@clinica.com', 1, NULL, NULL),
('carlos.gonzalez', '$2y$10$abcdefghijklmnopqrstuv', 'carlos.gonzalez@clinica.com', 2, 1, NULL),
('laura.martinez', '$2y$10$abcdefghijklmnopqrstuv', 'laura.martinez@clinica.com', 2, 2, NULL),
('recepcion1', '$2y$10$abcdefghijklmnopqrstuv', 'recepcion1@clinica.com', 3, NULL, NULL),
('juan.perez', '$2y$10$abcdefghijklmnopqrstuv', 'juan.perez@email.com', 4, NULL, 1),
('maria.garcia', '$2y$10$abcdefghijklmnopqrstuv', 'maria.garcia@email.com', 4, NULL, 2);

-- Insertar Logs de acceso
INSERT INTO logs_acceso (id_usuario, accion, ip, detalles) VALUES
(1, 'Inicio de sesión', '192.168.1.100', 'Acceso desde navegador Chrome'),
(2, 'Inicio de sesión', '192.168.1.101', 'Acceso desde navegador Firefox'),
(3, 'Inicio de sesión', '192.168.1.102', 'Acceso desde navegador Safari'),
(1, 'Consulta de citas', '192.168.1.100', 'Consulta de citas del día'),
(2, 'Consulta de paciente', '192.168.1.101', 'Consulta historial del paciente ID 1'),
(4, 'Registro de cita', '192.168.1.103', 'Registro de nueva cita ID 10');