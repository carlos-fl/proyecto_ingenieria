## **Módulo de Admisiones (`admissions`)**
**Ubicación:** `http://18.117.9.170:80/api/admissions/controllers/`

### 🔹 1. CREAR SOLICITUD DE ADMISIÓN
**Propósito:** Registrar una nueva solicitud de admisión.

```bash
curl -X POST "http://18.117.9.170:80/api/admissions/controllers/createAdmission.php" \
     -H "Content-Type: multipart/form-data" \
     -F "firstName=Juan" \
     -F "lastName=Pérez" \
     -F "dni=0801199905678" \
     -F "phoneNumber=98765432" \
     -F "email=juan.perez2@unah.edu.hn" \
     -F "gender=M" \
     -F "primaryMajor=101" \
     -F "secondaryMajor=102" \
     -F "comment=Solicitud de prueba con documento" \
     -F "certificate=@/home/KojiKabuto75/Documents/ddl/certificado_prueba.pdf" | jq
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

Sin Cambio en certificado

```bash
curl -X PUT "http://18.117.9.170:80/api/admissions/controllers/updateAdmission.php" \
     -H "Content-Type: application/json" \
     -d @- <<EOF | jq
{
  "applicationCode": 20251000001,
  "firstName": "Juan Carlos",
  "lastName": "Pérez Mejía",
  "dni": "0801199905678",
  "phoneNumber": "99887766",
  "email": "juan.perez2@unah.edu.hn",
  "gender": "M",
  "primaryMajor": 101,
  "secondaryMajor": 102,
  "comment": "Actualización sin cambiar el documento"
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
Con Cambio en certificado

```bash
curl -X PUT "http://18.117.9.170:80/api/admissions/controllers/updateAdmission.php" \
     -H "Content-Type: multipart/form-data" \
     -F "applicationCode=20251000001" \
     -F "firstName=Juan Carlos" \
     -F "lastName=Pérez Mejía" \
     -F "dni=0801199905678" \
     -F "phoneNumber=99887766" \
     -F "email=juan.perez2@unah.edu.hn" \
     -F "gender=M" \
     -F "primaryMajor=101" \
     -F "secondaryMajor=102" \
     -F "comment=Actualización con nuevo documento" \
     -F "certificate=@/home/KojiKabuto75/Documents/ddl/nuevo_certificado.pdf" | jq
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