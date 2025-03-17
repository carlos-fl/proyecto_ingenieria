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
          <form id="registration-form">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="first-name" class="form-label">Nombres*</label>
                  <input type="text" id="first-name" name="first-name" class="form-control" placeholder="Ingrese sus nombres" required />
                </div>
                <div class="mb-3">
                  <label for="last-name" class="form-label">Apellidos*</label>
                  <input type="text" id="last-name" name="last-name" class="form-control" placeholder="Ingrese sus apellidos" required />
                </div>
                <div class="mb-3">
                  <label for="user-id" class="form-label">Identidad*</label>
                  <input type="text" id="user-id" name="user-id" class="form-control" placeholder="Ingrese su número de identidad" required />
                </div>
                <div class="mb-3">
                  <label for="phone" class="form-label">Teléfono*</label>
                  <input type="tel" id="phone" name="phone" class="form-control" pattern="[0-9]{8}" placeholder="0000 0000" required />
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Correo*</label>
                  <input type="email" id="email" name="email" class="form-control" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,5}" placeholder="ejemplo@correo.com" required />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="regional-center" class="form-label">Centro Regional*</label>
                  <select id="regional-center" name="regional-center" class="form-select" required>
                    <option value="">Seleccione un centro regional</option>
                    <!-- fetch -->
                  </select>
                </div>
                <div class="mb-3">
                  <label for="main-career" class="form-label">Carrera Principal*</label>
                  <select id="main-career" name="main-career" class="form-select" required>
                    <option value="">Seleccione una carrera</option>
                    <!-- fetch -->
                  </select>
                </div>
                <div class="mb-3">
                  <label for="secondary-career" class="form-label">Carrera Secundaria*</label>
                  <select id="secondary-career" name="secondary-career" class="form-select" required>
                    <option value="">Seleccione una carrera secundaria</option>
                    <!-- fetch -->
                  </select>
                </div>
                <div class="mb-3">
                  <label for="file-upload" class="form-label">Adjuntar Certificado de Secundaria*</label>
                  <input type="file" id="file-upload" name="file-upload" class="form-control" required />
                </div>
                <div class="mb-3">
                  <button type="submit" id="submit-button" class="btn btn-primary w-100">
                    Enviar
                  </button>
                </div>
              </div>
            </div>
          </form>
          <div class="text-center mt-3">
            <a href="admisiones.php" id="back-link">← Regresar</a>
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer-unah></footer-unah>

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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
    document.getElementById('reviewersBox').addEventListener('click', function() {
      var myModal = new bootstrap.Modal(document.getElementById('reviewerModal'));
      myModal.show();
    });
  </script>
  <script src="js/components/navbar.js"></script>
  <script src="js/components/footer.js"></script>

</body>

</html>