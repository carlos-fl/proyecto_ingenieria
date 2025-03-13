# ENDPOINTS PARA AUTENTICACIÓN GENERAL (EMAIL/PASSWORD) 

Ubicados en la ruta `/src/services/resources/controllers`

### 1. ENDPOINT PARA OBTENER IMÁGENES DE EVENTOS

```bash
curl -X GET http://18.117.9.170:80/proyecto_ingenieria/src/services/resources/controllers/eventImages.php
```
- Endpoint utilizado para obtener las rutas de imágenes de eventos próximos no cancelados. Útil para el slide (carrusel) en vista del frontend.
- No necesita autenticación
- No crea sesión

#### Objeto que responde con http status 200
`{"status": "success", data: [{IMAGE: 'image_path'}]}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure", data: []}`
