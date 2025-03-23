<?php ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Revisión de Exámenes</title>
  <link rel="icon" type="image/png" href="assets/img/UNAH-escudo.png" />
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/solicitudesAdmisionStyles.css">
  <link rel="stylesheet" href="css/revisorExamenesStyles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
</head>

<body>
  <header>
    <navbar-unah></navbar-unah>
    <div class="top-bar">
      <div>
        <nav class="navbar bg-body-tertiary" id="header-bar">
          <div class="container-fluid">
            <h1 class="navbar-brand">Revisión de Exámenes</h1>
          </div>
        </nav>
      </div>
    </div>
  </header>
  <main>

    <div class="container center-container">
      <div class="upload-wrapper">
        <label for="csvFile" class="upload-btn">Subir Resultados</label>
        <input type="file" id="csvFile" name="csvFile" accept=".csv">
      </div>
    </div>
    <log-out id="logOut"></log-out>
  </main>
  <footer-unah></footer-unah>

  <!-- Ventana modal -->
    <reviewer-modal tag-id="reviewer" application="loginRevisorSolicitudesAd.php" exam="./admissions/uploadExamResults/login.php"></reviewer-modal>

  <!--Pop Up-->
  <pop-up
  id="popUp"
  imgsource="assets/img/crossmark.png"
  popupclass="fail-popup"
  message="">
  </pop-up>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script type="module" src="js/revisionExamenes.js"></script>
  <script src="js/components/navbar.js"></script>
  <script src="js/components/footer.js"></script>
  <script src="js/components/log-out.js"></script>
  <script src="js/components/pop-up.js"></script>
  <script src="js/components/reviewerModal.js"></script>
  

</body>

</html>