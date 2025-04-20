# Módulo de Librería (Library)
Ubicación: `http://18.117.9.170:80/api/library/controllers/`
___
## 1. Libros para la carrera

#### Descripción
Obtiene una lista de libros disponibles para las carreras (majors) en las que está inscrito el estudiante autenticado.

- Requiere autenticación

#### Parámetros

| Nombre | Tipo | Ubicación | Requerido | Descripción          |
|--------|------|-----------|-----------|----------------------|
| `page` | int  | Query     | No        | Página de resultados |

#### Ejemplo de Request
```bash
curl -X GET http://18.117.9.170:80/api/library/controllers/booksByMajor.php?page=2
```

#### Respuesta

** 200 OK**
```json
{
  "status": "success",
  "data": [
    {
      "bookId": 51,
            "title": "Libro 1 para Major 103",
            "author": "Autor 1 Major 103",
            "uploadDate": "2025-04-06",
            "url": "\/views\/assets\/pruebapdf.pdf",
            "majorName": "Ingenier\u00eda El\u00e9ctrica",
            "tags": [
                "Social Sciences",
                "Sociology"
            ]
    },
    ...
  ],
  "totalPages": 5,
  "currentPage": 2
}
```

** 401 Unauthorized**
```json
{
  "status": "failure",
  "message": "User not logged in",
  "code": 401
}
```

** 500 Internal Server Error**
```json
{
  "status": "failure",
  "message": "Error fetching books by major: <mensaje de error>",
  "code": 500
}
```

#### Nota:
- Utiliza el procedimiento almacenado `SP_GET_BOOKS_BY_STUDENT_MAJOR`.
- Los libros son paginados en bloques de 6, pero puede ser cambiado en `$limit = 6;`.
- Solo se devuelven libros activos.

___


## 2. Libros por roles del docente


#### Descripción
Obtiene la lista de libros correspondientes a las carreras del docente autenticado, únicamente si posee el rol de COORDINADOR o JEFE DE DEPARTAMENTO además de DOCENTE.

- Requiere autenticación
- Debe poseer rol de:
  - `COORDINATOR`
  - `DEPARTMENT_CHAIR`
 

#### Parámetros

| Parámetro | Tipo   | Requerido | Descripción                                      |
|-----------|--------|-----------|--------------------------------------------------|
| page      | int    | Opcional  | Número de página (paginación). Por defecto es 1. |

#### Ejemplo de Request
```bash
curl -X GET http://18.117.9.170:80/api/library/controllers/booksByRol.php?page=1
```

#### Respuesta

