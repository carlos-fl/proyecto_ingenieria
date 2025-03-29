<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="icon" type="image/png" href="../../assets/img/UNAH-escudo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link href="https://fonts.cdnfonts.com/css/helvetica-neue-5" rel="stylesheet" />
    <link rel="stylesheet" href="../../css/styles.css" />
    <link rel="stylesheet" href="../../css/loginStyles.css" />
</head>
<body>
    <header>
        <navbar-unah></navbar-unah>
        <div class="top-bar">
            <nav class="navbar bg-body-tertiary" id="header-bar">
                <div class="container-fluid">
                    <h1 class="navbar-brand">Inicio de Sesión Resultados</h1>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <login-form action="/api/auth/controllers/examReviewer.php" imgSource="../../assets/img/unah.png" imgAlt="Logo UNAH"
            heading="Resultados" message="Debe autenticarse como revisor para usar este servicio" id="login-form"
            data-redirect-success="./index.php">
        </login-form>
    </main>

    <footer-unah></footer-unah>
    <pop-up
        id="popUp"
        imgsource="../../assets/img/crossmark.png"
        popupclass="fail-popup"
        message="">
    </pop-up>

    <!-- Ventana modal -->
    <reviewer-modal tag-id="reviewer" application="../../loginRevisorSolicitudesAd.php" exam="./login.php"></reviewer-modal>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script type="module" src="../../js/components/login-form.js"></script>
    <script type="module" src="../../js/components/pop-up.js"></script>
    <script type="module" src="../../js/components/navbar.js"></script>
    <script type="module" src="../../js/components/footer.js"></script>
    <script type="module" src="../../js/components/reviewerModal.js"></script>
    <script type="module" src="../../js/loginRevisorSolicitudesAd.js"></script>
    <script type="module" src="./controllers/login.js"></script>
</body>

</html>