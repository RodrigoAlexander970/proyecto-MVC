# 🏥 API de Gestión Médica

Esta API permite gestionar médicos, pacientes, especialidades, horarios y citas médicas. Utiliza autenticación mediante Bearer Token y todas las solicitudes deben tener el encabezado `Content-Type: application/json`.

- Tipo de autenticación: Bearer Token
- Content-Type: application/json

## Recursos
- [Médicos](#médicos)
- [Especialidades](#especialidades)
- [Pacientes](#pacientes)
- [Horarios](#horarios)
- [Citas](#citas)
- [Usuarios](#usuarios)
- [Autenticación](#autenticación)

## Médicos
### Endpoints
- [`GET /medicos`](#get-medicos)
- [`GET /medicos/{id}`](#get-medicosid)
- [`GET /medicos/{id}/horarios`](#get-medicosidhorarios)
- [`GET /medicos/{id}/citas`](#get-medicosidcitas)
- [`GET /medicos/{id}/pacientes`](#get-medicosidpacientes)
- [`POST /medicos`](#post-medicos)
- [`PUT /medicos/{id}`](#put-medicosid)
- [`DELETE /medicos/{id}`](#delete-medicosid)
###  GET /medicos
Listar todos los médicos.
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Médicos obtenidos correctamente",
    "data": [
        {
            "id_medico": 1,
            "id_especialidad": 1,
            "nombre": "Carlos",
            "apellidos": "González Pérezaa",
            "cedula_profesional": "MG-12345",
            "email": "carlos.gonzalez@clinica.com",
            "telefono": "555-123-4567",
            "fecha_registro": "2025-05-26 18:56:30"
        },
        {
            "id_medico": 2,
            "id_especialidad": 2,
            "nombre": "Laura",
            "apellidos": "Martínez Rodríguez",
            "cedula_profesional": "CA-23456",
            "email": "laura.martinez@clinica.com",
            "telefono": "555-234-5678",
            "fecha_registro": "2025-05-26 18:56:30"
        }
    ]
}
```

### GET /medicos/{id}
Obtener información de un médico específico
### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Médico obtenido correctamente",
    "data": {
        "id_medico": 2,
        "id_especialidad": 2,
        "nombre": "Laura",
        "apellidos": "Martínez Rodríguez",
        "cedula_profesional": "CA-23456",
        "email": "laura.martinez@clinica.com",
        "telefono": "555-234-5678",
        "fecha_registro": "2025-05-26 18:56:30"
    }
}
```
### GET /medicos/{id}/horarios
Obtener los horarios de un médico en específico
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Horarios obtenidos correctamente",
    "data": [
        {
            "id_horario": 4,
            "id_medico": 2,
            "dia_semana": "Martes",
            "hora_inicio": "09:00:00",
            "hora_fin": "15:00:00"
        },
        {
            "id_horario": 5,
            "id_medico": 2,
            "dia_semana": "Jueves",
            "hora_inicio": "09:00:00",
            "hora_fin": "15:00:00"
        }
    ]
}
```

#### Errores
No existe el medico
```json
{
    "success": false,
    "status": 404,
    "message": "Médico no encontrado"
}
```

### GET /medicos/{id}/citas
Obtener las citas de un medico
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Citas obtenidas correctamente",
    "data": [
        {
            "id_cita": 1,
            "id_paciente": 1,
            "id_medico": 1,
            "fecha": "2025-05-26",
            "hora_inicio": "09:00:00",
            "hora_fin": "09:30:00",
            "motivo": "Chequeo general",
            "estado": "Programada",
            "observaciones": null,
            "fecha_registro": "2025-05-26 18:56:30"
        },
        {
            "id_cita": 13,
            "id_paciente": 3,
            "id_medico": 1,
            "fecha": "2025-05-22",
            "hora_inicio": "10:00:00",
            "hora_fin": "10:30:00",
            "motivo": "Seguimiento hipertensión",
            "estado": "Completada",
            "observaciones": null,
            "fecha_registro": "2025-05-26 18:56:30"
        }
    ]
}
```
#### Errores
No existe el medico
```json
{
    "success": false,
    "status": 404,
    "message": "Médico no encontrado"
}
```

### GET /medicos/{id}/pacientes
Listar pacientes de un médico específico  
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Pacientes conseguidos",
    "data": [
        {
            "id_paciente": 1,
            "nombre": "Juan",
            "apellidos": "Pérez Soto",
            "fecha_nacimiento": "1985-03-15",
            "genero": "M",
            "email": "juan.perez@email.com",
            "telefono": "555-111-2222",
            "direccion": "Calle Principal 123123123123",
            "fecha_registro": "2025-05-26 18:56:30"
        },
        {
            "id_paciente": 3,
            "nombre": "Pedro",
            "apellidos": "Martínez Gómez",
            "fecha_nacimiento": "1978-12-03",
            "genero": "M",
            "email": "pedro.martinez@email.com",
            "telefono": "555-333-4444",
            "direccion": "Boulevard Norte 789",
            "fecha_registro": "2025-05-26 18:56:30"
        }
    ]
}
```
#### Errores
No existe el medico
```json
{
    "success": false,
    "status": 404,
    "message": "Médico no encontrado"
}
```

### POST /medicos
Registrar un nuevo médico
#### Solicitud
```json
{
    "id_especialidad": 1,
    "nombre": "Nombre",
    "apellidos": "Apellidos",
    "cedula_profesional": "MG-12345SS",
    "email": "email@correo.com",
    "telefono": "123-456-7890"
}
```
Nota, no se puede tener duplicado en la base de datos los campos `cedula_profesional` o `email`

#### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Médico creado correctamente"
}
```

#### Errores
Datos duplicados
```json
{
    "success": false,
    "status": 409,
    "message": "Ya existe un registro con los mismos datos únicos."
}
```

### PUT /medicos/{id}
Actualizar información de un médico  
#### Solicitud
```json
{
    "id_especialidad": 1,
    "nombre": "Nombre",
    "apellidos": "Apellidos",
    "cedula_profesional": "MG-12345SS",
    "email": "email@correo.com",
    "telefono": "123-456-7890"
}
```
Nota, no se puede tener duplicado en la base los campos `cedula_profesional` o `email`

#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Médico actualizado correctamente"
}
```

#### Errores
Datos duplicados
```json
{
    "success": false,
    "status": 409,
    "message": "Error al actualizar el médico"
}
```
Medico no existente
```json
{
    "success": false,
    "status": 404,
    "message": "El medico no existe"
}
```

### DELETE /medicos/{id}
Eliminar un médico  
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Médico borrado correctamente"
}
```
#### Errores
Medico no existente
```json
{
    "success": false,
    "status": 404,
    "message": "El medico no existe"
}
```
Datos dependientes
```json
{
    "success": false,
    "status": 409,
    "message": "No se puede eliminar el registro porque existen registros relacionados."
}
```
---

## Especialidades
### Endpoints
- [`GET /especialidades`](#get-especialidades)
- [`GET /especialidades/{id}`](#get-especialidadesid)
- [`GET /especialidades/{id}/medicos`](#get-especialidadesidmedicos)
- [`POST /especialidades`](#post-especialidades)
- [`PUT /especialidades/{id}`](#put-especialidadesid)
- [`DELETE /especialidades/{id}`](#delete-especialidadesid)

### GET /especialidades
Listar todas las especialidades.

#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Especialidades obtenidas correctamente",
    "data": [
        {
            "id_especialidad": "1",
            "nombre": "Medicina General",
            "descripcion": "Atención médica primaria y preventiva"
        },
        {
            "id_especialidad": "2",
            "nombre": "Cardiología",
            "descripcion": "Especialidad en el sistema cardiovascular"
        },
        {
            "id_especialidad": "3",
            "nombre": "Pediatría",
            "descripcion": "Atención médica para niños y adolescentes"
        },
        {
            "id_especialidad": "4",
            "nombre": "Dermatología",
            "descripcion": "Especialidad en enfermedades de la piel"
        },
        {
            "id_especialidad": "5",
            "nombre": "Ginecología",
            "descripcion": "Salud reproductiva femenina"
        },
        {
            "id_especialidad": "6",
            "nombre": "Traumatología",
            "descripcion": "Especialidad en lesiones del sistema locomotor"
        },
        {
            "id_especialidad": "7",
            "nombre": "Neurología",
            "descripcion": "Especialidad en trastornos del sistema nervioso"
        },
        {
            "id_especialidad": "8",
            "nombre": "Oftalmología",
            "descripcion": "Especialidad en salud visual"
        },
        {
            "id_especialidad": "9",
            "nombre": "Psiquiatría",
            "descripcion": "Especialidad en salud mental"
        },
        {
            "id_especialidad": "10",
            "nombre": "Odontología",
            "descripcion": "Especialidad en salud bucodental"
        }
    ]
}
```
### GET /especialidades/{id}
Obtener información de una especialidad específica
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Especialidad obtenida correctamente",
    "data": {
        "id_especialidad": "1",
        "nombre": "Medicina General",
        "descripcion": "Atención médica primaria y preventiva"
    }
}
```
### GET /especialidades/{id}/medicos
Obtener los médicos de una especialidad específica
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Medicos obtenidos correctamente por especialidad",
    "data": [
        {
            "id_medico": "1",
            "id_especialidad": "1",
            "nombre": "Carlos",
            "apellidos": "González Pérez",
            "cedula_profesional": "MG-12345",
            "email": "carlos.gonzalez@clinica.com",
            "telefono": "555-123-4567",
            "fecha_registro": "2025-05-27 15:09:47"
        }
    ]
}
```

#### Errores
```json
No existe la especialidad
{
    "success": false,
    "status": 404,
    "message": "Especialidad no encontrada"
}
```

### POST /especialidades
Registrar una nueva especialidad
#### Solicitud
```json
{
    "id_especialidad": "11",
    "nombre": "Oftamologia",
    "descripcion": "Atención de los ojos"
}
```
#### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Especialidad creada correctamente"
}
```

#### Errores
Datos faltantes
```json
{
    "success": false,
    "status": 400,
    "message": "Datos JSON inválidos"
}
```

### PUT /especialidades/{id}
Actualizar información de una especialidad  
#### Solicitud
```json
{
    "id_especialidad": "11",
    "nombre": "Oftamologia",
    "descripcion": "Atención de los ojos y visuales, Nueva descripcion"
}
```

#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Especialidad actualizada correctamente"
}
```

#### Errores
Especialidad no existente
```json
{
    "success": false,
    "status": 404,
    "message": "Especialidad no encontrada"
}
```

### DELETE /especialidades/{id}
Eliminar una especialidad  
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Especialidad borrada correctamente"
}
```

#### Errores
Especialidad no existente
```json
{
    "success": false,
    "status": 404,
    "message": "La especialidad no existe"
}
```
Datos dependientes
```json
{
    "success": false,
    "status": 409,
    "message": "No se puede eliminar el registro porque existen registros relacionados."
}
```

---

## Pacientes
### Endpoints
- [`GET /pacientes`](#get-pacientes)
- [`GET /pacientes/{id}`](#get-pacientesid)
- [`POST /pacientes`](#post-pacientes)
- [`PUT /pacientes/{id}`](#put-pacientesid)
- [`DELETE /pacientes/{id}`](#delete-pacientesid)
- [`GET /pacientes/{id}/citas`](#get-pacientesidcitas)

### GET /pacientes
Listar todos los pacientes.
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Pacientes obtenidos correctamente",
    "data": [
        {
            "id_paciente": "1",
            "nombre": "Juan",
            "apellidos": "Pérez Soto",
            "fecha_nacimiento": "1985-03-15",
            "genero": "M",
            "email": "juan.perez@email.com",
            "telefono": "555-111-2222",
            "direccion": "Calle Principal 123",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "2",
            "nombre": "María",
            "apellidos": "García López",
            "fecha_nacimiento": "1990-07-20",
            "genero": "F",
            "email": "maria.garcia@email.com",
            "telefono": "555-222-3333",
            "direccion": "Avenida Central 456",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "3",
            "nombre": "Pedro",
            "apellidos": "Martínez Gómez",
            "fecha_nacimiento": "1978-12-03",
            "genero": "M",
            "email": "pedro.martinez@email.com",
            "telefono": "555-333-4444",
            "direccion": "Boulevard Norte 789",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "4",
            "nombre": "Ana",
            "apellidos": "López Hernández",
            "fecha_nacimiento": "1995-05-25",
            "genero": "F",
            "email": "ana.lopez@email.com",
            "telefono": "555-444-5555",
            "direccion": "Calle Sur 234",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "5",
            "nombre": "Luis",
            "apellidos": "Rodríguez Castro",
            "fecha_nacimiento": "1982-09-10",
            "genero": "M",
            "email": "luis.rodriguez@email.com",
            "telefono": "555-555-6666",
            "direccion": "Avenida Este 567",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "6",
            "nombre": "Carmen",
            "apellidos": "Torres Vargas",
            "fecha_nacimiento": "1973-06-28",
            "genero": "F",
            "email": "carmen.torres@email.com",
            "telefono": "555-666-7777",
            "direccion": "Calle Poniente 890",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "7",
            "nombre": "Roberto",
            "apellidos": "Díaz Morales",
            "fecha_nacimiento": "1988-02-12",
            "genero": "M",
            "email": "roberto.diaz@email.com",
            "telefono": "555-777-8888",
            "direccion": "Boulevard Sur 123",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "8",
            "nombre": "Laura",
            "apellidos": "Fernández Rivas",
            "fecha_nacimiento": "1992-11-07",
            "genero": "F",
            "email": "laura.fernandez@email.com",
            "telefono": "555-888-9999",
            "direccion": "Avenida Norte 456",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "9",
            "nombre": "Miguel",
            "apellidos": "Sánchez Ortega",
            "fecha_nacimiento": "1980-04-18",
            "genero": "M",
            "email": "miguel.sanchez@email.com",
            "telefono": "555-999-0000",
            "direccion": "Calle Oriente 789",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "10",
            "nombre": "Sofía",
            "apellidos": "Ramírez Mendoza",
            "fecha_nacimiento": "1997-08-30",
            "genero": "F",
            "email": "sofia.ramirez@email.com",
            "telefono": "555-000-1111",
            "direccion": "Boulevard Oeste 234",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "11",
            "nombre": "Jorge",
            "apellidos": "Gómez Estrada",
            "fecha_nacimiento": "1975-01-22",
            "genero": "M",
            "email": "jorge.gomez@email.com",
            "telefono": "555-112-2233",
            "direccion": "Avenida Principal 567",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "12",
            "nombre": "Claudia",
            "apellidos": "Jiménez Rojas",
            "fecha_nacimiento": "1991-10-05",
            "genero": "F",
            "email": "claudia.jimenez@email.com",
            "telefono": "555-223-3344",
            "direccion": "Calle Central 890",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "13",
            "nombre": "Fernando",
            "apellidos": "Ortiz Castillo",
            "fecha_nacimiento": "1983-07-14",
            "genero": "M",
            "email": "fernando.ortiz@email.com",
            "telefono": "555-334-4455",
            "direccion": "Boulevard Principal 123",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "14",
            "nombre": "Isabel",
            "apellidos": "Núñez Pacheco",
            "fecha_nacimiento": "1979-03-27",
            "genero": "F",
            "email": "isabel.nunez@email.com",
            "telefono": "555-445-5566",
            "direccion": "Avenida Sur 456",
            "fecha_registro": "2025-05-27 15:09:48"
        },
        {
            "id_paciente": "15",
            "nombre": "Raúl",
            "apellidos": "Herrera Méndez",
            "fecha_nacimiento": "1994-06-09",
            "genero": "M",
            "email": "raul.herrera@email.com",
            "telefono": "555-556-6677",
            "direccion": "Calle Norte 789",
            "fecha_registro": "2025-05-27 15:09:48"
        }
    ]
}
```

### GET /pacientes/{id}
Obtener información de un paciente específico
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Paciente obtenido correctamente",
    "data": {
        "id_paciente": "1",
        "nombre": "Juan",
        "apellidos": "Pérez Soto",
        "fecha_nacimiento": "1985-03-15",
        "genero": "M",
        "email": "juan.perez@email.com",
        "telefono": "555-111-2222",
        "direccion": "Calle Principal 123",
        "fecha_registro": "2025-05-27 15:09:48"
    }
}
```
#### Errores
No existe el paciente
```json
{
    "success": false,
    "status": 404,
    "message": "Paciente no encontrado"
}
```

### POST /pacientes
Registrar un nuevo paciente
#### Solicitud
```json
{
    "nombre": "Rusell",
    "apellidos": "Emmanuel Canche Ciau",
    "fecha_nacimiento": "1994-06-09",
    "genero": "M",
    "email": "rusell.herrera@email.com",
    "telefono": "984-556-6877",
    "direccion": "Calle Norte 789"
}
```
#### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Paciente creado correctamente"
}
```

#### Errores
Datos duplicados
```json
{
    "success": false,
    "status": 409,
    "message": "Ya existe un registro con los mismos datos únicos."
}
```

### PUT /pacientes/{id}
Actualizar información de un paciente  
#### Solicitud
```json
{
    "nombre": "Rusell",
    "apellidos": "Emmanuel Canche Ciau",
    "fecha_nacimiento": "2003-06-09",
    "genero": "M",
    "email": "rusell.herrera@email.com",
    "telefono": "984-556-6877",
    "direccion": "Calle Norte 789"
}
```

#### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Paciente actualizado correctamente"
}
```

#### Errores
Paciente no existente
```json
{
    "success": false,
    "status": 404,
    "message": "Paciente no encontrado"
}
```

### DELETE /pacientes/{id}
Eliminar un paciente  
#### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Paciente borrado correctamente"
}
```

#### Errores
Paciente no existente
```json
{
    "success": false,
    "status": 404,
    "message": "Paciente no encontrado"
}
```
Datos dependientes
```json
{
    "success": false,
    "status": 409,
    "message": "No se puede eliminar el registro porque existen registros relacionados."
}
```

### GET /pacientes/{id}/citas
Obtener las citas de un paciente
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Citas conseguidas por paciente",
    "data": [
        {
            "id_cita": "1",
            "id_paciente": "1",
            "id_medico": "1",
            "fecha": "2025-05-26",
            "hora_inicio": "09:00:00",
            "hora_fin": "09:30:00",
            "motivo": "Chequeo general",
            "estado": "Programada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        }
    ]
}
```

#### Errores
No existe el paciente
```json
{
    "success": false,
    "status": 404,
    "message": "Paciente no encontrado"
}
```

---

## Horarios
### Endpoints
- [`GET /horarios`](#get-horarios)
- [`GET /horarios/{id}`](#get-horariosid)
- [`POST /horarios`](#post-horarios)
- [`PUT /horarios/{id}`](#put-horariosid)
- [`DELETE /horarios/{id}`](#delete-horariosid)

### GET /horarios
Listar todos los horarios.
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Horarios obtenidos correctamente",
    "data": [
        {
            "id_horario": "1",
            "id_medico": "1",
            "dia_semana": "Lunes",
            "hora_inicio": "08:00:00",
            "hora_fin": "14:00:00"
        },
        {
            "id_horario": "2",
            "id_medico": "1",
            "dia_semana": "Miércoles",
            "hora_inicio": "08:00:00",
            "hora_fin": "14:00:00"
        },
        {
            "id_horario": "3",
            "id_medico": "1",
            "dia_semana": "Viernes",
            "hora_inicio": "08:00:00",
            "hora_fin": "12:00:00"
        },
        {
            "id_horario": "4",
            "id_medico": "2",
            "dia_semana": "Martes",
            "hora_inicio": "09:00:00",
            "hora_fin": "15:00:00"
        },
        {
            "id_horario": "5",
            "id_medico": "2",
            "dia_semana": "Jueves",
            "hora_inicio": "09:00:00",
            "hora_fin": "15:00:00"
        },
        {
            "id_horario": "6",
            "id_medico": "3",
            "dia_semana": "Lunes",
            "hora_inicio": "14:00:00",
            "hora_fin": "20:00:00"
        },
        {
            "id_horario": "7",
            "id_medico": "3",
            "dia_semana": "Miércoles",
            "hora_inicio": "14:00:00",
            "hora_fin": "20:00:00"
        },
        {
            "id_horario": "8",
            "id_medico": "3",
            "dia_semana": "Viernes",
            "hora_inicio": "14:00:00",
            "hora_fin": "18:00:00"
        },
        {
            "id_horario": "9",
            "id_medico": "4",
            "dia_semana": "Martes",
            "hora_inicio": "08:00:00",
            "hora_fin": "16:00:00"
        },
        {
            "id_horario": "10",
            "id_medico": "4",
            "dia_semana": "Jueves",
            "hora_inicio": "08:00:00",
            "hora_fin": "16:00:00"
        },
        {
            "id_horario": "11",
            "id_medico": "5",
            "dia_semana": "Lunes",
            "hora_inicio": "07:00:00",
            "hora_fin": "13:00:00"
        },
        {
            "id_horario": "12",
            "id_medico": "5",
            "dia_semana": "Martes",
            "hora_inicio": "14:00:00",
            "hora_fin": "20:00:00"
        },
        {
            "id_horario": "13",
            "id_medico": "5",
            "dia_semana": "Viernes",
            "hora_inicio": "08:00:00",
            "hora_fin": "14:00:00"
        },
        {
            "id_horario": "14",
            "id_medico": "6",
            "dia_semana": "Miércoles",
            "hora_inicio": "09:00:00",
            "hora_fin": "17:00:00"
        },
        {
            "id_horario": "15",
            "id_medico": "6",
            "dia_semana": "Sábado",
            "hora_inicio": "08:00:00",
            "hora_fin": "13:00:00"
        },
        {
            "id_horario": "16",
            "id_medico": "7",
            "dia_semana": "Lunes",
            "hora_inicio": "12:00:00",
            "hora_fin": "20:00:00"
        },
        {
            "id_horario": "17",
            "id_medico": "7",
            "dia_semana": "Jueves",
            "hora_inicio": "12:00:00",
            "hora_fin": "20:00:00"
        },
        {
            "id_horario": "18",
            "id_medico": "8",
            "dia_semana": "Martes",
            "hora_inicio": "07:00:00",
            "hora_fin": "15:00:00"
        },
        {
            "id_horario": "19",
            "id_medico": "8",
            "dia_semana": "Viernes",
            "hora_inicio": "07:00:00",
            "hora_fin": "15:00:00"
        },
        {
            "id_horario": "20",
            "id_medico": "9",
            "dia_semana": "Lunes",
            "hora_inicio": "16:00:00",
            "hora_fin": "20:00:00"
        },
        {
            "id_horario": "21",
            "id_medico": "9",
            "dia_semana": "Miércoles",
            "hora_inicio": "16:00:00",
            "hora_fin": "20:00:00"
        },
        {
            "id_horario": "22",
            "id_medico": "9",
            "dia_semana": "Viernes",
            "hora_inicio": "16:00:00",
            "hora_fin": "20:00:00"
        },
        {
            "id_horario": "23",
            "id_medico": "10",
            "dia_semana": "Martes",
            "hora_inicio": "08:00:00",
            "hora_fin": "13:00:00"
        },
        {
            "id_horario": "24",
            "id_medico": "10",
            "dia_semana": "Jueves",
            "hora_inicio": "14:00:00",
            "hora_fin": "19:00:00"
        },
        {
            "id_horario": "25",
            "id_medico": "10",
            "dia_semana": "Sábado",
            "hora_inicio": "09:00:00",
            "hora_fin": "14:00:00"
        }
    ]
}
```

### GET /horarios/{id}
Obtener un horario específico
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Horario obtenido correctamente",
    "data": {
        "id_horario": "1",
        "id_medico": "1",
        "dia_semana": "Lunes",
        "hora_inicio": "08:00:00",
        "hora_fin": "14:00:00"
    }
}
```

#### Errores
No existe el horario
```json
{
    "success": false,
    "status": 404,
    "message": "Horario no encontrado"
}
```

### POST /horarios
Crear un nuevo horario
#### Solicitud
```json
{
    "id_medico": "1",
    "dia_semana": "Lunes",
    "hora_inicio": "10:00:00",
    "hora_fin": "12:00:00"
}
```

#### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Horario creado correctamente"
}
```

#### Errores
Conflicto de horario
```json
{
    "success": false,
    "status": 409,
    "message": "Ya existe un registro con los mismos datos únicos."
}
```

### PUT /horarios/{id}
Actualizar un horario existente  
#### Solicitud
```json
{
    "id_medico": "10",
    "dia_semana": "Jueves",
    "hora_inicio": "10:00:00",
    "hora_fin": "12:00:00"
}
```

#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Horario actualizado correctamente"
}
```

#### Errores
Horario no existente
```json
{
    "success": false,
    "status": 404,
    "message": "Horario no encontrado"
}
```

### DELETE /horarios/{id}
Eliminar un horario  
#### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Horario eliminado correctamente"
}
```

#### Errores
Horario no existente
```json
{
    "success": false,
    "status": 404,
    "message": "Horario no encontrado"
}
```
---

## Citas
### Endpoints
- [`GET /citas`](#get-citas)
- [`GET /citas/{id}`](#get-citasid)
- [`POST /citas`](#post-citas)
- [`PUT /citas/{id}`](#put-citasid)
- [`DELETE /citas/{id}`](#delete-citasid)

### GET /citas
Listar todas las citas.
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Citas obtenidas correctamente",
    "data": [
        {
            "id_cita": "1",
            "id_paciente": "1",
            "id_medico": "1",
            "fecha": "2025-05-26",
            "hora_inicio": "09:00:00",
            "hora_fin": "09:30:00",
            "motivo": "Chequeo general",
            "estado": "Programada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "2",
            "id_paciente": "2",
            "id_medico": "3",
            "fecha": "2025-05-26",
            "hora_inicio": "15:00:00",
            "hora_fin": "15:30:00",
            "motivo": "Dolor de garganta",
            "estado": "Confirmada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "3",
            "id_paciente": "3",
            "id_medico": "5",
            "fecha": "2025-05-27",
            "hora_inicio": "08:00:00",
            "hora_fin": "08:30:00",
            "motivo": "Control de presión arterial",
            "estado": "Programada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "4",
            "id_paciente": "4",
            "id_medico": "2",
            "fecha": "2025-05-27",
            "hora_inicio": "10:00:00",
            "hora_fin": "10:30:00",
            "motivo": "Consulta de rutina",
            "estado": "Confirmada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "5",
            "id_paciente": "5",
            "id_medico": "4",
            "fecha": "2025-05-28",
            "hora_inicio": "09:00:00",
            "hora_fin": "09:30:00",
            "motivo": "Alergia en la piel",
            "estado": "Programada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "6",
            "id_paciente": "6",
            "id_medico": "6",
            "fecha": "2025-05-28",
            "hora_inicio": "10:00:00",
            "hora_fin": "10:30:00",
            "motivo": "Dolor en la rodilla",
            "estado": "Confirmada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "7",
            "id_paciente": "7",
            "id_medico": "8",
            "fecha": "2025-05-29",
            "hora_inicio": "08:00:00",
            "hora_fin": "08:30:00",
            "motivo": "Revisión de vista",
            "estado": "Programada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "8",
            "id_paciente": "8",
            "id_medico": "7",
            "fecha": "2025-05-29",
            "hora_inicio": "14:00:00",
            "hora_fin": "14:30:00",
            "motivo": "Dolor de cabeza frecuente",
            "estado": "Confirmada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "9",
            "id_paciente": "9",
            "id_medico": "9",
            "fecha": "2025-05-30",
            "hora_inicio": "17:00:00",
            "hora_fin": "17:30:00",
            "motivo": "Ansiedad",
            "estado": "Programada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "10",
            "id_paciente": "10",
            "id_medico": "10",
            "fecha": "2025-05-30",
            "hora_inicio": "09:00:00",
            "hora_fin": "09:30:00",
            "motivo": "Revisión dental",
            "estado": "Confirmada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "11",
            "id_paciente": "1",
            "id_medico": "2",
            "fecha": "2025-05-20",
            "hora_inicio": "11:00:00",
            "hora_fin": "11:30:00",
            "motivo": "Consulta por dolor en el pecho",
            "estado": "Completada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "12",
            "id_paciente": "2",
            "id_medico": "3",
            "fecha": "2025-05-21",
            "hora_inicio": "16:00:00",
            "hora_fin": "16:30:00",
            "motivo": "Control de crecimiento",
            "estado": "Completada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "13",
            "id_paciente": "3",
            "id_medico": "1",
            "fecha": "2025-05-22",
            "hora_inicio": "10:00:00",
            "hora_fin": "10:30:00",
            "motivo": "Seguimiento hipertensión",
            "estado": "Completada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "14",
            "id_paciente": "4",
            "id_medico": "5",
            "fecha": "2025-05-23",
            "hora_inicio": "09:00:00",
            "hora_fin": "09:30:00",
            "motivo": "Consulta ginecológica",
            "estado": "Completada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        },
        {
            "id_cita": "15",
            "id_paciente": "5",
            "id_medico": "4",
            "fecha": "2025-05-24",
            "hora_inicio": "11:00:00",
            "hora_fin": "11:30:00",
            "motivo": "Seguimiento tratamiento dermatológico",
            "estado": "Completada",
            "observaciones": null,
            "fecha_registro": "2025-05-27 15:09:49"
        }
    ]
}
```

### GET /citas/{id}
Obtener detalles de una cita específica
#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Cita obtenida correctamente",
    "data": {
        "id_cita": "1",
        "id_paciente": "1",
        "id_medico": "1",
        "fecha": "2025-05-26",
        "hora_inicio": "09:00:00",
        "hora_fin": "09:30:00",
        "motivo": "Chequeo general",
        "estado": "Programada",
        "observaciones": null,
        "fecha_registro": "2025-05-27 15:09:49"
    }
}
```

#### Errores
No existe la cita
```json
{
    "success": false,
    "status": 404,
    "message": "La cita no existe"
}
```

### POST /citas
Programar una nueva cita
#### Solicitud
```json
{
    "id_paciente": "5",
    "id_medico": "5",
    "fecha": "2025-05-24",
    "hora_inicio": "11:00:00",
    "hora_fin": "11:30:00",
    "motivo": "Seguimiento tratamiento dermatológico",
    "estado": "Completada",
    "observaciones": null,
    "fecha_registro": "2025-05-27 15:09:49"
}
```

#### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Cita creado correctamente",
    "data": true
}
```

#### Errores
Conflicto de horario
```json
{
    "success": false,
    "status": 400,
    "message": "Datos JSON inválidos"
}
```

### PUT /citas/{id}
Actualizar información de una cita  
#### Solicitud
```json
{
    "id_paciente": "7",
    "id_medico": "8",
    "fecha": "2025-05-24",
    "hora_inicio": "8:00:00",
    "hora_fin": "8:30:00",
    "motivo": "Seguimiento tratamiento dermatológico",
    "estado": "Programada",
    "observaciones": "Ninguna"
}
```

#### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Cita actualizada correctamente",
    "data": true
}
```

#### Errores
Cita no existente
```json
{
    "success": false,
    "status": 404,
    "message": "La cita no existe"
}
```

### DELETE /citas/{id}
Cancelar una cita  
#### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Cita borrada correctamente",
    "data": true
}
```

#### Errores
Cita no existente
```json
{
    "success": false,
    "status": 404,
    "message": "La cita no existe"
}
```


---

## Usuarios
### Endpoints
- [`POST /usuarios`](#post-usuarios)
- [`POST /usuarios/login`](#post-usuarioslogin)

### POST /usuarios
Registrar un nuevo usuario
#### Solicitud
```json
{
    "id_Usuario": "7",
    "username": "Rusell",
    "password": "Rusell2345",
    "email": "rusell@gmail.com",
    "claveApi": "APIKEY-ADMIN-002"
}
```

#### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Usuario registrado correctamente"
}
```

#### Errores
Email duplicado
```json
{
    "success": false,
    "status": 500,
    "message": "Error al crear el registro:SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'rusell@gmail.com' for key 'email'"
}
```

### POST /usuarios/login
Autenticar usuario
#### Solicitud
```json
{
    "username": "Rusell",
    "password": "Rusell2345",
    "email": "rusell@gmail.com"
}
```

#### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Login exitoso",
    "data": {
        "id_usuario": "7",
        "username": "Rusell",
        "email": "rusell@gmail.com",
        "claveAPI": "05bcddc1aec69fcbd854050e710d583f"
    }
}
```

#### Errores
Credenciales inválidas
```json
{
    "success": false,
    "status": 404,
    "message": "Credenciales inválidas"
}
```

---

## Autenticación
Se usa una autenticación de tipo Bearer Token, el cual es necesario enviar en cada consulta.

`POST /usuarios/login`
Se hace un login