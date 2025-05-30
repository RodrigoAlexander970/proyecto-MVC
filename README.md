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
RESPUESTA_JSON_ESPECIALIDADES

### GET /especialidades/{id}
Obtener información de una especialidad específica
#### Respuesta
RESPUESTA_JSON_ESPECIALIDAD

### GET /especialidades/{id}/medicos
Obtener los médicos de una especialidad específica
#### Respuesta
RESPUESTA_JSON_MEDICOS_ESPECIALIDAD

#### Errores
No existe la especialidad
ERROR_JSON_ESPECIALIDAD_NO_ENCONTRADA

### POST /especialidades
Registrar una nueva especialidad
#### Solicitud
SOLICITUD_JSON_NUEVA_ESPECIALIDAD

#### Respuesta
RESPUESTA_JSON_ESPECIALIDAD_CREADA

#### Errores
Datos faltantes
ERROR_JSON_DATOS_FALTANTES

### PUT /especialidades/{id}
Actualizar información de una especialidad  
#### Solicitud
SOLICITUD_JSON_ACTUALIZAR_ESPECIALIDAD

#### Respuesta
RESPUESTA_JSON_ESPECIALIDAD_ACTUALIZADA

#### Errores
Especialidad no existente
ERROR_JSON_ESPECIALIDAD_NO_ENCONTRADA

### DELETE /especialidades/{id}
Eliminar una especialidad  
#### Respuesta
RESPUESTA_JSON_ESPECIALIDAD_ELIMINADA

#### Errores
Especialidad no existente
ERROR_JSON_ESPECIALIDAD_NO_ENCONTRADA
Datos dependientes
ERROR_JSON_DEPENDENCIAS_ESPECIALIDAD

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
RESPUESTA_JSON_PACIENTES

### GET /pacientes/{id}
Obtener información de un paciente específico
#### Respuesta
RESPUESTA_JSON_PACIENTE

#### Errores
No existe el paciente
ERROR_JSON_PACIENTE_NO_ENCONTRADO

### POST /pacientes
Registrar un nuevo paciente
#### Solicitud
SOLICITUD_JSON_NUEVO_PACIENTE

#### Respuesta
RESPUESTA_JSON_PACIENTE_CREADO

#### Errores
Datos duplicados
ERROR_JSON_EMAIL_DUPLICADO

### PUT /pacientes/{id}
Actualizar información de un paciente  
#### Solicitud
SOLICITUD_JSON_ACTUALIZAR_PACIENTE

#### Respuesta
RESPUESTA_JSON_PACIENTE_ACTUALIZADO

#### Errores
Paciente no existente
ERROR_JSON_PACIENTE_NO_ENCONTRADO

### DELETE /pacientes/{id}
Eliminar un paciente  
#### Respuesta
RESPUESTA_JSON_PACIENTE_ELIMINADO

#### Errores
Paciente no existente
ERROR_JSON_PACIENTE_NO_ENCONTRADO
Datos dependientes
ERROR_JSON_DEPENDENCIAS_PACIENTE

### GET /pacientes/{id}/citas
Obtener las citas de un paciente
#### Respuesta
RESPUESTA_JSON_CITAS_PACIENTE

#### Errores
No existe el paciente
ERROR_JSON_PACIENTE_NO_ENCONTRADO

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
RESPUESTA_JSON_HORARIOS

### GET /horarios/{id}
Obtener un horario específico
#### Respuesta
RESPUESTA_JSON_HORARIO

#### Errores
No existe el horario
ERROR_JSON_HORARIO_NO_ENCONTRADO

### POST /horarios
Crear un nuevo horario
#### Solicitud
SOLICITUD_JSON_NUEVO_HORARIO

#### Respuesta
RESPUESTA_JSON_HORARIO_CREADO

#### Errores
Conflicto de horario
ERROR_JSON_CONFLICTO_HORARIO

### PUT /horarios/{id}
Actualizar un horario existente  
#### Solicitud
SOLICITUD_JSON_ACTUALIZAR_HORARIO

#### Respuesta
RESPUESTA_JSON_HORARIO_ACTUALIZADO

#### Errores
Horario no existente
ERROR_JSON_HORARIO_NO_ENCONTRADO

### DELETE /horarios/{id}
Eliminar un horario  
#### Respuesta
RESPUESTA_JSON_HORARIO_ELIMINADO

#### Errores
Horario no existente
ERROR_JSON_HORARIO_NO_ENCONTRADO

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
RESPUESTA_JSON_CITAS

### GET /citas/{id}
Obtener detalles de una cita específica
#### Respuesta
RESPUESTA_JSON_CITA

#### Errores
No existe la cita
ERROR_JSON_CITA_NO_ENCONTRADA

### POST /citas
Programar una nueva cita
#### Solicitud
SOLICITUD_JSON_NUEVA_CITA

#### Respuesta
RESPUESTA_JSON_CITA_PROGRAMADA

#### Errores
Conflicto de horario
ERROR_JSON_CONFLICTO_CITA

### PUT /citas/{id}
Actualizar información de una cita  
#### Solicitud
SOLICITUD_JSON_ACTUALIZAR_CITA

#### Respuesta
RESPUESTA_JSON_CITA_ACTUALIZADA

#### Errores
Cita no existente
ERROR_JSON_CITA_NO_ENCONTRADA

### DELETE /citas/{id}
Cancelar una cita  
#### Respuesta
RESPUESTA_JSON_CITA_CANCELADA

#### Errores
Cita no existente
ERROR_JSON_CITA_NO_ENCONTRADA
Cita ya completada
ERROR_JSON_CITA_COMPLETADA

---

## Usuarios
### Endpoints
- [`POST /usuarios`](#post-usuarios)
- [`POST /usuarios/login`](#post-usuarioslogin)

### POST /usuarios
Registrar un nuevo usuario
#### Solicitud
SOLICITUD_JSON_NUEVO_USUARIO

#### Respuesta
RESPUESTA_JSON_USUARIO_CREADO

#### Errores
Email duplicado
ERROR_JSON_EMAIL_DUPLICADO

### POST /usuarios/login
Autenticar usuario
#### Solicitud
SOLICITUD_JSON_LOGIN

#### Respuesta
RESPUESTA_JSON_LOGIN_EXITOSO

#### Errores
Credenciales inválidas
ERROR_JSON_CREDENCIALES_INVALIDAS

---

## Autenticación
Se usa una autenticación de tipo Bearer Token, el cual es necesario enviar en cada consulta.

`POST /usuarios/login`
Se hace un login