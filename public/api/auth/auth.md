# ENDPOINTS PARA AUTENTICACIÓN 

### 1. ENDPOINT PARA AUTENTICACIÓN USANDO INST_EMAIL AND PASSWORD DE ADMINISTRADOR

```bash
curl -X POST -d '{ "email": "carlos.rodriguez@unah.hn", "password": 1234 }' http://18.117.9.170:80/api/auth/controllers/adminLogin.php
```
- Endpoint utilizado para autenticar administrador
- Crea sesión

#### Objeto que responde con http status 200
`{
  "status": "success",
  "data": [],
  "sessionData": "{\"user\":{\"DNI\":\"0801199901234\",\"FIRST_NAME\":\"Carlos\",\"LAST_NAME\":\"Rodr\\u00edguez\",\"PHONE_NUMBER\":\"9999-1234\",\"PERSONAL_EMAIL\":\"carlos.rodriguez@gmail.com\",\"INST_EMAIL\":\"carlos.rodriguez@unah.hn\",\"PASSWORD\":\"d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db\",\"GENDER\":\"M\",\"PHOTO\":\"default_photo.png\",\"ACTIVE\":1,\"REGIONAL_CENTER_ID\":1},\"roles\":[\"ADMINISTRATOR\"]}",
  "error": null
}`
- sessionData es un objeto en formato JSON. su valor en objeto es: `{ user: {DNI: str, FIRST_NAME: str, LAST_NAME: str, PHONE_NUMBER: str, PERSONAL_EMAIL: str, INST_EMAIL: str, PASSWORD: str, GENDER: str, PHOTO: str, ACTIVE: boolean, REGIONAL_CENTER_ID: int}}, roles: [strings]}`


#### Objeto que responde con http status 401
`{
  "status": "failure",
  "data": null,
  "sessionData": null,
  "error": {
    "errorCode": 401,
    "errorMessage": "Incorrect email or password"
  }
}`
### 2. ENDPOINT PARA AUTENTICACIÓN USANDO INST_EMAIL AND PASSWORD DE APPLICANTREVIEWER 

```bash
curl -X POST -d '{ "email": "carlos.rodriguez@unah.hn", "password": 1234 }' http://18.117.9.170:80/api/auth/controllers/applicantReviewer.php
```
- Endpoint utilizado para autenticar al revisor de solicitudes de admisión
- Crea sesión

#### Objeto que responde con http status 200
`{
  "status": "success",
  "data": [],
  "sessionData": "{\"user\":{\"DNI\":\"0801199901234\",\"FIRST_NAME\":\"Carlos\",\"LAST_NAME\":\"Rodr\\u00edguez\",\"PHONE_NUMBER\":\"9999-1234\",\"PERSONAL_EMAIL\":\"carlos.rodriguez@gmail.com\",\"INST_EMAIL\":\"carlos.rodriguez@unah.hn\",\"PASSWORD\":\"d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db\",\"GENDER\":\"M\",\"PHOTO\":\"default_photo.png\",\"ACTIVE\":1,\"REGIONAL_CENTER_ID\":1},\"roles\":[\"APPLICANT_REVIEWER\"]}",
  "error": null
}`
- sessionData es un objeto en formato JSON. su valor en objeto es: `{ user: {DNI: str, FIRST_NAME: str, LAST_NAME: str, PHONE_NUMBER: str, PERSONAL_EMAIL: str, INST_EMAIL: str, PASSWORD: str, GENDER: str, PHOTO: str, ACTIVE: boolean, REGIONAL_CENTER_ID: int}}, roles: [strings]}`


#### Objeto que responde con http status 401
`{
  "status": "failure",
  "data": null,
  "sessionData": null,
  "error": {
    "errorCode": 401,
    "errorMessage": "Incorrect email or password"
  }
}`

### 3. ENDPOINT PARA AUTENTICACIÓN USANDO PERSONAL_EMAIL AND APPLICANT_CODE FOR APPLICANTS

```bash
curl -X POST -d '{ "email": "carlos.rodriguez@unah.hn", "applicantCode": 12344353 }' http://18.117.9.170:80/api/auth/controllers/applicantAuth.php
```
- Endpoint utilizado para autenticar al aplicante para revisar sus resultados
- Crea sesión

#### Objeto que responde con http status 200
`{
  "status": "success",
  "data": [{EXAM_NAME: str, CALIFICATION: float, DATE: date}],
  "sessionData": "{\"user\":{\"DNI\":\"0801199901234\",\"FIRST_NAME\":\"Carlos\",\"LAST_NAME\":\"Rodr\\u00edguez\",\"PHONE_NUMBER\":\"9999-1234\",\"PERSONAL_EMAIL\":\"carlos.rodriguez@gmail.com\",\"INST_EMAIL\":\"carlos.rodriguez@unah.hn\",\"PASSWORD\":\"d404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db\",\"GENDER\":\"M\",\"PHOTO\":\"default_photo.png\",\"ACTIVE\":1,\"REGIONAL_CENTER_ID\":1},\"roles\":[\"APPLICANT\"]}",
  "error": null
}`
- sessionData es un objeto en formato JSON. su valor en objeto es: `{ user: {DNI: str, FIRST_NAME: str, LAST_NAME: str, PHONE_NUMBER: str, PERSONAL_EMAIL: str, INST_EMAIL: str, PASSWORD: str, GENDER: str, PHOTO: str, ACTIVE: boolean, REGIONAL_CENTER_ID: int}}, roles: [strings]}`


#### Objeto que responde con http status 401
`{
  "status": "failure",
  "data": null,
  "sessionData": null,
  "error": {
    "errorCode": 401,
    "errorMessage": "Incorrect email or application code"
  }
}`