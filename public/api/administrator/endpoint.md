### 1. ENDPOINT QUE PERMITE AL ADMINISTRADOR INSERTAR UN NUEVO EMPLEADO

```bash
curl -i -X POST "http://localhost:8000/src/services/administrator/controllers/adminEmployees.php" \
     -H "Content-Type: application/json" \
     -d '{
           "dni": "123456789",
           "firstName": "Juan",
           "lastName": "Pérez",
           "phoneNumber": "987654321",
           "email": "juan.perez@example.com",
           "centerCode": 1,
           "gender": "M",
           "salary": 2500.50
         }'
```

#### Objeto que responde con http status 200
`{"status": "success"}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure"}`



### 2. ENDPOINT QUE PERMITE AL ADMINISTRADOR ASIGNAR ROLES

```bash
curl -i -X PUT "http://localhost:8000/api/administrator/controllers/adminRoles.php" \
     -H "Content-Type: application/json" \
     -d '{
          "dni": "0803200109123",
          "rolesCode": 2
     }'
```


#### Objeto que responde con http status 200
`{"status": "success"}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure"}`


### 3. ENDPOINT QUE PERMITE AL ADMINISTRADOR INSERTAR UN NUEVO PROCESSO

```bash
curl -i -X POST "http://localhost:8000/src/services/administrator/controllers/adminProcess.php" \
     -H "Content-Type: application/json" \
     -d '{
   	"proccessTypeCode": 1,
    	"PACCode": 2025,
    	"startDate": "2025-06-01 08:00:00",
    	"endDate": "2025-06-30 18:00:00"
	}'
```


#### Objeto que responde con http status 200
`{"status": "success"}`

#### Objeto que responde con http status distinto a 200
`{"status": "failure"}`

