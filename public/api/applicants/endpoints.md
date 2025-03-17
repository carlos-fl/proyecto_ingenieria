## **Módulo de Solicitantes (`applicants`)**
**Ubicación:** `http://18.117.9.170:80/api/applicants/controllers/`

### 🔹 1. OBTENER SOLICITANTE POR CÓDIGO
**Propósito:** Obtener los datos de un solicitante según su código de aplicación.

```bash
curl -X GET "http://18.117.9.170:80/api/applicants/controllers/getApplicant.php?applicationCode=20251000001" | jq
```
**Respuesta esperada:**
```json
{
  "status": "success",
  "data": {
    "FIRST_NAME": "Juan",
    "LAST_NAME": "Pérez",
    "DNI": "0801199905678",
    "PHONE_NUMBER": "98765432",
    "EMAIL": "juan.perez2@unah.edu.hn",
    "GENDER": "M",
    "MAJOR_CODE": 101,
    "SECOND_MAJOR_CODE": 102,
    "CERTIFICATE_FILE": "certificado.pdf"
  }
}
```
**Errores posibles:**
```json
{
  "status": "failure",
  "error": { "errorCode": "404", "errorMessage": "Applicant not found" }
}
```


