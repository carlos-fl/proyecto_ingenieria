<?php ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="../../css/styles.css" />
    <link rel="stylesheet" href="../../css/sidebarStyles.css" />
    <title>Solicitud Cambio de Carrera</title>
</head>
<body>
    <navbar-unah></navbar-unah>
    <main>
        <div class="container bg-white rounded">
            <div class="row">
                <div class="col-sm-2 pb-4 sidebar border-end border-black text-center">
                    <div class="sidebar-hd mt-1 border-bottom border-black">
                        <header>
                            <h1>UNAH</h1>
                        </header>
                    </div>
                    <div class="sidebar-mn mt-3">
                        <a href="/views/students/home/index.php">Volver a mi perfil</a>
                    </div>
                    <div class="sidebar-ft">
                        <log-out></log-out>
                    </div>
                </div>
                <div class="col-sm-10">
                    <section class="container bg-white rounded border border-light p-3">
                        <div class="border-bottom border-black">
                            <h2>Solicitudes Cambio de Carrera</h2>
                            <p class="mt-3">
                                En este apartado puedes realizar tus solicitudes para el cambio de carrera. El coordinador de la carrera de tu centro será el responsable de revisar tu solicitud.
                            </p>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nueva Carrera</th>
                                        <th>Fecha de Solicitud</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="careerChangeTblInfo" class="text-center text-secondary">
                            No tienes solicitudes de cambio de carrera
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newRequestModal">Nueva solicitud</button>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <!--Modal para realizar nueva solicitud-->
    <div class="modal fade" id="newRequestModal" tabindex="-1" aria-labelledby="alumnosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alumnosModalLabel">Lista de Alumnos: <span class="titleSuffix"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <fieldset class="px-3">
                        <div class="mt-2">
                            <label class="mb-1" for="newMajor">Nueva carrera</label>
                            <select name="newMajor" id="newMajorSelect" class="form-select">
                                <option value="">Seleccione la carrera...</option>
                                <option value="">Ingenieria Química</option>
                                <option value="">Ingenieria Industrial</option>
                                <option value="">Administración de Empresas</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label class="mb-1"for="changeReason">Motivo del cambio</label>
                            <textarea name="changeReason" id="changeReason" class="form-control"></textarea>
                        </div>
                        <div class="mt-3">
                            <label for="requestSupport">Documentación de respaldo</label>
                            <input type="file" name="requestSupport id="requestSupportFiles" class="form-control" multiple>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button id="downloadStudentTableBtn" class="btn btn-success">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <footer-unah></footer-unah>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous">
</script>
<script type="module" src="../../js/components/navbar.js"></script>
<script type="module" src="../../js/components/footer.js"></script>
<script type="module"src="../../js/components/log-out.js"></script>
<script type="module"src="../../js/components/modal.js"></script>


</html>