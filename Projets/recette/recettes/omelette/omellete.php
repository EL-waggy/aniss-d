<?php

include $_SERVER['DOCUMENT_ROOT'] . '/partial/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MasterchefLab</title>
  <link rel="stylesheet" href="/css/style.css">
  <meta name="description" content="Portfolio et blog d'Aniss Dah : développement web et expérimentations numériques.">

  <meta property="og:title" content="">
  <meta property="og:type" content="">
  <meta property="og:url" content="">
  <meta property="og:image" content="">
  <meta property="og:image:alt" content="">

  <link rel="icon" href="/img/index/image-principale-1.png" sizes="any">
  <link rel="icon" href="/img/index/image-principale-1.png" type="image/svg+xml">
  <link rel="apple-touch-icon" href="/img/index/icon_lofi_style.ico">

  <link rel="manifest" href="site.webmanifest">
  <meta name="theme-color" content="#fafafa">
</head>

<body>
<section>


  <!-- header navbar -->
  <?php include $_SERVER['DOCUMENT_ROOT']."/partial/navbar.php"; ?>
  <!-- end header navbar -->


</section>
<article>
  <section>
    <h1><a href="/Projets/recette/">Master Chef</a></h1>
    <h2>Petite presentation</h2>
    <p>Ce site de recette a pour vocation de vous proposer un large pannelle de recette avec des alternative vegan,sans gluten, halal etc. des que cela est possible</p>
    <p>Merci de votre visite</p>
  </section>
</article>

<article>
  <section>
    <!-- listes des recettes que je sais faire -->
    <ul>
      <li><a href="/Projets/recette/recettes/omelette/omellete.php">Recette de l'omellettte du chef</a> </li>
      <li><a href="/Projets/recette/recettes/fritte/fritte.php">La fritte belge</a></li>
      <li><a href="/Projets/recette/recettes/crepes/crepes.php">recettes de crepes</a></li>
    </ul>
  </section>
</article>


<article>
  <section>
    <h2>Pour faire une bonne omelette</h2>
    <h2><b> Liste des ingredients</b></h2>
      <ul>
        <p>Pour 1 personne: </p>
        <li>3 œufs frais</li>
        <li>1 cuillère à soupe de lait ou de crème pour plus de moelleux (facultatif)</li>
        <li>10 g de beurre</li>
        <li>Sel et poivre</li>
        <li>Quelques brins de ciboulette ciselée (facultatif)</li>
      </ul>

  </section>
</article>

<!-- Footer -->
<?php include $_SERVER['DOCUMENT_ROOT']."/partial/footer.php"; ?>
<!-- End Footer -->
</body>
</html>
