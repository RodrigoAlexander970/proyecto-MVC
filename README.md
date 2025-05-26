## Endpoints de la API

### 🩺 Médicos

- [X] `GET /medicos` – Listar todos los médicos (con filtros opcionales)  
- [X] `GET /medicos/{id}` – Obtener detalles de un médico específico  
- [X] `POST /medicos` – Registrar un nuevo médico  
- [X] `PUT /medicos/{id}` – Actualizar información de un médico  
- [X] `DELETE /medicos/{id}` – Eliminar un médico  
- [] `GET /medicos/{id}/horarios` – Obtener los horarios de un médico en específico  
- [] `GET /medicos/{id}/citas` - Obtener las citas de un medico
- [] `GET /medicos/{id}/pacientes` – Listar pacientes de un médico específico  

#### Objeto Medico
```
{
    "id_medico": 1,
    "id_especialidad": 1,
    "nombre": "Nombre",
    "apellidos": "Apellidos",
    "cedula_profesional": "MG-12345SS",
    "email": "email@correo.com",
    "telefono": "123-456-7890"
}
```

---
### 🧬 Especialidades

- [X] `GET /especialidades` – Listar todas las especialidades  
- [X] `GET /especialidades/{id}` – Obtener detalles de una especialidad  
- [X] `POST /especialidades` – Crear una nueva especialidad  
- [X] `PUT /especialidades/{id}` – Actualizar una especialidad  
- [X] `DELETE /especialidades/{id}` – Eliminar una especialidad  
- [ ] `GET /especialidades/{id}/medicos` – Listar médicos por especialidad  

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
- [ ] `GET /pacientes/{id}/citas` – Listar citas de un paciente  

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

### 👥 Usuarios y Roles (por hacer)

- [ ] `GET /usuarios` – Listar todos los usuarios  
- [ ] `GET /usuarios/{id}` – Obtener detalles de un usuario específico  
- [ ] `POST /usuarios` – Crear un nuevo usuario  
- [ ] `PUT /usuarios/{id}` – Actualizar información de un usuario  
- [ ] `DELETE /usuarios/{id}` – Desactivar un usuario  
- [ ] `GET /roles` – Listar todos los roles  
- [ ] `GET /roles/{id}` – Obtener detalles de un rol específico  
- [ ] `POST /roles` – Crear un nuevo rol  
- [ ] `PUT /roles/{id}` – Actualizar un rol  
- [ ] `DELETE /roles/{id}` – Eliminar un rol  

---

### 📊 Reportes (por hacer)

- [ ] `GET /reportes/citas-por-medico` – Reporte de citas por médico  
- [ ] `GET /reportes/citas-por-especialidad` – Reporte de citas por especialidad  
- [ ] `GET /reportes/pacientes-frecuentes` – Reporte de pacientes frecuentes  
- [ ] `GET /reportes/ocupacion-medicos` – Reporte de ocupación de médicos  

---
