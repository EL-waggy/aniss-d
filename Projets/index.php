<?php

include $_SERVER['DOCUMENT_ROOT'] . '/partial/bootstrap.php';
?>

<!doctype html>
<html class="no-js" lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Projets - Aniss D.exe</title>

  <meta name="description" content="Découvrez les projets web, IA et outils réalisés par Aniss Dah.">

  <meta property="og:title" content="">
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

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/partial/navbar.php'; ?>

  <!-- end header navbar -->


  <main>
    <article>
      <section class="hero">
        <div class="hero-content">
          <h2> Mes projets en cours de boot</h2>
          <p>Un aperçu des missions tech que je développe : applications web, outils d'automatisation et expériences interactives inspirées de l'univers gaming.</p>
          <a href="/Projets/" class="btn">Explorer les projets</a>
        </div>
        <img src="/img/index/presentation_ia.png" alt="Illustration futuriste de présentation" class="hero-img">
      </section>
    </article>

    <article>
      <section class="hero">
        <div class="hero-content">
          <h2>Mon projet de site de recettes</h2>
          <p>Un aperçu DU site qui est en cours de devloppement</p>
          <a href="/Projets/recette/" class="btn">Explorer le site</a>
        </div>
        <img src="/img/recette.png" alt="Illustration futuriste de présentation" class="hero-img">

      </section>
    </article>



    <article>
      <section class="about">
        <h2>À propos des projets</h2>
        <p>Chaque projet est pensé comme un niveau à franchir : cahier des charges, design UI/UX, développement et déploiement. Reste connecté, des nouveautés arrivent très vite.</p>
      </section>
    </article>

  </main>
  <script src="../js/app.js"></script>

  <!-- Footer -->
  <?php include $_SERVER['DOCUMENT_ROOT']."/partial/footer.php"; ?>
  <!-- End Footer -->


</body>
</html>
