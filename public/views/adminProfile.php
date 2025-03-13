<?php ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.cdnfonts.com/css/helvetica-neue-5" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/adminProfileStyle.css">
    <title>Admin</title>
</head>
<body style="background-color: var(--secondary-color);">
    <section id="adminSection" class="d-flex justify-content-center">
        <div id="adminComponent" class="rounded-2 py-3">
            <div id="adminGrid" class="container-fluid p-3">
                <div class="row">
                    <div class="col">
                        <aside id="asideControl" class="">
                            <h4 class="fw-bold"><a class="primary-text no-underline"href="">UNAH</a></h4>
                            <button id="endSession" class="btn btn-outline-danger btn-sm">Cerrar Sesión</button>
                        </aside>
                    </div>
                    <div class="col-10">
                        <main>
                            <!--Título-->
                            <section>
                                <h4 class="fw-bold">Sistema de Registro</h4>
                                <hr>
                            </section>
                            <!--Fotografía-->
                            <section>
                                <div>
                                    <h5 class="fw-bold text-end">Bienvenid@ Administrador!</h5>
                                </div>
                                <div id="photo">
                                    <img id="profilePic" src="assets/img/default-profile.png" alt="Mi foto de perfil">
                                    <input type="file" name="newPhoto" id="newPhoto" placeholder="Subir foto..." accept=".png, .jpg">
                                </div>
                                <hr>
                            </section>
                            <!---Información Personal-->
                            <section>
                                <div id="personalInfo" class="rounded-2">
                                    <div class="w-100">
                                        <div>
                                            <p class="fw-bold">Información Personal</p>
                                            <hr>
                                        </div>
                                        <div id="informacionContainer">
                                            <div class="row">
                                                <div class="col text-light-gray">Nombre Completo</div>
                                                <div class="col text-light-gray">No. Empleado</div>
                                                <div class="col text-light-gray">Correo</div>
                                                <div class="col text-light-gray">Telefono</div>
                                            </div>
                                            <div class="row">
                                                <div id="adminName" class="col">Juan Perez Saavedra</div>
                                                <div id="adminEmployeeNumber" class="col">1521</div>
                                                <div id="adminEmail" class="col">Jperez@unah.edu.hn</div>
                                                <div id="adminPhone" class="col">9900-1188</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!--Admin Actions-->
                            <section id="adminActions" class="mt-5 container d-flex">
                                <!--Admin Action Component-->
                                <div class="d-flex px-5">
                                    <a id="gestionDocentes" href="">
                                        <div>
                                            <img src="assets/img/archive.png" alt="" class="action-image">
                                            <p class="pt-2">Gestión de docentes</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="d-flex px-5">
                                    <a id="gestionMatricula" href="">
                                        <div>
                                            <img src="assets/img/archive.png" alt="" class="action-image">
                                            <p class="pt-2">Gestión de Matrícula</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="d-flex px-5">
                                    <a id="gestionExamen" href="examManagement.php">
                                        <div>
                                            <img src="assets/img/archive.png" alt="" class="action-image">
                                            <p class="pt-2">Gestión de exámenes</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="d-flex px-5">
                                    <a id="gestionRevisores" href="">
                                        <div>
                                            <img src="assets/img/archive.png" alt="" class="action-image">
                                            <p class="pt-2">Asignar Revisores</p>
                                        </div>
                                    </a>
                                </div>
                            </section>
                            
                        </main>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script type="module" src="js/adminProfile.js"></script>
</html>
