# Módulo de Librería (Library)
Ubicación: `http://18.117.9.170:80/api/library/controllers/`
___
## 1. Agregar miembro a un grupo

#### Descripción
Permite que un estudiante autenticado agregue a otro estudiante a un grupo existente. Solo los miembros actuales del grupo pueden agregar a nuevos miembros. El nuevo miembro puede ser identificado por su ID o por su correo institucional.

- Requiere autenticación

#### Parámetros

| Nombre             | Tipo   | Ubicación   | Requerido | Descripción                                  |
|--------------------|--------|-------------|-----------|----------------------------------------------|
| `groupId`          | int    | Body (JSON) | Sí        | Identificador del grupo                      |
| `memberIdentifier` | string | Body (JSON) | Sí        | ID del estudiante o correo institucional     |


#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/addGroupMember.php
Content-Type: application/json
Cookie: PHPSESSID=1
{
  "groupId": 15,
  "memberIdentifier": "2"
}
```
```bash
POST /api/students/controllers/addGroupMember.php
Content-Type: application/json
Cookie: PHPSESSID=1
{
  "groupId": 15,
  "memberIdentifier": "maria.sinstitutional@unah.hn"
}
```
#### Respuesta

** 200 OK**
```json
{
  "status": "success"
}
```

** 400 Bad request**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 400,
    "errorMessage": "Missing group ID or member identifier"
  }
}
```

** 401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 401,
    "errorMessage": "Unauthorized"
  }
}
```

** 500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 500,
    "errorMessage": "Error message returned from the stored procedure"
  }
}
```

#### Nota:
- Utiliza el procedimiento almacenado `SP_ADD_GROUP_MEMBERS`.
- Solo los estudiantes miembros del grupo pueden agregar a otros miembros.
- El identificador puede ser un número de cuenta (ID) o un correo institucional.

___


## 2. Crear grupo de chat


#### Descripción
Permite que un estudiante autenticado cree un nuevo grupo de chat, del cual será automáticamente el creador y primer miembro.

- Requiere autenticación

 

#### Parámetros

| Parámetro | Tipo   | Requerido | Descripción                                      |
|-----------|--------|-----------|--------------------------------------------------|
| page      | int    | Opcional  | Número de página (paginación). Por defecto es 1. |

#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/createGroup.php
Content-Type: application/json
Cookie: PHPSESSID=1

{
  "groupName": "Grupo"
}
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": {
    "groupId": 17
  }
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 401,
    "errorMessage": "Unauthorized"
  }
}
```

**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 400,
    "errorMessage": "Group name is required"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 500,
    "errorMessage": "Error message returned from the stored procedure"
  }
}
```

#### Nota:
- Utiliza el procedimiento almacenado `SP_CREATE_GROUP`.
- El estudiante autenticado se convierte automáticamente en miembro del grupo y el owner.
- El nombre del grupo no puede estar vacío.

___

## 3. Eliminar grupo de chat

#### Descripción
Permite al estudiante autenticado eliminar un grupo de chat del cual es el creador. Solo el creador del grupo puede eliminarlo.


- Requiere autenticación
- Solo el administrador del grupo (owner) puede usarlo

#### Parámetros

| Nombre      | Tipo   | Ubicación    | Requerido | Descripción                |
|-------------|--------|--------------|-----------|----------------------------|
| `groupId`   | int    | Body (JSON)  | Sí        | ID del grupo a eliminar    |


#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/deleteGroup.php
Content-Type: application/json
Cookie: PHPSESSID=1
{
  "groupId": 17
}
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "error": null
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "message": "Unauthorized",
  "code": 401
}
```

**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 400,
    "errorMessage": "Missing or invalid groupId"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 500,
    "errorMessage": "Error message returned from the stored procedure"
  }
}
```

