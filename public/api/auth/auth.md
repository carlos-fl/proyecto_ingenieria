# ENDPOINTS PARA AUTENTICACIÓN GENERAL (EMAIL/PASSWORD) 

Ubicados en la ruta `/src/services/resources/controllers`

### 1. ENDPOINT PARA AUTENTICAR ADMINISTRADOR 

```bash
curl -X POST http://18.117.9.170:80/api/adminLogin.php
```
- Endpoint utilizado para autenticar un administrador
- crea sesión
- sessionData is delivered as a JSON. Needs to be parsed to get the object

#### Objeto que responde con http status 200
`{
  "status": "success",
  "data": [],
  "sessionData": {user: {}, roles: []},
  "error": {
    "errorCode": 401,
    "errorMessage": "Incorrect email or password"
  }
}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: []}`
