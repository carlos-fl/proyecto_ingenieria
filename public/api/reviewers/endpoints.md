
## **SERVICIO DE REVISORES**
Ubicación: `/src/services/reviewers/controllers`

### 1.  OBTENER SOLICITUDES POR ESTADO**
```bash
curl -X GET "http://localhost:8000/src/services/reviewers/controllers/getApplicationsByStatus.php?requests=pending"
```
**Propósito:** Obtener solicitudes en estado `pending`, `approved` o `rejected`.
**Respuesta esperada:**
```json
{"status": "success", "data": [{"application_code": 20251000001}]}
```

### 2.  APROBAR UNA SOLICITUD**
```bash
curl -X POST "http://localhost:8000/src/services/reviewers/controllers/approveApplication.php" \
     -H "Content-Type: application/json" \
     -d '{
           "applicantCode": 20251000001
         }'
```
**Propósito:** Aprobar una solicitud de admisión.
**Respuesta esperada:**
```json
{"status": "success"}
```

### 3.  RECHAZAR UNA SOLICITUD CON COMENTARIO**
```bash
curl -X POST "http://localhost:8000/src/services/reviewers/controllers/rejectApplication.php" \
     -H "Content-Type: application/json" \
     -d '{
           "applicantCode": 20251001000,
           "commentary": "No cumple con los requisitos académicos"
         }'
```
**Propósito:** Rechazar una solicitud y registrar un comentario.
**Respuesta esperada:**
```json
{"status": "success"}
```


