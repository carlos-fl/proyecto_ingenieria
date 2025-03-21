<?php
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admisiones UNAH</title>
  <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/admisionesStyles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
</head>

<body>
  <header>
    <navbar-unah></navbar-unah>
    <div class="top-bar">
      <div>
        <nav class="navbar bg-body-tertiary" id="header-bar">
          <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="navbar-brand">
              Inscripcion
            </h1>
          </div>
        </nav>
      </div>
    </div>
  </header>
  <main>
    <div class="container d-flex justify-content-center align-items-center min-vh-95" id="container-form">
      <div class="card shadow" id="form-card" style="max-width: 900px; width: 100%;">
        <div class="card-body">
          <div id="registration-form">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="first-name" class="form-label">Nombres<span class="text-danger">*</span></label>
                  <input type="text" id="first-name" name="first-name" class="form-control" placeholder="Ingrese sus nombres" required />
                </div>
                <div class="mb-3">
                  <label for="last-name" class="form-label">Apellidos<span class="text-danger">*</span></label>
                  <input type="text" id="last-name" name="last-name" class="form-control" placeholder="Ingrese sus apellidos" required />
                </div>
                <div class="mb-3">
                  <label for="user-id" class="form-label">Identidad<span class="text-danger">*</span></label>
                  <input type="text" id="user-id" name="user-id" class="form-control" placeholder="0801-0000-00000" required />
                  <span id="identity-info" style="color: green; font-size: 14px;"></span>
                </div>
                <div class="mb-3">
                  <label for="phone" class="form-label">Teléfono<span class="text-danger">*</span></label>
                  <input type="tel" id="phone" name="phone" class="form-control" pattern="[0-9]{8}" placeholder="0000 0000" required />
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Correo<span class="text-danger">*</span></label>
                  <input type="email" id="email" name="email" class="form-control" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,5}" placeholder="ejemplo@correo.com" required />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="gender" class="form-label">Género<span class="text-danger">*</span></label>
                  <select id="gender" name="gender" class="form-select" required>
                    <option value="">Seleccione un género</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="regional-center" class="form-label">Centro Regional<span class="text-danger">*</span></label>
                  <select id="regional-center" name="regional-center" class="form-select" required>
                    <option value="">Seleccione un centro regional</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="main-career" class="form-label">Carrera Principal*</label>
                  <select id="main-career" name="main-career" class="form-select" required>
                    <option value="">Seleccione una carrera</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="secondary-career" class="form-label">Carrera Secundaria<span class="text-danger">*</span></label>
                  <select id="secondary-career" name="secondary-career" class="form-select" required>
                    <option value="">Seleccione una carrera secundaria</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="file-upload" class="form-label">Adjuntar Certificado de Secundaria<span class="text-danger">*</span></label>
                  <input type="file" id="file-upload" name="file-upload" accept=".pdf, .doc, .docx, .jpg, .jpeg, .png" class="form-control" required />
                </div>
                <div class="mb-3">
                  <button id="submit-button" class="btn btn-primary w-100">
                    Enviar
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center mt-3">
            <a href="admisiones.php" id="back-link">← Regresar</a>
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer-unah></footer-unah>

  <!-- Modal de inscripción exitosa -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalLabel">Inscripción Exitosa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p>Su inscripción se realizó correctamente. Su número de solicitud es:</p>
          <h3 id="application-number" class="text-center"></h3>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Ventana modal -->
  <div class="modal fade" id="reviewerModal" tabindex="-1" aria-labelledby="reviewerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reviewerModalLabel">Revisores</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body d-flex justify-content-center align-items-center">
          <div class="btn-group" role="group" aria-label="Select reviewer type">
            <a href="loginRevisorSolicitudesAd.php"><button type="button" class="btn btn-warning mx-3" id="admissionReviewer">Revisor de Solicitud de Admision</button></a>
            <a href="loginRevisorExamenes.php"><button type="button" class="btn btn-warning mx-3" id="examReviewer">Revisor de Examen de Admision</button></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--Pop Up-->
  <pop-up
    id="popUp"
    imgsource="assets/img/crossmark.png"
    popupclass="fail-popup"
    message="">
  </pop-up>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
  </script>
  <script src="js/components/navbar.js"></script>
  <script src="js/components/footer.js"></script>
  <script src="js/components/pop-up.js"></script>
  <script type="module" src="js/inscripciones.js"></script>

</body>

</html>