#### Nota:
- Utiliza el procedimiento almacenado `SP_DELETE_GROUP`.
- Los libros son paginados en bloques de 6, pero puede ser cambiado en `$limit = 6;`.
- El procedimiento verifica internamente si el estudiante tiene permiso para eliminar el grupo.

___

## 4. Rechazar o bloquear contacto

#### Descripción
Permite al estudiante autenticado rechazar o bloquear a otro estudiante en el sistema de mensajería. El contacto dejará de aparecer en el listado de contactos.


- Requiere autenticación mediante sesión.

#### Parámetros

| Nombre     | Tipo   | Ubicación   | Requerido | Descripción                                     |
|------------|--------|-------------|-----------|-------------------------------------------------|
| `targetId` | int    | Body (JSON) | Sí        | ID del estudiante con el que se tiene contacto  |
| `action`   | string | Body (JSON) | Sí        | Acción a tomar: `"REJECTED"` o `"BLOCKED"`      |

#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/deleteOrBlockContact.php
Content-Type: application/json
Cookie: PHPSESSID=1
{
  "targetId": 17,
  "action": "BLOCKED"
}
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "error": null
}
```
**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 400,
    "errorMessage": "Missing or invalid fields"
  }
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 401,
    "errorMessage": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 500,
    "errorMessage": "Error message returned from the stored procedure"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_UPDATE_CONTACT_STATUS`.
- Solo se permiten las acciones "REJECTED" y "BLOCKED"
  
___

## 7. Obtener conversación

#### Descripción
Obtiene los mensajes entre un estudiante y otros, y/o grupos. La conversación se retorna en orden descendente desde el mensaje más reciente. Soporta paginación basada en el `lastMessageId`, esta paginación es manejada mediante el scroll del mouse.

- Requiere Autenticación

#### Parámetros

| Nombre           | Tipo    | Ubicación | Requerido | Descripción                           |
|------------------|---------|-----------|-----------|---------------------------------------|
| `receiverId`     | int     | Query     | Sí        | ID del receptor (otro estudiante o grupo)                                              |
| `receiverType`  | string  | Query     | Sí        | Tipo de receptor: `"STUDENT"` o `"GROUP"`                                              |
| `lastMessageId` | int     | Query     | No        | ID del último mensaje recibido (para paginación hacia atrás)                          |

#### Ejemplo de Request
```bash
curl -X GET http://18.117.9.170:80/api/students/controllers/getConversation.php?receiverId=12&receiverType=STUDENT&lastMessageId=100
```

#### Respuesta

