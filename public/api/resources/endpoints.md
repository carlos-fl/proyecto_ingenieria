# ENDPOINTS PARA OBTENER RECURSOS

### 1. ENDPOINT PARA OBTENER IMÁGENES DE EVENTOS

```bash
curl -X GET http://18.117.9.170:80/api/resources/controllers/eventImages.php
```
- Endpoint utilizado para obtener las rutas de imágenes de eventos próximos no cancelados. Útil para el slide (carrusel) en vista del frontend.
- No necesita autenticación
- No crea sesión

#### Objeto que responde con http status 200
`{"status": "success", data: [{IMAGE: 'image_path'}]}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: []}`

### 2. ENDPOINT PARA OBTENER INFORMACIÓN DE CARRERAS
```bash
curl -X GET http://18.117.9.170:80/api/resources/controllers/majorsData.php
```
- No necesita autenticación
- No crea sesión

#### Objeto que responde con http status 200
`{"status": "success", data: [{"MAJOR_NAME": "", "ICON": "path"}]}`


#### Objeto que responde con http status 200
`{"status": "failure", data: []}`

### 3. ENDPOINT PARA OBTENER CENTROS REGIONALES
```bash
curl -X GET http://18.117.9.170:80/api/resources/controllers/regionalCenters.php
```
- No necesita autenticación
- No crea sesión

#### Objeto que responde con http status 200
`{"status": "success", data: [{"CENTER_ID": , "CENTER_NAME"}]}`


#### Objeto que responde con http status 200
`{"status": "failure", data: []}`


### 4. ENDPOINT PARA OBTENER CARRERAS PERTENECIENTE A UN CENTRO REGIONAL
```bash
curl -X GET http://18.117.9.170:80/api/resources/controllers/majorsByCenter.php?center=id
```
- No necesita autenticación
- No crea sesión

#### Objeto que responde con http status 200
`{"status": "success", data: [{MAJOR_ID, MAJOR_NAME, MAJOR_SCORE, MAJOR_ICON}]}`


#### Objeto que responde con http status 200
`{"status": "failure", data: []}`

### 5. ENDPOINT PARA OBTENER CARRERAS POR NIVEL Y CENTRO REGIONAL
```bash
curl -X GET 'http://18.117.9.170:80/api/resources/controllers/majorsByLevelAndCenter.php?center=id&primary-major=id'
```
- No necesita autenticación
- No crea sesión

#### Objeto que responde con http status 200
`{"status": "success", data: [{MAJOR_ID, MAJOR_NAME, MAJOR_SCORE, MAJOR_ICON, MAJOR_LEVEL}]}`


#### Objeto que responde con http status 200
`{"status": "failure", data: []}`



