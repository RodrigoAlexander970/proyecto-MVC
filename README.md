# 🏥 API de Gestión Médica

Esta API permite gestionar médicos, pacientes, especialidades, horarios y citas médicas. Utiliza autenticación mediante Bearer Token y todas las solicitudes deben tener el encabezado `Content-Type: application/json`.

- Tipo de autenticación: Bearer Token
- Content-Type: application/json

## Tabla de Contenido
- [Autenticación](#-autenticación)
- [Médicos](#médicos)
- [Especialidades](#-especialidades)
- [Pacientes](#-pacientes)
- [Horarios](#-horarios)
- [Citas](#-citas)
- [Usuarios](#-usuarios)


## Médicos
### Endpoints
- `GET /medicos`
- `GET /medicos/{id}`
- `GET /medicos/{id}/horarios`
- `GET /medicos/{id}/citas`
- `GET /medicos/{id}/pacientes`
- `POST /medicos`  
- `PUT /medicos/{id}`  
- `DELETE /medicos/{id}`  
####  GET /medicos
Listar todos los médicos.
##### Respuesta
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

#### GET /medicos/{id}
Obtener información de un médico específico
#### Respuesta
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
#### GET /medicos/{id}/horarios
Obtener los horarios de un médico en específico
##### Respuesta
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

##### Errores
No existe el medico
```json
{
    "success": false,
    "status": 404,
    "message": "Médico no encontrado"
}
```

#### GET /medicos/{id}/citas
Obtener las citas de un medico
##### Respuesta
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
##### Errores
No existe el medico
```json
{
    "success": false,
    "status": 404,
    "message": "Médico no encontrado"
}
```

#### GET /medicos/{id}/pacientes
Listar pacientes de un médico específico  
##### Respuesta
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
##### Errores
No existe el medico
```json
{
    "success": false,
    "status": 404,
    "message": "Médico no encontrado"
}
```

#### POST /medicos
Registrar un nuevo médico
##### Solicitud
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

##### Respuesta
```json
{
    "success": true,
    "status": 201,
    "message": "Médico creado correctamente"
}
```

##### Errores
Datos duplicados
```json
{
    "success": false,
    "status": 409,
    "message": "Ya existe un registro con los mismos datos únicos."
}
```

##### PUT /medicos/{id}
Actualizar información de un médico  
##### Solicitud
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

##### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Médico actualizado correctamente"
}
```

##### Errores
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

#### DELETE /medicos/{id}
Eliminar un médico  
##### Respuesta
```json
{
    "success": true,
    "status": 200,
    "message": "Médico borrado correctamente"
}
```
##### Errores
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
### 🧬 Especialidades

- [X] `GET /especialidades` – Listar todas las especialidades  
- [X] `GET /especialidades/{id}` – Obtener detalles de una especialidad  
- [X] `POST /especialidades` – Crear una nueva especialidad  
- [X] `PUT /especialidades/{id}` – Actualizar una especialidad  
- [X] `DELETE /especialidades/{id}` – Eliminar una especialidad  
- [X] `GET /especialidades/{id}/medicos` – Listar médicos por especialidad  

#### Objeto Especialidad:
```
{
    "id_especialidad": 1, (NO SE MANDA)
    "nombre": "Nombre especialidad", (OBLIGATORIO)
    "descripcion": "Descripcion especialidad", (OBLIGATORIO)
}
```
---

### 🧑‍🤝‍🧑 Pacientes

- [X] `GET /pacientes` – Listar todos los pacientes  
- [X] `GET /pacientes/{id}` – Obtener detalles de un paciente específico  
- [X] `POST /pacientes` – Registrar un nuevo paciente  
- [X] `PUT /pacientes/{id}` – Actualizar información de un paciente  
- [X] `DELETE /pacientes/{id}` – Borrar un paciente  
- [X] `GET /pacientes/{id}/citas` – Listar citas de un paciente  

#### Objeto Paciente:
```
{
    "id_paciente": 1,
    "nombre": "Juan",
    "apellidos": "Pérez Soto",
    "fecha_nacimiento": "1985-03-15",
    "genero": "M",
    "email": "juan.perez@email.com",
    "telefono": "555-111-2222",
    "direccion": "Calle Principal 123",
    "fecha_registro": "2025-05-22 22:19:21"
}
```

---

### ⏰ Horarios

- [X] `GET /horarios` – Listar todos los horarios  
- [X] `GET /horarios/{id}` – Obtener un horario específico  
- [X] `POST /horarios` – Crear un nuevo horario  
- [X] `PUT /horarios/{id}` – Actualizar un horario existente  
- [X] `DELETE /horarios/{id}` – Eliminar un horario  

#### Objeto Horario
```  
    {
        "id_horario": 1, (SOLO SE RECIBE)
        "id_medico": 1, (RELACION CON EL MEDICO)
        "dia_semana": "DIA", (Lunes, Martes, Miercoles, Jueves, Viernes)
        "hora_inicio": "08:00:00",
        "hora_fin": "14:00:00"
    }
```
---

### 📅 Citas

- [X] `GET /citas` – Listar todas las citas  
- [X] `GET /citas/{id}` – Obtener detalles de una cita específica  
- [X] `POST /citas` – Programar una nueva cita  
- [X] `PUT /citas/{id}` – Actualizar información de una cita  
- [X] `DELETE /citas/{id}` – Cancelar una cita

#### Objeto Cita
```
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
    "fecha_registro": "2025-05-22 22:19:21"
}
```

---

### Usuarios
- [X] `POST /usuarios` - Para crear un usuario
---

## Auntentificacion
- [X] `POST /usuarios/login` - Se hace un login