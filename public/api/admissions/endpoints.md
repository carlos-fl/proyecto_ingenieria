## **Módulo de Admisiones (`admissions`)**
**Ubicación:** `http://18.117.9.170:80/api/admissions/controllers/`

### 🔹 1. CREAR SOLICITUD DE ADMISIÓN
**Propósito:** Registrar una nueva solicitud de admisión.

```bash
curl -X POST "http://18.117.9.170:80/api/admissions/controllers/createAdmission.php" \
     -H "Content-Type: application/json" \
     -d @- <<EOF | jq
{
  "firstName": "Juan",
  "lastName": "Pérez",
  "dni": "0801199905678",
  "phoneNumber": "98765432",
  "email": "juan.perez2@unah.edu.hn",
  "gender": "M",
  "primaryMajor": 101,
  "secondaryMajor": 102,
  "comment": "Solicitud de prueba",
  "certificate": "certificado.pdf"
}
EOF
```

**Respuesta esperada:**
```json
{
  "status": "success",
  "application_code": "20251000001"
}
```
**Errores posibles:**
```json
{
  "status": "failure",
  "error": { "errorCode": "400", "errorMessage": "Missing required fields" }
}
```

---

### 🔹 2. ACTUALIZAR SOLICITUD DE ADMISIÓN
**Propósito:** Actualizar una solicitud de admisión existente.

```bash
curl -X PUT "http://18.117.9.170:80/api/admissions/controllers/updateAdmission.php" \
     -H "Content-Type: application/json" \
     -d @- <<EOF | jq
{
  "applicationCode": 20251000001,
  "firstName": "Juan Carlos",
  "lastName": "Pérez",
  "dni": "0801199905678",
  "phoneNumber": "98765433",
  "email": "juan.perez3@unah.edu.hn",
  "gender": "M",
  "primaryMajor": 101,
  "secondaryMajor": 102,
  "comment": "Actualización de solicitud",
  "certificate": "certificado_actualizado.pdf"
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

### 3. ENDPOINT QUE RETORNA LAS CARRERAS JUNTO A SU ICON Y SCORE
```bash
curl -i -X GET "http://localhost:8000/src/services/administrator/controllers/majorsScoreIcon.php"
```

#### Objeto que responde con http status 200
`{"status": "success", data:[{major string, centers:[strings], score, icon}] }`


### 4. ENDPOINT QUE NOS RETORNA LOS TIPOS DE EXÁMENES JUNTO A SU PUNTUACIÓN MÁXIMA Y MÍNIMA

```bash
curl -i -X GET "http://localhost:8000/src/services/administrator/controllers/admissionsExams.php"
```

#### Objeto que responde con http status 200
`{"status": "success"}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data:[{examCode int, examName string, maxScore int, minScore int}] }`