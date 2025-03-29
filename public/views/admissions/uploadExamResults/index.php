<?php 
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/userProfileComponent.css">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="../../css/revisorExamenesStyles.css">
  <link rel="stylesheet" href="../../css/loader.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <script src="https://kit.fontawesome.com/6130fb0810.js" crossorigin="anonymous"></script>
  <title>Subir Exámenes de Admisión</title>
</head>
<body>

  <navbar-unah></navbar-unah>

  <div class="container bg-white">
    <user-profile
    profile-title="Subir Resultados Admisión"
    profile-img="<?php echo base64_encode($_SESSION['PHOTO']) ?>"
    welcome-msg="Bienvenido a Sistema Admisión"
    user-number="<?php echo $_SESSION['DNI'] ?>"
    user-name="<?php echo $_SESSION['FIRST_NAME'] . $_SESSION['LAST_NAME'] ?>"
    user-phone="<?php echo $_SESSION['PHONE'] ?>"
    user-email="<?php echo $_SESSION['INST_EMAIL'] ?>"
    >
    </user-profile>
    <div class="upload-wrapper mb-3 d-flex justify-content-center align-items-center p-4">
      <div class="me-2">
        <label for="csvFile" class="upload-btn">Subir Resultados</label>
        <input type="file" id="csvFile" name="csvFile" accept=".csv">
      </div>
      <div>
        <button class="download-btn" id="admission-blueprint">Descargar formato</button>
      </div>
    </div>
  </div>

  <loading-modal tag-id="loading" modal-id="loading-modal"></loading-modal>

  <modal-error tag-id="csv-upload" modal-id="file-upload" arial-label-led-by="fileErrorModal" header-title="" arial-label="TEXT" hidden="false"></modal-error>

  <modal-success tag-id="csv-upload-s" modal-id="file-upload-s" arial-label-led-by="fileSuccessModal" header-title="Archivo subido Correctamente" arial-label="sucess" hidden="false"></modal-success>
  <reviewer-modal tag-id="reviewer" application="../../loginRevisorSolicitudesAd.php" exam="./login.php"></reviewer-modal>

  <footer-unah></footer-unah>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/components/userProfile.js"></script>
  <script type="module" src="../../js/components/reviewerModal.js"></script>
  <script src="../../js/components/footer.js"></script>
  <script src="../../js/components/navbar.js"></script>
  <script src="../../js/components/modal.js"></script>
  <script src="../../js/components/loading.js"></script>
  <script src="../../js/components/failModal.js"></script>
  <script src="../../js/components/successModal.js"></script>
  <script src="../../js/components/pop-up.js"></script>
  <script type="module" src="./controllers/parseCsv.js"></script>
  <script type="module" src="./controllers/downloadBlueprint.js"></script>
</body>
</html>
