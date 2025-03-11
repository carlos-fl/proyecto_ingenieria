# Sistema de Registro de la UNAH

## Descripción
Este proyecto es un sistema basado en microservicios para la gestión de la Universidad Nacional Autónoma de Honduras. 
El sistema debe contar con los siguiente módulos
- admisiones 
- estudiantes
- matrícula
- docentes (incluyen jefe de departamento y coordinadores) 
- biblioteca virtual
- administradores

## Requisitos previos
- php 8.2
- mysql

## Instalación
- clonar el repositorio:
```bash
git clone https://github.com/carlos-fl/proyecto_ingenieria.git
```
- crear archivo .env con las variables del archivo `.env.example`

## Arquitectura
El proyecto se encuentra construido con microservicios, utilizando API REST y MVC
Los microservicios son los siguiente:
- admisiones: se encarga de procesar solicitudes de ingreso
- estudiantes: se encarga de la funcionalidades de los estudiantes
- docentes: incluye jefe de departamento y coordinador
- matrícula: encargada de las solicitudes de matricula por PAC académico
- biblioteca virtual: permite ver recursos y/o subir (en caso de tener roles)
- administradores: permite parametrizar los procesos de la universidad
- notificaciones de correo: permite enviar correos
- resources: entrega recursos que no necesitan de autenticación
- chat: encargado de permitir la comunicación de mensajería
- uploads: encargado de procesar subidas de archivos
- auth: encargado de la autenticación

## Estructura del proyecto
```bash
proyecto/
├── src/
│   ├── services/ # contiene todos los servicios del proyecto
│       ├── auth/
│       ├── admissions/
│       ├── resources/
│       ├── library/
│       ├── emailNotifications/
│       ├── students/
│       ├── teachers/
│       ├── enrollment/
│       ├── administrators/
│       ├── chat/
│       ├── uploads/
│ 
├── config/  # contiene las configuraciones como base de datos y lectura de .env
├── docs/    # contiene documentación general del documento
├── utils/   # contiene clases, funciones y tipo de datos
├── .env.example
├── .gitignore
├── README.md
```

## Integrantes
- Guillermo Morales
- Cristopher Izaguirre 
- Josué Ham
- David Garcia
- Juan Flores