**200 OK**
```json
{
    "status": "success",
    "data": [
        {
            "messageId": 27,
            "senderId": 3,
            "receiverId": 28,
            "receiverType": "STUDENT",
            "message": "Contenido del mensaje #27",
            "sentAt": "2024-07-06 09:28:49",
            "readStatus": "SENT",
            "senderName": "Andrea Cruz"
        }
    ],
    "hasMore": false
}
```
**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 400,
    "errorMessage": "Missing or invalid parameters"
  }
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 401,
    "errorMessage": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "errorCode": 500,
    "errorMessage": "Error message returned from the stored procedure"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_GET_CONVERSATION`.
- El resultado está paginado, con un máximo de 20 mensajes por llamada.
- El parámetro `lastMessageId` permite cargar mensajes anteriores en bloques.
  
___

## 10. Bandeja de entrada de grupos

#### Descripción
Obtiene los últimos mensajes de grupos a los que pertenece el estudiante autenticado, paginados.

- Requiere Autenticación

#### Parámetros

| Nombre   | Tipo | Ubicación | Requerido | Descripción                          |
|----------|------|-----------|-----------|--------------------------------------|
| `page`   | int  | Query     | No        | Página de resultados (por defecto 1) |

#### Ejemplo de Request
```bash
curl -X GET "http://18.117.9.170:80/api/students/controllers/getGroupInbox.php?page=1"
```

#### Respuesta

**200 OK**
```json
{
    "status": "success",
    "data": [
        {
            "groupId": 7,
            "groupName": "GRUPO DE PRUEBA",
            "ownerId": 5,
            "ownerName": "Luis Cruz",
            "membersCount": 4,
            "lastMessage": "22",
            "lastMessageDate": "2025-04-15 04:18:39",
            "readStatus": "SENT"
        },
        {
            "groupId": 5,
            "groupName": "Grupo #5",
            "ownerId": 6,
            "ownerName": "Mar\u00eda Cruz",
            "membersCount": 7,
            "lastMessage": "Hola",
            "lastMessageDate": "2025-04-15 03:43:42",
            "readStatus": "SENT"
        }
    ],
    "totalPages": 1,
    "currentPage": 1
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_GET_GROUP_INBOX_MESSAGES`.
- El resultado está paginado, con un máximo de 30 grupos por llamada.
- 
___

## 11. Obtener miembros del grupo

#### Descripción
Obtiene una lista paginada de los miembros de un grupo.

- Requiere Autenticación

#### Parámetros

| Nombre     | Tipo | Ubicación | Requerido | Descripción                            |
|------------|------|-----------|-----------|----------------------------------------|
| `groupId`  | int  | Query     | Sí        | ID del grupo del cual obtener miembros |
| `page`     | int  | Query     | No        | Página de resultados (por defecto 1)   |

#### Ejemplo de Request
```bash
curl -X GET "http://18.117.9.170:80/api/students/controllers/getGroupMembers.php?groupId=12&page=1"
```

#### Respuesta

**200 OK**
```json
{
    "status": "success",
    "data": [
        {
            "studentId": 5,
            "fullName": "Luis Cruz",
            "institutionalEmail": "luis.sinstitutional@unah.hn",
            "isOwner": 1
        },
        {
            "studentId": 3,
            "fullName": "Andrea Cruz",
            "institutionalEmail": "andrea.sinstitutional@unah.hn",
            "isOwner": 0
        }
    ],
    "totalPages": 1,
    "currentPage": 1
}
```
**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "code": 400,
    "message": "Missing groupId"
  }
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_GET_GROUP_MEMBERS`.
- El resultado está paginado, con un máximo de 20 miembros por llamada.
  
___

## 12. Obtener bandeja de mensajes

#### Descripción
Obtiene una lista de conversaciones del estudiante, estas conversaciones se encuentran paginadas. Cada entrada representa un contacto único con el que ha intercambiado mensajes.


- Requiere Autenticación

#### Parámetros


| Nombre   | Tipo | Ubicación | Requerido | Descripción                          |
|----------|------|-----------|-----------|--------------------------------------|
| `page`   | int  | Query     | No        | Página de resultados (por defecto 1) |

#### Ejemplo de Request
```bash
curl -X GET "http://18.117.9.170:80/api/students/controllers/getInbox.php?page=1"
```

#### Respuesta

**200 OK**
```json
{"status":"success","data":
[
{
"chat_id":8,
"full_name":"Mario Castillo",
"institutional_email":"mario.sinstitutional@unah.hn",
"last_message":"Contenido del mensaje #2",
"last_message_date":"2024-12-30 09:28:49",
"READ_STATUS":"SENT"
},
{
"chat_id":28,
"full_name":"Valeria Torres",
"institutional_email":"valeria.stuinstitutional@unah.hn",
"last_message":"Contenido del mensaje #22",
"last_message_date":"2024-11-08 09:28:49",
"READ_STATUS":"SENT"}
 "currentPage": 1
}
```
**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_GET_INBOX_MESSAGES`.
- El resultado está paginado, con un máximo de 10 conversaciones por llamada.
- La bandejada esta ordenada por fecha, no se manejan en este endpoint el llamado de las conversaciones de un grupo.
  
___

## 14. Obtener bandeja de mensajes

#### Descripción
Obtiene una lista de conversaciones del estudiante, estas conversaciones se encuentran paginadas. Cada entrada representa un contacto único con el que ha intercambiado mensajes.


- Requiere Autenticación

#### Parámetros


| Nombre   | Tipo | Ubicación | Requerido | Descripción                          |
|----------|------|-----------|-----------|--------------------------------------|
| `page`   | int  | Query     | No        | Página de resultados (por defecto 1) |


#### Ejemplo de Request
```bash
curl -X GET "http://18.117.9.170:80/api/students/controllers/getPendingContactRequest.php?page=1"
```

#### Respuesta

**200 OK**
```json
{
    "status": "success",
    "data": [
        {
            "ID_STUDENT": 3,
            "FIRST_NAME": "Andrea",
            "LAST_NAME": "Cruz",
            "INST_EMAIL": "andrea.sinstitutional@unah.hn",
            "PHOTO": "\/views\/assets\/img\/default-profile.png"
        }
    ],
    "totalPages": 1,
    "currentPage": 1
}
```
**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_GET_PENDING_CONTACT_REQUESTS`.
- El resultado está paginado, con un máximo de 10 conversaciones por llamada.
- El endpoint solo retorna solicitudes en estado PENDING.
  
___

## 15. Historial de clases del estudiante

#### Descripción
Obtiene el historial académico de clases de un estudiante.


- Requiere Autenticación

#### Parámetros

Utiliza el Student Id de la sesion.


#### Ejemplo de Request
```bash
curl -X GET "http://18.117.9.170:80/api/students/controllers/getStudentClassHistory.php?student-id=4"
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": [
    {"classCode":"IM-101",
"className":"Termodin\u00e1mica",
"uv":4,
"section":"S3T2",
"year":2025,
"period":1,
"calification":89,
"status":"APB"
}
  ]
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**404 Not Found**
```json
{
  "status": "failure",
  "error": {
    "code": 404,
    "message": "No class history found for student ID 4"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_GET_STUDENT_CLASS_HISTORY`.
  
___

## 16. Obtener contactos del estudiante

#### Descripción
Obtiene una lista paginada de los contactos del estudiante.

- Requiere Autenticación

#### Parámetros


| Nombre   | Tipo | Ubicación | Requerido | Descripción                          |
|----------|------|-----------|-----------|--------------------------------------|
| `page`   | int  | Query     | No        | Página de resultados (por defecto 1) |


#### Ejemplo de Request
```bash
curl -X GET "http://18.117.9.170:80/api/students/controllers/getStudentContacts.php?page=1"
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": [
{
"studentId":20,
"fullName":"Andrea D\u00edaz",
"institutionalEmail":"andrea.stinstitutional@unah.hn",
"profilePhoto":"\/views\/assets\/img\/default-profile.png"
}
  ],"totalPages":1,"currentPage":1}

```
**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_GET_STUDENT_CONTACTS`.
- El resultado está paginado, con un máximo de 20 contactos por llamada.
- Los contactos son una confirmación mutua de amistad, por lo tanto, si “x” tiene a “z” agregado como contacto, significa que “z” aceptó en algún momento ser un contacto con “x”.
  
___

## 17. Obtener grupos del estudiante

#### Descripción
Devuelve una lista paginada de los grupos en los que el estudiante es miembro activo.

- Requiere Autenticación

#### Parámetros


| Nombre   | Tipo | Ubicación | Requerido | Descripción                          |
|----------|------|-----------|-----------|--------------------------------------|
| `page`   | int  | Query     | No        | Página de resultados (por defecto 1) |


#### Ejemplo de Request
```bash
curl -X GET "http://18.117.9.170:80/api/students/controllers/getStudentGroups.php?page=1"
```

#### Respuesta

**200 OK**
```json
{"status":"success",
"data":
[
{"groupId":3,
"groupName":"Grupo #3",
"ownerId":4,
"ownerName":"Juan Perez",
"isOwner":0,
"membersCount":6}
],
"totalPages":1,"currentPage":1,
"error":null
}
```
**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_GET_STUDENT_GROUPS`.
- El resultado está paginado, con un máximo de 10 grupos por llamada.
  
___

## 18. Obtener información del estudiante

#### Descripción
Devuelve la información personal del estudiante, nombre, correo, descripción y otros detalles relevantes.

- Requiere Autenticación

#### Parámetros

Utiliza el Student Id de la sesion.

#### Ejemplo de Request
```bash
curl -X GET http://18.117.9.170:80/api/students/controllers/getStudentPersonalInfo.php
```

#### Respuesta

**200 OK**
```json
{"status":"success","data":
{"studentId":19,
"studentAccountNumber":20251001019,
"description":"Description for student 19",
"firstName":"Camila",
"lastName":"D\u00edaz",
"email":"camila.sinstitutional@unah.hn",
"phone":"99806569",
"profilePhoto":"\/public\/views\/assets\/img\/default-profile.png"},
"error":null}

```
**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```
**404 Not Found**
```json
{
  "status": "failure",
  "error": {
    "code": 404,
    "message": "NOT FOUND"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_GET_STUDENT_PERSONAL_INFO`.
- Solo el estudiante puede acceder a su información personal.
  
___

## 19. Obtener perfil público del estudiante

#### Descripción
Este endpoint devuelve el perfil del estudiante, incluyendo su descripción y fotos asociadas al perfil.

- Requiere Autenticación

#### Parámetros

Utiliza el Student Id de la sesion.

#### Ejemplo de Request
```bash
curl -X GET http://18.117.9.170:80/api/students/controllers/getStudentProfile.php
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": {
    "description": "Estudiante",
    "photos": [
      "profile1.jpg",
      "profile2.jpg"
    ]
  },
  "error": null
}

```
**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```
**404 Not Found**
```json
{
  "status": "failure",
  "error": {
    "code": 404,
    "message": "NOT FOUND"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_GET_STUDENT_PROFILE`.
- Este endpoint esta descontinuado, actualmente se utiliza el endpoint getStudentPersonalInfo.
  
___

## 20. Salir del grupo

#### Descripción
Permite a un estudiante abandonar un grupo al que pertenece.

- Requiere Autenticación

#### Parámetros

| Nombre     | Tipo   | Ubicación   | Requerido | Descripción              |
|------------|--------|-------------|-----------|--------------------------|
| `groupId`  | int    | Body (JSON) | Sí        | ID del grupo a abandonar |

#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/leaveGroup.php
  -H "Content-Type: application/json"
  -d '{"groupId": 45}'
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": null,
  "error": null
}

```

**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "code": 400,
    "message": "Missing groupId"
  }
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_LEAVE_GROUP`.
- El creador del grupo no puede abandonar un grupo, debe transferir primero la propiedad a otra persona, de otro modo se tendría que eliminar el grupo.
  
___

## 20. Salir del grupo

#### Descripción
Permite a un estudiante abandonar un grupo al que pertenece.

- Requiere Autenticación

#### Parámetros

| Nombre     | Tipo   | Ubicación   | Requerido | Descripción              |
|------------|--------|-------------|-----------|--------------------------|
| `groupId`  | int    | Body (JSON) | Sí        | ID del grupo a abandonar |

#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/leaveGroup.php
  -H "Content-Type: application/json"
  -d '{"groupId": 45}'
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": null,
  "error": null
}

```

**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "code": 400,
    "message": "Missing groupId"
  }
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_LEAVE_GROUP`.
- El creador del grupo no puede abandonar un grupo, debe transferir primero la propiedad a otra persona, de otro modo se tendría que eliminar el grupo.
  
___

## 22. Eliminar miembro de grupo

#### Descripción
Permite al creador del grupo eliminar a un miembro del grupo.

- Requiere Autenticación

#### Parámetros

| Nombre      | Tipo | Ubicación   | Requerido | Descripción                            |
|-------------|------|-------------|-----------|----------------------------------------|
| `groupId`   | int  | Body (JSON) | Sí        | ID del grupo                           |
| `memberId`  | int  | Body (JSON) | Sí        | ID del estudiante a eliminar del grupo |

#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/removeGroupMember.php
  -H "Content-Type: application/json"
  -d '{"groupId": 45, "memberId": 99}'
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": null,
  "error": null
}

```

**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "code": 400,
    "message": "Missing groupId or memberId"
  }
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_REMOVE_GROUP_MEMBER`.
  
___

## 23. Responder solicitud de contacto

#### Descripción
Permite a un estudiante responder a una solicitud de contacto recibida, aceptando, rechazando o bloqueando al solicitante.

- Requiere Autenticación

#### Parámetros

| Nombre         | Tipo   | Ubicación   | Requerido | Descripción                                       |
|----------------|--------|-------------|-----------|--------------------------------------------------|
| `requesterId`  | int    | Body (JSON) | Sí        | ID del estudiante que envió la solicitud |
| `action`       | string | Body (JSON) | Sí        | Acción a realizar: `ACCEPT`, `REJECT` o `BLOCK` |

#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/respondContactRequest.php
  -H "Content-Type: application/json"
  -d '{"requesterId": 9, "action": "ACCEPT"}'
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": null,
  "error": null
}

```

