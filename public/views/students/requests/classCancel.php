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
    <title>Solicitud: Cancelación de Clases</title>
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
                            <h2>Solicitudes: Cancelación de clases</h2>
                            <p class="mt-3">
                                En este apartado puedes realizar tus solicitudes para la cancelación de tus clases. El coordinador de la carrera de tu centro será el responsable de revisar tu solicitud.
                            </p>
                        </div>
                        <div class="table-responsive mt-4 table-overflow">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha de Solicitud</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="studentRequestsTableBody">
                                </tbody>
                            </table>
                        </div>
                        <div id="majorChangeTblInfo" class="text-center text-secondary">
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button id="newRequestBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newRequestModal">Nueva solicitud</button>
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
                    <h5 class="modal-title" id="alumnosModalLabel">Nueva solicitud de Cancelación de clases<span class="titleSuffix"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <fieldset class="px-3">
                        <div class="mt-2">
                            <label class="mb-1" for="cancelFile">Documetación de Soporte <span class="text-danger">*</span></label>
                            <input type="file" name="cancelFile" id="classCancel" class="form-control" accept="application/pdf">
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button id="sendRequestBtn" class="btn btn-success" disabled>Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <pop-up
        id="popUp"
        imgsource="/views/assets/img/crossmark.png"
        popupclass=""
        message="">
    </pop-up>

    <footer-unah></footer-unah>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous">
</script>
<script type="module" src="../../js/components/navbar.js"></script>
<script type="module" src="../../js/components/footer.js"></script>
<script type="module"src="../../js/components/log-out.js"></script>
<script type="module"src="../../js/components/pop-up.js"></script>
<script type="module"src="../../js/components/modal.js"></script>
<script type="module" src="../js/classCancel.js"></script>


</html>
