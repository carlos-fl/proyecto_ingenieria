<?php ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.cdnfonts.com/css/helvetica-neue-5" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/examManagementStyle.css">
    <title>Exámenes</title>
</head>
<body style="background-color: var(--secondary-color);">
    <section id="adminSection" class="d-flex justify-content-center">
        <div id="adminComponent" class="rounded-2 py-3">
            <div id="adminGrid" class="container-fluid p-3">
                <!--Grid-->
                <div class="row">
                    <!--Aside-->
                    <div class="col">
                        <aside id="asideControl" class="pe-4">
                            <h4 class="fw-bold"><a class="primary-text no-underline"href="">UNAH</a></h4>
                            <a href="adminProfile.php">Regresar</a>
                            <input type="text" placeholder="Buscar..." class="form-control mt-2">
                            <section>
                                <div id="listaExamenes" class="mt-3 h-90">
                                    <ul id="examenes" class="p-1">
                                        <li data-test-name="PHUMA" data-max-score="1200" data-min-score="200" data-active>
                                            PHUMA
                                        </li>
                                        <li data-test-name="PAA" data-max-score="800" data-min-score="100">
                                            PAA
                                        </li>
                                        <li data-test-name="PAM" data-max-score="800" data-min-score="100">
                                            PAM
                                        </li>
                                        <li data-test-name="PCCNS" data-max-score="800" data-min-score="100">
                                            PCCNS
                                        </li>
                                    </ul>
                                </div>
                            </section>
                            <!-- Nuevo Examen Modal -->
                            <div class="modal fade" id="newExamModal" tabindex="-1" aria-labelledby="newExamModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="newExamModalLabel">Nuevo Examen</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <label for="newExamName">Nombre</label>
                                                <input id="newExamName" name="newExamName" type="text" class="form-control mb-3">

                                                <label for="newExamMax">Puntaje Máximo</label>
                                                <input id="newExamMax" name="newExamMax" type="text" class="form-control mb-3">

                                                <label for="newExamMin">Puntaje Mínimo</label>
                                                <input id="newExamMin" name="newExamMin" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" id="newExamCreate"class="btn btn-success">Crear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
  
                            <button id="newExam" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#newExamModal">Nuevo Examen</button>
                            <button id="endSession" class="btn btn-outline-danger btn-sm mt-5">Cerrar Sesión</button>
                        </aside>
                    </div>
                    <!--Main-->
                    <div class="col-10">
                        <main>
                            <!--Título-->
                            <section>
                                <h4 class="fw-bold">Gestión de Examenes</h4>
                                <hr>
                            </section>
                            <!--Datos del examen-->
                            <section id="examData" class="mt-5">
                                <div class="mb-5">
                                    <h2 id="examTitle" class="fw-bold">PHUMA</h2>
                                    <p class="fw-bold mt-4">Puntación máxima: <span id="examMaxGrade" class="fw-normal">1200</span></p>
                                    <p class="fw-bold">Puntuación mínima: <span id="examMinGrade" class="fw-normal">200</span></p>
                                </div>
                                <div class="">
                                     <!-- Editar Examen Modal -->
                                    <div class="modal fade" id="editExamModal" tabindex="-1" aria-labelledby="editExamModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="editExamenModalLabel">Editar Examen</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div>
                                                        <input type="hidden" name="editExamCode" id="examCode" value="1">

                                                        <label for="examName">Nombre</label>
                                                        <input id="examName" name="examName" type="text" class="form-control mb-3" value="PHUMA" disabled>

                                                        <label for="examMax">Puntaje Máximo</label>
                                                        <input id="examMax" name="examMax" type="text" class="form-control mb-3">

                                                        <label for="newExamMin">Puntaje Mínimo</label>
                                                        <input id="examMin" name="examMin" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="button" id="newExamCreate"class="btn btn-warning">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Eliminar Examen Modal -->
                                    <div class="modal fade" id="deleteExamModal" tabindex="-1" aria-labelledby="deleteExamModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="editExamenModalLabel">Eliminar Examen</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div>
                                                    <p>Esta seguro que desea eliminar el siguiente examen?</p>
                                                    <input type="hidden" name="deleteExamCode" id="examCode" value="1">

                                                    <label for="examName">Nombre</label>
                                                    <input id="examName" name="examName" type="text" class="form-control mb-3" value="PHUMA" disabled>

                                                    <label for="examMax">Puntaje Máximo</label>
                                                    <input id="examMax" name="examMax" type="text" class="form-control mb-3" value="1200" disabled>

                                                    <label for="newExamMin">Puntaje Mínimo</label>
                                                    <input id="examMin" name="examMin" type="text" class="form-control" value="200" disabled>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="button" id="newExamCreate"class="btn btn-danger">Eliminar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editExamModal">Editar Prueba</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteExamModal">Eliminar Prueba</button>
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
</html>