**200 OK**
```json
{
    "status": "success",
    "data": [
        {
            "bookId": 51,
            "title": "Libro 1 para Major 103",
            "author": "Autor 1 Major 103",
            "uploadDate": "2025-04-06",
            "url": "\/views\/assets\/pruebapdf.pdf",
            "majorName": "Ingenier\u00eda El\u00e9ctrica",
            "tags": [
                "Social Sciences",
                "Sociology"
            ]
        }
    ],
    "totalPages": 6,
    "currentPage": 1
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

**403 Forbidden**
```json
{
  "status": "failure",
  "message": "Access denied",
  "code": 403
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "message": "Error fetching books: <mensaje de error>",
  "code": 500
}
```

#### Nota:
- Utiliza el procedimiento almacenado `SP_GET_BOOKS_BY_TEACHER_CAREERS`.
- Los libros son paginados en bloques de 6, pero puede ser cambiado en `$limit = 6;`.
- Solo se devuelven libros activos.
- Este endpoint es exclusivo para docentes con rol adicional de coordinación o jefe de departamento.

___

## 3. Libros para estudiante

#### Descripción
Obtiene la lista de libros asociados a las clases en las que el estudiante autenticado se ha inscrito.

- Requiere autenticación

#### Parámetros

| Nombre | Tipo | Ubicación | Requerido | Descripción          |
|--------|------|-----------|-----------|----------------------|
| `page` | int  | Query     | No        | Página de resultados |

#### Ejemplo de Request
```bash
curl -X GET http://18.117.9.170:80/api/library/controllers/booksByStudent.php?page=1
```

#### Respuesta

**200 OK**
```json
{
    "status": "success",
    "data": [
        {
            "bookId": 1,
            "title": "Libro 1 para Major 101",
            "author": "Autor 1 Major 101",
            "uploadDate": "2025-04-06",
            "url": "\/views\/assets\/pruebapdf.pdf",
            "className": "Introducci\u00f3n a la Ingenier\u00eda en Sistemas",
            "tags": [
                "Engineering",
                "Programming"
            ]
        }
    ],
    "totalPages": 3,
    "currentPage": 1
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

**403 Forbidden**
```json
{
  "status": "failure",
  "message": "Access denied",
  "code": 403
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "message": "Error fetching classes: <detalle del error>",
  "code": 500
}
```

#### Nota:
- Utiliza el procedimiento almacenado `SP_GET_BOOKS_BY_STUDENT`.
- Los libros son paginados en bloques de 6, pero puede ser cambiado en `$limit = 6;`.
- Se devuelven libros activos relacionados con clases inscritas por el estudiante.

___

## 4. Clases de un docente con rol

#### Descripción
Obtiene las clases asociadas a un docente que debe tener el rol de **Coordinador** o **Jefe de Departamento**.

- Requiere autenticación mediante sesión.
- Solo disponible para usuarios con rol: `COORDINATOR` o `DEPARTMENT_CHAIR`.

#### Parámetros

Utiliza la sesión del usuario autenticado. Se asume un máximo de clases debido a las unidades valorativas.
#### Ejemplo de Request
```bash
curl -X GET http://18.117.9.170:80/api/library/controllers/classesByAuthority.php
```

#### Respuesta

**200 OK**
```json
{
    "status": "success",
    "data": [
        {
            "classId": 5,
            "classCode": "IE-101",
            "className": "Circuitos El\u00e9ctricos",
            "uv": 4
        },
        {
            "classId": 6,
            "classCode": "IE-102",
            "className": "Electromagnetismo",
            "uv": 4
        }
    ]
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

**403 Forbidden**
```json
{
  "status": "failure",
  "message": "Access denied",
  "code": 403
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "message": "Error fetching classes: <detalle del error>",
  "code": 500
}
```

#### Nota:
- El procedimiento almacenado utilizado es `SP_GET_CLASSES_BY_AUTHORITY`.
- El número de docentes se obtiene de `$_SESSION["TEACHER_NUMBER"]` o se consigue con `TeacherService::getTeacherNumber()` si no está disponible en la sesión.

___

## 5. Crear nueva etiqueta de libros

#### Descripción
Endpoint que permite a coordinadores o jefes de departamento con rol de docente crear unas nuevas tags.  

- Requiere autenticación mediante sesión.
- Solo disponible para usuarios con rol: `COORDINATOR` o `DEPARTMENT_CHAIR`.

#### Parámetros

| Nombre     | Tipo   | Ubicación   | Requerido | Descripción                 |
|------------|--------|-------------|-----------|-----------------------------|
| `tagName`  | string | Body (JSON) | Sí        | Nombre de la nueva etiqueta |

#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170:80/api/library/controllers/createTag.php\
  -H "Content-Type: application/json" \
  -d '{ "tagName": "Nueva tag" }'
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "tagId": 15
}
```

**403 Forbidden**
```json
{
  "status": "failure",
  "message": "Access denied"
}
```

**400 Bad Request**
```json
{
  "status": "failure",
  "message": "Missing tag name"
}
```

**500 Internal Server Error**
```json
{
  "status": "failure",
  "message": "Error creating tag: <detalle>"
}
```

#### Nota:
- Utiliza el procedimiento almacenado `SP_CREATE_TAG`.

___

## 6. Eliminar libro (desactivar)

#### Descripción
Desactiva un libro registrado en el sistema. Solo los docentes con rol de **Coordinador** o **Jefe de Departamento** pueden ejecutar este endpoint. 

- Requiere autenticación mediante sesión.
- Solo disponible para usuarios con rol: `COORDINATOR` o `DEPARTMENT_CHAIR`.

#### Parámetros

| Nombre    | Tipo | Ubicación | Requerido | Descripción               |
|-----------|------|-----------|-----------|---------------------------|
| `bookId`  | int  | FormData  |   Sí      | ID del libro a desactivar |

#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170/api/library/controllers/deactivateBook.php \
     -F "bookId=12"
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "message": "Book deactivated successfully"
}
```

**400 Bad Request**
```json
{
  "status": "failure",
  "message": "Missing book ID"
}
```

**403 Forbidden**
```json
{
  "status": "failure",
  "message": "Access denied"
}
```



**500 Internal Server Error**
```json
{
  "status": "failure",
  "message": "Error deactivating book: <mensaje de error>"
}
```

#### Nota:
- Utiliza el procedimiento almacenado `SP_DEACTIVATE_BOOK`.
- Los libros no se eliminan, solo se desactivan (soft-delete).

___

## 7. Eliminar etiqueta

#### Descripción
Elimina una etiqueta del sistema (tag). Solo los docentes con rol de **Coordinador** o **Jefe de Departamento** pueden ejecutar este endpoint

- Requiere autenticación mediante sesión.
- Solo disponible para usuarios con rol: `COORDINATOR` o `DEPARTMENT_CHAIR`.

#### Parámetros

| Nombre   | Tipo | Ubicación   | Requerido | Descripción                  |
|----------|------|-------------|-----------|------------------------------|
| `tagId`  | int  | Body (JSON) | Sí        | ID de la etiqueta a eliminar |

#### Ejemplo de Request
```bash
curl -X DELETE http://18.117.9.170/api/library/controllers/deleteTag.php \
     -H "Content-Type: application/json" \
     -d '{"tagId": 7}'
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "message": "Tag deleted successfully"
}
```

**400 Bad Request**
```json
{
  "status": "failure",
  "message": "Missing tag ID"
}
```

**403 Forbidden**
```json
{
  "status": "failure",
  "message": "Access denied"
}
```



**500 Internal Server Error**
```json
{
  "status": "failure",
  "message": "Error deleting tag: <mensaje de error>"
}
```

#### Nota:
- Utiliza el procedimiento almacenado `SP_DELETE_TAG`.

___

## 8. Obtener etiqueta

#### Descripción
Devuelve la lista completa de etiquetas (tags) registradas en el sistema.

- No requiere autenticación

#### Parámetros

No recibe ningún parámetro.

#### Ejemplo de Request
```bash
curl -X GET http://18.117.9.170/api/library/controllers/getTags.php'
```

#### Respuesta

**200 OK**
```json
{
    "status": "success",
    "data": [
        {
            "tagId": 33,
            "tagName": "Accounting"
        }
    ]
}

