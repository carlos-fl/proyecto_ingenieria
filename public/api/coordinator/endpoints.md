# ENPOINTS FOR COORDINATOR

### 1. ENDPOINT LAS CARGAS ACADÉMICAS

```bash
curl -X GET http://18.117.9.170:80/api/coordinator/controllers/academicLoad.php
```
- Endpoint para traer las cargas académicas de para un coordinador.
- Necesita autenticación
- No crea sesión

#### Objeto que responde con http status 200
`{"status": "success", data: [], error: null}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: [], error: {errorCode: 500, errorMessage: "server error"}}`

### 2. ENDPOINT PARA ACEPTAR UNA SOLICITUD

```bash
curl -X PUT http://18.117.9.170:80/api/coordinator/controllers/acceptRequest.php?q=1&type="MAJORCHANGE"
```
- Endpoint para aceptar una solicitud realizada por un estudiante
- Necesita autenticación
- No crea sesión

#### Objeto que responde con http status 200
`{"status": "success", data: [], error: null}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: [], error: {errorCode: 500, errorMessage: "server error"}}`

### 3. ENDPOINT PARA CANCELAR UNA CLASE

```bash
curl -X POST http://18.117.9.170:80/api/coordinator/controllers/cancelStudentCurrentClass.php -d '{ "sectionID": 1, "requestID: 2"}'
```
- Endpoint para Cancelar la clase de un estudiante realizada en solicitud de cancelaciones excepcionales
- Necesita autenticación
- No crea sesión

#### Objeto que responde con http status 200
`{"status": "success", data: [], error: null}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: [], error: {errorCode: 500, errorMessage: "server error"}}`

### 4. ENDPOINT MARCAR COMO REALIZADA UNA SOLICITUD DE CANCELACIÓN DE CLASES

```bash
curl -X POST http://18.117.9.170:80/api/coordinator/controllers/completedCancellationRequest.php -d '{ "requestID: 2"}'
```
- Endpoint para actualizar las solicitudes de cancelación de clases y marcarlas como completada
- Necesita autenticación
- No crea sesión

#### Objeto que responde con http status 200
`{"status": "success", data: [], error: null}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: [], error: {errorCode: 500, errorMessage: "server error"}}`

### 5. ENDPOINT PARA OBTENER LOS PDF DE UNA SOLICITUD

```bash
curl -X GET http://18.117.9.170:80/api/coordinator/controllers/getCancellationPDF.php?q=2
```
- Endpoint para obtener el PDF de una solicitud de cancelación de clases en cancelaciones excepcionales
- Necesita autenticación
- No crea sesión
- el parámetro q en la url es el requestID

#### Objeto que responde con http status 200
`{"status": "success", data: [], error: null}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: [], error: {errorCode: 500, errorMessage: "server error"}}`


### 6. ENDPOINT PARA OBTENER LAS CLASES EN CURSO DE UN ESTUDIANTE

```bash
curl -X GET http://18.117.9.170:80/api/coordinator/controllers/getCurrentStudentClasses.php?request=1
```
- Endpoint para obtener las clases que un estudiante tiene matriculadas en el PAC actual
- Necesita autenticación
- No crea sesión
- el parámetro request en la url es el requestID

#### Objeto que responde con http status 200
`{"status": "success", data: [], error: null}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: [], error: {errorCode: 500, errorMessage: "server error"}}`




### 7. ENDPOINT PARA OBTENER SOLICITUDES PARA UN COORDINADOR

```bash
curl -X GET http://18.117.9.170:80/api/coordinator/controllers/requests.php?q='CANCELLATION'
```
- Endpoint para obtener las solicitudes por tipo
- Necesita autenticación
- No crea sesión
- el parámetro q en la url es el tipo de solicitud y puede ser: CANCELLATION, MAJORCHANGE, CAMPUSTRANSFER

#### Objeto que responde con http status 200
`{"status": "success", data: [], error: null}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: [], error: {errorCode: 500, errorMessage: "server error"}}`



### 8. ENDPOINT PARA OBTENER EL HISTORIAL DE UN ESTUDIANTE

```bash
curl -X GET http://18.117.9.170:80/api/coordinator/controllers/studentHistory.php?account=20221001802
```
- Endpoint para obtener el historial de clases de un estudiante
- Necesita autenticación
- No crea sesión
- el parámetro account es el número de cuenta de un estudiante

#### Objeto que responde con http status 200
`{"status": "success", data: [], error: null}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: [], error: {errorCode: 500, errorMessage: "server error"}}`


### 9. ENDPOINT PARA OBTENER UN ESTUDIANTE

```bash
curl -X GET http://18.117.9.170:80/api/coordinator/controllers/students.php?account=20221001802
```
- Endpoint para un estudiante
- Necesita autenticación
- No crea sesión
- El parámetro account es el número de cuenta del estudiante a buscar

#### Objeto que responde con http status 200
`{"status": "success", data: [], error: null}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: [], error: {errorCode: 500, errorMessage: "server error"}}`


### 10. ENDPOINT PARA OBTENER LISTADO DE ESTUDIANTES

```bash
curl -X GET http://18.117.9.170:80/api/coordinator/controllers/studentsAll.php
```
- Endpoint para obtener listado de todos los estudiantes
- Necesita autenticación
- No crea sesión

#### Objeto que responde con http status 200
`{"status": "success", data: [], error: null}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: [], error: {errorCode: 500, errorMessage: "server error"}}`




### 11. ENDPOINT PARA OBTENER ESTUDIANTES CON FILTROS

```bash
curl -X GET http://18.117.9.170:80/api/coordinator/controllers/studentsByFilter.php?campus=1&major=3
```
- Endpoint para obtener estudiantes
- Necesita autenticación
- No crea sesión
- El parámetro campus y major son los ids de campus y major seleccionados por el coordinador

#### Objeto que responde con http status 200
`{"status": "success", data: [], error: null}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: [], error: {errorCode: 500, errorMessage: "server error"}}`


