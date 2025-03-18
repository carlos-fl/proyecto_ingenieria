## ** Módulo de Revisores (`reviewers`)**
**Ubicación:** `http://18.117.9.170:80/api/reviewers/controllers/`

### 🔹 1. OBTENER SOLICITUDES POR ESTADO
**Propósito:** Obtener las solicitudes según su estado (`pending`, `approved`, `rejected`).

```bash
curl -X GET "http://18.117.9.170:80/api/reviewers/controllers/getApplicationsByStatus.php?status=pending" | jq
```
**Respuesta esperada:**
```json
{
  "status": "success",
  "data": [
    { "APPLICATION_CODE": "20251000001" },
    { "APPLICATION_CODE": "20251000002" }
  ]
}
```
**Errores posibles:**
```json
{
  "status": "failure",
  "error": { "errorCode": "400", "errorMessage": "Invalid status value" }
}
```

---

### 🔹 2. OBTENER PRÓXIMA SOLICITUD PENDIENTE  
**Propósito:** Obtener las solicitudes según su estado (`pending`) Y se seleciona la ultima en lista.

```bash
curl -X GET "http://18.117.9.170:80/api/reviewers/controllers/getNextPendingApplication.php" | jq
```

**Respuesta esperada:**
```json
{
  "status": "success",
  "data": {
    "FIRST_NAME": "Ana",
    "LAST_NAME": "González",
    "IDENTIDAD": "0802199805678",
    "CORREO": "ana.gonzalez@unah.edu.hn",
    "CARRERA_PRINCIPAL": 103,
    "CARRERA_SECUNDARIA": 104,
    "CERTIFICATE_FILE": "sin_certificado.pdf"
  }
}
```
**Errores posibles:**
```json
{
  "status": "failure",
  "error": { "errorCode": "400", "errorMessage": "Invalid status value" }
}
```

---


### 🔹 3. APROBAR SOLICITUD DE ADMISIÓN
**Propósito:** Aprobar una solicitud de admisión.

```bash
curl -X POST "http://18.117.9.170:80/api/reviewers/controllers/approveApplication.php" \
     -H "Content-Type: application/json" \
     -d @- <<EOF | jq
{
  "applicationCode": 20251000001
}
EOF
```
**Respuesta esperada:**
```json
{
  "status": "success"
}
```
**Errores posibles:**
```json
{
  "status": "failure",
  "error": { "errorCode": "404", "errorMessage": "Application not found" }
}
```

---

### 🔹 4. RECHAZAR SOLICITUD DE ADMISIÓN
**Propósito:** Rechazar una solicitud de admisión y agregar un comentario.

```bash
curl -X POST "http://18.117.9.170:80/api/reviewers/controllers/rejectApplication.php" \
     -H "Content-Type: application/json" \
     -d @- <<EOF | jq
{
  "applicationCode": 20251000001,
  "commentary": "No cumple con los requisitos de admisión."
}
EOF
```
**Respuesta esperada:**
```json
{
  "status": "success"
}
```
**Errores posibles:**
```json
{
  "status": "failure",
  "error": { "errorCode": "404", "errorMessage": "Application not found" }
}
```