**500 Internal Server Error**
```json
{
  "status": "failure",
  "message": "Error fetching tags: <mensaje de error>",
  "code": 500
}
```

#### Nota:
- Utiliza el procedimiento almacenado `SP_GET_ALL_TAGS`.

___

## 9. Subir libro

#### Descripción
Permite a un coordinador o jefe de departamento subir un nuevo libro, asociado a una clase, carrera y etiquetas (tags), estas últimas son opcionales.

- Requiere autenticación mediante sesión.
- Solo disponible para usuarios con rol: `COORDINATOR` o `DEPARTMENT_CHAIR`.

#### Parámetros

| Nombre       | Tipo    | Ubicación | Requerido | Descripción                                         |
|--------------|---------|-----------|-----------|-----------------------------------------------------|
| `title`      | string  | FormData  | Sí        | Título del libro                                    |
| `author`     | string  | FormData  | Sí        | Autor del libro                                     |
| `idClass`    | int     | FormData  | Sí        | ID de la clase a la que se asocia el libro          |
| `tags_name`  | string[]| FormData  | No        | Arreglo de nombres de etiquetas                     |
| `file`       | file    | FormData  | Sí        | Archivo PDF del libro a subir                       |

#### Ejemplo de Request
```bash
curl -X POST http://18.117.9.170/api/library/controllers/uploadBook.php \
  -F "title=Introducción a la Lógica" \
  -F "author=Juan Pérez" \
  -F "idClass=12" \
  -F "tags_name[]=Filosofía" \
  -F "tags_name[]=Lógica" \
  -F "file=@/ruta/a/archivo.pdf"
```

#### Respuesta

**200 OK**
```json
{
  "status": "success",
  "bookId": 101
}
```

**400 Bad Request**
```json
{
  "status": "failure",
  "message": "Missing required fields"
}
```

**403 Forbidden**
```json
{
  "status": "failure",
  "message": "No se encontró una carrera asociada al docente"
}

{
  "status": "failure",
  "message": "Access denied",
  "code": 403
}

```



**500 Internal Server Error**
```json
{
  "status": "failure",
  "message": "Error uploading book: <mensaje del error>"
}
```

#### Nota:
- Utiliza el procedimiento almacenado `SP_INSERT_BOOK`.
- Si el tag ya existe, se reutiliza, de lo contrario este se creará.