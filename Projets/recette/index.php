<?php

include $_SERVER['DOCUMENT_ROOT'] . '/partial/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MasterchefLab</title>

  <meta name="description" content="Portfolio et blog d'Aniss Dah : développement web et expérimentations numériques.">

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
<section>

    <!-- header navbar -->
    <?php include $_SERVER['DOCUMENT_ROOT']."/partial/navbar.php"; ?>
    <!-- end header navbar -->

</section>


<section>
  <h1><a href="/Projets/recette/">MasterchefLab</a></h1>
  <h2>Petite presentation</h2>
  <p>Ce site de recette a pour vocation de vous proposer un large pannelle de recette avec des alternative vegan,sans gluten, halal etc. des que cela est possible</p>
  <p>Merci de votre visite</p>
</section>


<section>
  <!-- listes des recettes que je sais faire -->
  <ul>
    <li><a href="/Projets/recette/recettes/omelette/omellete.php">Recette de l'omellettte du chef</a> </li>
    <li><a href="/Projets/recette/recettes/fritte/fritte.php">La fritte belge</a></li>
    <li><a href="/Projets/recette/recettes/crepes/crepes.php">recettes de crepes</a></li>
  </ul>
</section>
<section>
<p> cette section est en cours de reflexion de mise en page merci de patienter</p>
</section>
<body>
<!-- Footer -->
<?php include $_SERVER['DOCUMENT_ROOT']."/partial/footer.php"; ?>
<!-- End Footer -->
</body>
</html>
