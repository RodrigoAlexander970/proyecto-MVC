## Endpoints de la API

### 🩺 Médicos

- [X] `GET /medicos` – Listar todos los médicos (con filtros opcionales)  
- [X] `GET /medicos/{id}` – Obtener detalles de un médico específico  
- [X] `POST /medicos` – Registrar un nuevo médico  
- [X] `PUT /medicos/{id}` – Actualizar información de un médico  
- [X] `DELETE /medicos/{id}` – Eliminar un médico  
- [X] `GET /medicos/{id}/horarios` – Obtener los horarios de un médico en específico  
- [ ] `GET /medicos/{id}/pacientes` – Listar pacientes de un médico específico  

---

### 🧬 Especialidades

- [X] `GET /especialidades` – Listar todas las especialidades  
- [X] `GET /especialidades/{id}` – Obtener detalles de una especialidad  
- [X] `POST /especialidades` – Crear una nueva especialidad  
- [X] `PUT /especialidades/{id}` – Actualizar una especialidad  
- [X] `DELETE /especialidades/{id}` – Eliminar una especialidad  
- [ ] `GET /especialidades/{id}/medicos` – Listar médicos por especialidad  

```
OBJETO JSON:
{
    "id_especialidad": 1, (NO SE MANDA)
    "nombre": "Nombre especialidad", (OBLIGATORIO)
    "descripcion": "Descripcion especialidad", (OBLIGATORIO)
    "activo": 1 (NO SE MANDA)
}
```
---

### 🧑‍🤝‍🧑 Pacientes

- [X] `GET /pacientes` – Listar todos los pacientes  
- [X] `GET /pacientes/{id}` – Obtener detalles de un paciente específico  
- [ ] `POST /pacientes` – Registrar un nuevo paciente  
- [ ] `PUT /pacientes/{id}` – Actualizar información de un paciente  
- [ ] `DELETE /pacientes/{id}` – Desactivar un paciente  
- [ ] `GET /pacientes/{id}/citas` – Listar citas de un paciente  
- [ ] `GET /pacientes/{id}/historiales` – Listar historiales médicos de un paciente  

---

### ⏰ Horarios

- [X] `GET /horarios` – Listar todos los horarios  
- [X] `GET /horarios/{id}` – Obtener un horario específico  
- [X] `POST /horarios` – Crear un nuevo horario  
- [X] `PUT /horarios/{id}` – Actualizar un horario existente  
- [X] `DELETE /horarios/{id}` – Eliminar un horario  
- [ ] `GET /medicos/{id}/horarios` – Listar horarios de un médico específico  

```  
OBJETO JSON:
    {
        "id_horario": 1, (SOLO SE RECIBE)
        "id_medico": 1, (RELACION CON EL MEDICO)
        "dia_semana": "DIA", (Lunes, Martes, Miercoles, Jueves, Viernes)
        "hora_inicio": "08:00:00",
        "hora_fin": "14:00:00",
        "activo": 1 (SOLO AL ACTUALIZAR)
    }
```
---

### 📅 Citas

- [ ] `GET /citas` – Listar todas las citas  
- [ ] `GET /citas/{id}` – Obtener detalles de una cita específica  
- [ ] `POST /citas` – Programar una nueva cita  
- [ ] `PUT /citas/{id}` – Actualizar información de una cita  
- [ ] `DELETE /citas/{id}` – Cancelar una cita  
- [ ] `PATCH /citas/{id}/estado` – Actualizar estado de una cita  
- [ ] `GET /citas/fecha/{fecha}` – Listar citas por fecha  

---

### 👥 Usuarios y Roles

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

### 📊 Reportes

- [ ] `GET /reportes/citas-por-medico` – Reporte de citas por médico  
- [ ] `GET /reportes/citas-por-especialidad` – Reporte de citas por especialidad  
- [ ] `GET /reportes/ingresos` – Reporte de ingresos (filtrable por período)  
- [ ] `GET /reportes/pacientes-frecuentes` – Reporte de pacientes frecuentes  
- [ ] `GET /reportes/ocupacion-medicos` – Reporte de ocupación de médicos  
- [ ] `POST /reportes/generar-pdf` – Generar reporte en formato PDF  
- [ ] `POST /reportes/enviar-email` – Enviar reporte por email  

---

### 📁 Archivos

- [ ] `POST /archivos/upload` – Subir un archivo  
- [ ] `GET /archivos/{id}` – Descargar un archivo  
- [ ] `DELETE /archivos/{id}` – Eliminar un archivo  
- [ ] `POST /pacientes/{id}/archivos` – Subir archivo para un paciente  
- [ ] `GET /pacientes/{id}/archivos` – Listar archivos de un paciente  
- [ ] `GET /historiales/{id}/archivos` – Listar archivos de un historial clínico  
