<?php
include __DIR__ . '/partial/bootstrap.php';
?>

<!doctype html>
<html class="no-js" lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aniss Dahaoui.exe</title>
  <link rel="stylesheet" href="css/style.css">
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

<body>
  <!-- header navbar -->
  <?php include $_SERVER['DOCUMENT_ROOT']."/partial/navbar.php"; ?>
  <!-- end header navbar -->



<main>
  <section class="hero" aria-labelledby="hero-title">
    <div class="hero-content">
      <p class="hero-kicker">Développeur full-stack </p>
      <h1 id="hero-title">Bienvenue dans mon laboratoire numérique 👋</h1>
      <p class="hero-text">
        Je conçois des expériences web modernes, partage mes découvertes techniques et documente mes
        apprentissages. Parcours mes projets, lis le blog ou contacte-moi pour discuter d'une collaboration.
      </p>
      <div class="hero-actions">
        <a href="/Projets/" class="btn">Explorer mes projets</a>
        <a href="/contact/" class="btn btn-secondary">Discuter ensemble</a>
      </div>
    </div>
    <img src="img/index/presentation_ia.png" alt="Illustration de ma tete" class="hero-img">
  </section>

  <section class="status-banner" aria-live="polite">
    <h2 class="sr-only">Informations</h2>
    <p>Ce site évolue en permanence : nouvelles fonctionnalités, expériences et partages réguliers.</p>
  </section>

  <section class="about" id="about">
    <div class="section-header">
      <h2 class="section-title">À propos</h2>
      <p class="section-subtitle">Un mélange de curiosité, de veille technologique et de projets concrets.</p>
      <p>Ce site est actuellement en cours de d'experimentation ce n'est pas la v finale c'est juste du html css avec un peu de js juste le temps de choisir ma DA et une fois trouvé ca va etre bcp plus propre</p>
    </div>
    <div class="about-grid">
      <article class="about-card">
        <h3>Développement</h3>

        <p>Je construis des interfaces réactives,cette page est vouée a etre modifier </p>

      </article>
      <article class="about-card">
        <h3>Veille & partage</h3>
        <p>Je documente mes découvertes sur le blog afin de rendre mes apprentissages accessibles et d'aider la
          communauté.</p>
      </article>
      <article class="about-card">
        <h3>Expérimentations</h3>
        <p>Chaque projet est l'occasion de tester de nouveaux outils, d'améliorer mes pratiques et de sortir de ma
          zone de confort.</p>
      </article>
    </div>
  </section>

  <section class="highlights" aria-labelledby="highlights-title">
    <div class="section-header">
      <h2 id="highlights-title" class="section-title">Explorer</h2>
      <p class="section-subtitle">Quelques portes d'entrée pour découvrir mon univers.</p>
    </div>
    <div class="highlight-grid">
      <article class="highlight-card">
        <h3>Projets en vedette</h3>
        <p>Une sélection d'applications web, d'outils et d'expériences créatives réalisés ces dernières années.</p>
        <a href="/Projets/" class="card-link">Voir mes projets</a>
      </article>
      <article class="highlight-card">
        <h3>Articles du blog</h3>
        <p>Des retours d'expérience, des tutoriels et de la veille technologique pour rester à jour.</p>
        <a href="/blog/" class="card-link">Lire le blog</a>
      </article>
      <article class="highlight-card">
        <h3>Recettes & sport</h3>
        <p>Une bulle plus personnelle où je partage mes routines sportives et mes recettes préférées.</p>
        <div class="card-actions">
          <a href="/recette/" class="card-link">Recettes</a>
          <a href="/sport/" class="card-link">Sport</a>
        </div>
      </article>
    </div>
  </section>

  <section class="cta-contact" aria-labelledby="cta-title">
    <div class="cta-content">
      <h2 id="cta-title">Travaillons ensemble</h2>
      <p>Un projet, une idée ou simplement envie d'échanger ? Je suis toujours partant pour découvrir de nouveaux
        défis.</p>
      <a href="/contact/" class="btn">Accéder à la page contact</a>
    </div>
  </section>
</main>

<script src="js/app.js"></script>
  <!-- Footer -->
  <?php include $_SERVER['DOCUMENT_ROOT']."/partial/footer.php"; ?>
  <!-- End Footer -->
</body>
</html>
