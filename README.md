## Endpoints de la API

### 🩺 Médicos

- [X] `GET /medicos` – Listar todos los médicos (con filtros opcionales)  
- [X] `GET /medicos/{id}` – Obtener detalles de un médico específico  
- [X] `POST /medicos` – Registrar un nuevo médico  
- [X] `PUT /medicos/{id}` – Actualizar información de un médico  
- [X] `DELETE /medicos/{id}` – Eliminar un médico  
- [X] `GET /medicos/{id}/horarios` – Obtener los horarios de un médico en específico  
- [ ] `GET /medicos/{id}/citas` – Listar citas de un médico específico  
- [ ] `GET /medicos/{id}/pacientes` – Listar pacientes de un médico específico  

---

### 🧬 Especialidades

- [X] `GET /especialidades` – Listar todas las especialidades  
- [X] `GET /especialidades/{id}` – Obtener detalles de una especialidad  
- [X] `POST /especialidades` – Crear una nueva especialidad  
- [ ] `PUT /especialidades/{id}` – Actualizar una especialidad  
- [ ] `DELETE /especialidades/{id}` – Eliminar una especialidad  
- [ ] `GET /especialidades/{id}/medicos` – Listar médicos por especialidad  

---

### 🧑‍🤝‍🧑 Pacientes

- [ ] `GET /pacientes` – Listar todos los pacientes  
- [ ] `GET /pacientes/{id}` – Obtener detalles de un paciente específico  
- [ ] `POST /pacientes` – Registrar un nuevo paciente  
- [ ] `PUT /pacientes/{id}` – Actualizar información de un paciente  
- [ ] `DELETE /pacientes/{id}` – Desactivar un paciente  
- [ ] `GET /pacientes/{id}/citas` – Listar citas de un paciente  
- [ ] `GET /pacientes/{id}/historiales` – Listar historiales médicos de un paciente  

---

### ⏰ Horarios

- [ ] `GET /horarios` – Listar todos los horarios  
- [ ] `GET /horarios/{id}` – Obtener un horario específico  
- [ ] `POST /horarios` – Crear un nuevo horario  
- [ ] `PUT /horarios/{id}` – Actualizar un horario existente  
- [ ] `DELETE /horarios/{id}` – Eliminar un horario  
- [ ] `GET /horarios/disponibles` – Listar horarios disponibles (con filtros)  
- [ ] `GET /medicos/{id}/horarios` – Listar horarios de un médico específico  

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

### 📝 Historiales Clínicos

- [ ] `GET /historiales` – Listar historiales clínicos (con filtros)  
- [ ] `GET /historiales/{id}` – Obtener un historial clínico específico  
- [ ] `POST /historiales` – Crear un nuevo registro en historial  
- [ ] `PUT /historiales/{id}` – Actualizar un historial clínico  
- [ ] `GET /citas/{id}/historial` – Obtener historial asociado a una cita  
- [ ] `GET /historiales/{id}/medicamentos` – Listar medicamentos de un historial  
- [ ] `POST /historiales/{id}/medicamentos` – Agregar medicamento a un historial  

---

### 💊 Medicamentos

- [ ] `GET /medicamentos` – Listar todos los medicamentos  
- [ ] `GET /medicamentos/{id}` – Obtener detalles de un medicamento específico  
- [ ] `POST /medicamentos` – Registrar un nuevo medicamento  
- [ ] `PUT /medicamentos/{id}` – Actualizar información de un medicamento  
- [ ] `DELETE /medicamentos/{id}` – Eliminar un medicamento  

---

### 💳 Pagos

- [ ] `GET /pagos` – Listar todos los pagos  
- [ ] `GET /pagos/{id}` – Obtener detalles de un pago específico  
- [ ] `POST /pagos` – Registrar un nuevo pago  
- [ ] `PUT /pagos/{id}` – Actualizar información de un pago  
- [ ] `GET /citas/{id}/pagos` – Obtener pagos de una cita específica  
- [ ] `GET /pacientes/{id}/pagos` – Listar pagos de un paciente  

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