**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "code": 400,
    "message": "Invalid requester or action"
  }
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from the server"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_UPDATE_CONTACT_STATUS`.
- Actualmente, no se maneja la opción de “bloqueado”, pero el endpoint está completamente listo por si se implementa en la base de datos, también el sp maneja la lógica para bloquear.
  
___

## 24. Enviar solicitud de contacto

#### Descripción
Permite a un estudiante enviar una solicitud de contacto a otro estudiante usando su correo institucional.

- Requiere Autenticación

#### Parámetros

| Nombre          | Tipo   | Ubicación   | Requerido | Descripción                              |
|-----------------|--------|-------------|-----------|------------------------------------------|
| `receiverEmail` | string | Body (JSON) | Sí        | Correo institucional del destinatario    |


#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/sendFriendRequest.php
  -H "Content-Type: application/json"
  -d '{"receiverEmail": "juan.lopez@unah.hn"}'
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": null,
  "error": null
}

```

**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "code": 400,
    "message": "Missing receiver email"
  }
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**404 Not Found**
```json
{
  "status": "failure",
  "error": {
    "code": 404,
    "message": "Correo institucional no encontrado"
  }
}
```

**409 Conflict**
```json
{
  "status": "failure",
  "error": {
    "code": 409,
    "message": "Ya existe una solicitud entre los usuarios"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error interno del servidor"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_SEND_CONTACT_REQUEST`.
- El procedimiento almacenado valida que el correo exista, que no se haya enviado una solicitud previa, y que no estén ya conectados.
  
___

## 24. Enviar mensaje

#### Descripción
Permite a un estudiante enviar un mensaje a otro estudiante o a un grupo.

- Requiere Autenticación

#### Parámetros

| Nombre          | Tipo   | Ubicación   | Requerido | Descripción                                             |
|-----------------|--------|-------------|-----------|---------------------------------------------------------|
| `receiverId`    | int    | Body (JSON) | Sí        | ID del destinatario (otro estudiante o grupo)          |
| `receiverType`  | string | Body (JSON) | Sí        | Tipo de destinatario: `STUDENT` o `GROUP`           |
| `content`       | string | Body (JSON) | Sí        | Contenido del mensaje                                   |



#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/sendMessage.php
  -H "Content-Type: application/json"
  -d '{
    "receiverId": 45,
    "receiverType": "STUDENT",
    "content": "¡Hola Mundo!"
}'
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": null,
  "error": null
}

