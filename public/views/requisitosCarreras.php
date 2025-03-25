<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisitos de Carrera</title>
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
                            Requisitos Especificos por Carrera
                        </h1>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main class="container" id="main-requirements">
        <h1 class="mb-4">Requisitos</h1>
        <div id="card-requirements" class="card">
        <div class="card-body">
            <img src="assets/img/Odontologia-color.png"/>
            <div>
            <h5 class="card-title text-primary">Odontologia</h5>
            <p class="card-text">
                <strong>Requisitos:</strong><br />
                - PHUMA: 1200<br />
                - PCNS: 900<br />
            </p>
            </div>
        </div>
        </div>
        <div class="text-center mt-3">
            <a href="admisiones.php" id="back-link">← Regresar</a>
        </div>   
    </main>
    <footer-unah></footer-unah>

<!-- Ventana modal -->
    <reviewer-modal tag-id="reviewer" application="loginRevisorSolicitudesAd.php" exam="./admissions/uploadExamResults/login.php"></reviewer-modal>
<!---->
      
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/components/navbar.js"></script>
    <script src="js/components/footer.js"></script>
    <script src="js/components/reviewerModal.js"></script>
</body>
</html>