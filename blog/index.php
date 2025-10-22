<?php

include $_SERVER['DOCUMENT_ROOT'] . '/partial/bootstrap.php';
?>

<!doctype html>
<html class="no-js" lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog - Aniss D.exe</title>
  <link rel="stylesheet" href="/css/style.css">
  <meta name="description" content="Le blog d'Aniss Dah : tutoriels, veille technologique et retours d'expérience.">

  <meta property="og:title" content="blog">
  <meta property="og:type" content="">
  <meta property="og:url" content="">
  <meta property="og:image" content="">
  <meta property="og:image:alt" content="">


  <link rel="icon" href="<?= BASE_URL ?>/img/index/icon_lofi_style.ico" sizes="any">
  <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/img/index/icon_lofi_style.ico">
  <link rel="apple-touch-icon" href="<?= BASE_URL ?>/img/index/icon_lofi_style.ico">
  <link rel="manifest" href="<?= BASE_URL ?>/site.webmanifest">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
  <meta name="theme-color" content="#fafafa">


</head>

<body>



<!-- header navbar -->
<?php include $_SERVER['DOCUMENT_ROOT']."/partial/navbar.php"; ?>
<!-- end header navbar -->

  <main>
    <section class="hero">
      <div class="hero-content">
        <h2>Mon bloc-notes numérique</h2>
        <p>Retours d'expérience, tutoriels et idées sur le dev web, l'IA et la culture gaming. Le blog est en préparation, mais tu peux déjà t'abonner à l'ambiance.</p>
        <a href="../blog/" class="btn">Explorer les futurs articles</a>
      </div>
      <img src="../img/index/presentation_ia.png" alt="Illustration futuriste de présentation" class="hero-img">
    </section>

    <section class="about">
      <h2>À propos du blog</h2>
      <p>Chaque article sera conçu comme une mission : un objectif clair, un plan d'action et des ressources pour progresser. Laisse-moi un peu de temps pour looter du contenu épique.</p>
    </section>
  </main>

  <script src="../js/app.js"></script>
<!-- Footer -->
<?php include $_SERVER['DOCUMENT_ROOT']."/partial/footer.php"; ?>
<!-- End Footer -->
</body>
</html>
