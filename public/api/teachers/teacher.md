# ENDPOINTS PARA DOCENTES

### 1. ENDPOINT PARA OBTENER INFORMACIÓN DE UN DOCENTE

```bash
curl -X GET http://18.117.9.170:80/api/teachers/controllers/getTeacher.php?teacher-number=1
```
- Endpoint que obtiene datos de un docente
- Necesita estar autenticado

#### Objeto que responde con http status 200
`{
  "status": "success",
  "data": {
    "firstName": "María",
    "lastName": "Fernández",
    "employeeNumber": 1003,
    "email": "mfernandez@unah.hn",
    "phone": "9988-5678",
    "photo": "default_photo.png",
    "DNI": "0802199805678"
  },
  "roles": [
    {
      "ROL_NAME": "TEACHERS"
    }
  ],
  "error": null
}`

#### Objeto que responde con http status distinto a 200
`{
  "status": "failure",
  "data": [],
  "error": {
    "errorCode": 401,
    "errorMessage": "Unathorized"
  }
}`

### 2. ENDPOINT PARA OBTENER SECCIONES ACTIVAS DE UN DOCENTE 
```bash
curl -X GET http://18.117.9.170:80/api/teachers/controllers/teacherSections.php?teacher-number=1
```
- Necesita estar autenticado

#### Objeto que responde con http status 200
`{
  "status": "success",
  "data": [
    {
      "CLASS_CODE": "MM-314",
      "SECTION_CODE": "A1",
      "CLASS_NAME": "Programación I"
    }
  ],
  "error": null
}`


#### Objeto que responde con http status distinto 200
`{
  "status": "failure",
  "data": [],
  "error": {
    "errorCode": 401,
    "errorMessage": "Unathorized"
  }
}`

### 3. ENDPOINT PARA OBTENER INFORMACIÓN DE UNA SECCIÓN
```bash
curl -X GET http://18.117.9.170:80/api/teachers/controllers/section.php?section-id=1
```
- Necesita estar autenticado

#### Objeto que responde con http status 200
`{
  "status": "success",
  "data": {
    "className": "Programación I",
    "classCode": "MM-314",
    "sectionCode": "A1",
    "students": [
      {
        "IS_STUDENT": 2001
      }
    ],
    "daysOfWeek": [
      "Lunes",
      "Miércoles",
      "Viernes"
    ],
    "sectionID": 1
  },
  "error": null
}`


#### Objeto que responde con http status distinto a 200
`{
  "status": "failure",
  "data": [],
  "error": {
    "errorCode": 401,
    "errorMessage": "Unathorized"
  }
}`


### 4. ENDPOINT PARA AGREGAR VIDEO A UNA SECCIÓN
```bash
curl -X POST http://18.117.9.170:80/api/teachers/controllers/addVideo.php
```
#### REQUEST BODY
`{ sectionID: int, URL: str}`

- Necesita autenticación

#### Objeto que responde con http status 200
`{
  "status": "success",
  "data": [],
  "error": null
}`


#### Objeto que responde con http status distinto a 200
`{
  "status": "failure",
  "data": [],
  "error": {
    "errorCode": 401,
    "errorMessage": "Unathorized"
  }
}`

### 5. ENDPOINT PARA ELIMINAR UN VIDEO
```bash
curl -X GET 'http://18.117.9.170:80/api/teachers/controllers/deleteVideo.php'
```
#### REQUEST BODY
`{ sectionID: int }`

- Necesita autenticación

#### Objeto que responde con http status 200
`{
  "status": "success",
  "data": [],
  "error": null
}`


#### Objeto que responde con http status 200
`{
  "status": "failure",
  "data": [],
  "error": {
    "errorCode": 401,
    "errorMessage": "Unathorized"
  }
}`

### 6. ENDPOINT PARA SUBIR NOTAS DE ESTUDIANTES
```bash
curl -X POST http://localhost:8000/api/teachers/controllers/uploadGrades.php
```
#### REQUEST BODY
{
  "sectionId": int,
  "csvPath": "string",
  "formatType": "grades"
}


#### Objeto que responde con http status 200
{
  "status": "success",
  "data": [],
  "error": null
}


#### Objeto que responde con http status 200
{
  "status": "failure",
  "data": [],
  "error": {
    "errorCode": 400,
    "errorMessage": "Bad request. Missing sectionId, csvPath, or formatType."
  }
}

#### Objeto que responde con http status 400 si faltan parametros
{
  "status": "failure",
  "data": [],
  "error": {
    "errorCode": 400,
    "errorMessage": "Invalid format type. Expected 'grades'."
  }
}

#### Objeto que responde con http status 400 si el formato no es valido
{
  "status": "failure",
  "data": [],
  "error": {
    "errorCode": 500,
    "errorMessage": "CSV file not found or unreadable: <path>"
  }
}

#### Objeto que responde con http status 500 si no es legible
{
  "status": "failure",
  "data": [],
  "error": {
    "errorCode": 500,
    "errorMessage": "Failed to read CSV file."
  }
}

#### Objeto que responde con http status 500 si no se pudo leer correctamente el csv
{
  "status": "failure",
  "data": [],
  "error": {
    "errorCode": 500,
    "errorMessage": "Error updating grade for student <idStudent>"
  }
}