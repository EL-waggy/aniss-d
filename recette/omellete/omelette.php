<?php

include $_SERVER['DOCUMENT_ROOT'] . '/partial/bootstrap.php';
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Omelette moelleuse - Aniss D.exe</title>
  <meta name="description" content="Recette d'omelette moelleuse en cours de rédaction par Aniss Dah.">
  <link rel="stylesheet" href="../../css/style.css">
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
  <section class="page-intro">
    <p class="hero-kicker">Recette à venir</p>
    <h1>Omelette moelleuse</h1>
    <p>Cette recette détaillera bientôt comment obtenir une omelette fondante à souhait, avec des variations salées et sucrées.</p>
  </section>
  <section class="content-section">
    <a class="btn" href="../">↩ Retour aux recettes</a>
  </section>
</main>

<!-- Footer -->
<?php include $_SERVER['DOCUMENT_ROOT']."/partial/footer.php"; ?>
<!-- End Footer -->
</body>
</html>