```

**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "code": 400,
    "message": "Missing or invalid required fields"
  }
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error interno del servidor"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_SEND_MESSAGE`.
- El tipo `receiverType` debe ser exactamente `STUDENT` o `GROUP`, de esta manera se diferencia a quien se está enviando el mensaje con exactitud.
  
___

## 25. Transferir propiedad de grupo

#### Descripción
Permite al creador de un grupo transferir su propiedad a otro miembro.

- Requiere Autenticación

#### Parámetros

| Nombre         | Tipo | Ubicación   | Requerido | Descripción                                          |
|----------------|------|-------------|-----------|------------------------------------------------------|
| `groupId`      | int  | Body (JSON) | Sí        | ID del grupo cuya propiedad será transferida |
| `newOwnerId`   | int  | Body (JSON) | Sí        | ID del nuevo propietario del grupo                   |



#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/transferGroupOwnership.php
  -H "Content-Type: application/json"
  -d '{
    "groupId": 12,
    "newOwnerId": 45
}'
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": null,
  "error": null
}

```

**400 Bad Request**
```json
{
  "status": "failure",
  "error": {
    "code": 400,
    "message": "Missing required parameters"
  }
}
```

**401 Unauthorized**
```json
{
  "status": "failure",
  "error": {
    "code": 401,
    "message": "Unauthorized"
  }
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "error": {
    "code": 500,
    "message": "Error message from exception"
  }
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_TRANSFER_GROUP_OWNERSHIP`.
- El nuevo propietario debe ser un miembro existente del grupo.
  
___

## 26. Actualizar información personal del estudiante

#### Descripción
Permite al estudiante autenticado actualizar su número de teléfono y descripción personal.

- Requiere Autenticación

#### Parámetros

| Nombre         | Tipo   | Ubicación   | Requerido | Descripción                       |
|----------------|--------|-------------|-----------|-----------------------------------|
| `phone`        | string | Body (JSON) | Sí        | Número de teléfono del estudiante |
| `description`  | string | Body (JSON) | Sí        | Descripción personal o biografía  |



#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/updateStudentPersonalInfo.php
  -H "Content-Type: application/json"
  -d '{
    "phone": "99887766",
    "description": "Estudiante de Ingeniería en Sistemas"
}'
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": null,
  "error": null
}

```

**400 Bad Request**
```json
{
  "status": "error",
  "message": "Faltan datos requeridos."
}
```

**401 Unauthorized**
```json
{
  "status": "error",
  "message": "Usuario no autenticado."
}
```

**500 Internal Server Error**
```json
{
  "status": "error",
  "message": "Error al ejecutar el stored procedure."
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_UPDATE_STUDENT_PERSONAL_INFO`.
- Este endpoint fue sustituido por updateStudentProfile.php
___

## 27. Actualizar perfil del estudiante

#### Descripción
Permite al estudiante autenticado actualizar su número de teléfono y descripción personal.

- Requiere Autenticación

#### Parámetros

| Nombre         | Tipo   | Ubicación   | Requerido | Descripción                       |
|----------------|--------|-------------|-----------|-----------------------------------|
| `phone`        | string | Body (JSON) | Sí        | Número de teléfono del estudiante |
| `description`  | string | Body (JSON) | Sí        | Descripción personal o biografía  |



#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/students/controllers/updateStudentProfile.php
  -H "Content-Type: application/json"
  -d '{
    "phone": "99887766",
    "description": "Estudiante de Ingeniería en Sistemas"
}'
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "data": null,
  "error": null
}

```

**400 Bad Request**
```json
{
  "status": "error",
  "message": "Faltan datos requeridos."
}
```

**401 Unauthorized**
```json
{
  "status": "error",
  "message": "Usuario no autenticado."
}
```

**500 Internal Server Error**
```json
{
  "status": "error",
  "message": "Error al ejecutar el stored procedure."
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_UPDATE_STUDENT_INFO`.
___