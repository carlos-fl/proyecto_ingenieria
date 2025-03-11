##  **SERVICIO DE APLICANTES**
Ubicación: `/src/services/applicants/controllers`

### 1. OBTENER INFORMACIÓN DE UN APLICANTE**
```bash
curl -X GET "http://localhost:8000/src/services/applicants/controllers/getApplicant.php?applicant-code=20251000001"
```
**Propósito:** Obtener información de un aplicante por su código de solicitud.
**Respuesta esperada:**
```json
{"status": "success", "data": {"firstName": "Juan", "lastName": "Pérez", "id": "0801199905678"}}
```


