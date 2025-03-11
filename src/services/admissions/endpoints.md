

## **SERVICIO DE ADMISIONES**
Ubicación: `/src/services/admissions/controllers`

### 1. CREAR SOLICITUD DE ADMISIÓN**
```bash
curl -X POST "http://localhost:8000/src/services/admissions/controllers/createAdmission.php" \
     -H "Content-Type: application/json" \
     -d '{
           "firstName": "Juan",
           "lastName": "Pérez",
           "id": "0801199905678",
           "phoneNumber": "98765432",
           "email": "juan.perez2@unah.edu.hn",
           "gender": "M",
           "primaryMajor": 101,
           "secondaryMajor": 102,
           "comment": "Solicitud de prueba",
           "certificate": "certificado.pdf"
         }'
```
**Propósito:** Registrar una nueva solicitud de admisión.
**Respuesta esperada:**
```json
{"status": "success", "application_code": "20251000001"}
```

### 2. ACTUALIZAR SOLICITUD DE ADMISIÓN**
```bash
curl -X PUT "http://localhost:8000/src/services/admissions/controllers/updateAdmission.php" \
     -H "Content-Type: application/json" \
     -d '{
           "applicationCode": 20251000001,
           "firstName": "Juan Carlos",
           "lastName": "Pérez",
           "id": "0801199905678",
           "phoneNumber": "98765433",
           "email": "juan.perez3@unah.edu.hn",
           "gender": "M",
           "primaryMajor": 101,
           "secondaryMajor": 102,
           "certificate": "certificado_actualizado.pdf"
         }'
```
**Propósito:** Actualizar una solicitud de admisión existente.
**Respuesta esperada:**
```json
{"status": "success"}
```

