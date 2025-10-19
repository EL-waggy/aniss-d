<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sport - Aniss D.exe</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../img/index/image-principale-1.png" sizes="any">
  <meta name="description" content="Programmes sportifs et suivi d'entraînement par Aniss.">
</head>
<body>
  <!-- header navbar -->
  <?php include $_SERVER['DOCUMENT_ROOT']."/partial/navbar.php"; ?>
  <!-- end header navbar -->

<main>
  <section class="page-intro">
    <p class="hero-kicker">Routine & bien-être</p>
    <h1>Mes entraînements et défis sportifs</h1>
    <p>Parce que la créativité naît aussi d'un esprit en forme, je partage ici mes programmes de musculation, de cardio et les
      défis que je me lance au fil des saisons.</p>
  </section>

  <section class="content-section" aria-labelledby="weekly-focus">
    <h2 id="weekly-focus">Focus de la semaine</h2>
    <div class="card-grid">
      <article class="simple-card">
        <h3>Musculation</h3>
        <ul>
          <li>Lundi : Pectoraux & triceps</li>
          <li>Mercredi : Dos & biceps</li>
          <li>Vendredi : Jambes & épaules</li>
        </ul>
      </article>
      <article class="simple-card">
        <h3>Cardio</h3>
        <ul>
          <li>Mardi : HIIT 25 minutes</li>
          <li>Jeudi : Course à pied 6 km</li>
          <li>Samedi : Sortie vélo détente</li>
        </ul>
      </article>
      <article class="simple-card">
        <h3>Objectif du mois</h3>
        <p>Améliorer mon temps sur 5 km en passant sous les 24 minutes tout en gardant trois séances de musculation.</p>
      </article>
    </div>
  </section>

  <section class="content-section" aria-labelledby="resources">
    <h2 id="resources">Ressources utiles</h2>
    <div class="card-grid">
      <article class="simple-card">
        <h3>Suivi d'entraînement</h3>
        <p>Je consigne mes progrès dans Notion avec un tableau qui suit les charges, le nombre de répétitions et les sensations à
          chaque séance.</p>
      </article>
      <article class="simple-card">
        <h3>Playlist motivation</h3>
        <p>Retrouve ma sélection de sons pour booster la motivation sur Spotify.</p>
        <a href="https://open.spotify.com" target="_blank" rel="noopener" class="card-link">Écouter la playlist</a>
      </article>
      <article class="simple-card">
        <h3>Stretching</h3>
        <p>15 minutes d'étirements quotidiens axés sur les hanches et les épaules pour garder une bonne mobilité.</p>
      </article>
    </div>
  </section>
</main>

  <!-- Footer -->
  <?php include $_SERVER['DOCUMENT_ROOT']."/partial/footer.php"; ?>
  <!-- End Footer -->
</body>
</html>
