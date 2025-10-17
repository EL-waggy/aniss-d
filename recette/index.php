<?php
/**
 * index.php (recette) — barre unique de navigation recettes avec "déroulé"
 * ----------------------------------------------------------------------------------
 * - On garde la nav principale du site telle quelle (NE PAS TOUCHER).
 * - Une seule barre "recettes" affiche les catégories sous forme de puces.
 * - En cliquant sur une catégorie, un panneau déroulant affiche les recettes (HTML/PHP)
 *   de ce sous-dossier avec des liens cliquables vers chaque fichier.
 * - Le reste de la page peut afficher "toutes les catégories" ou une grille, mais ce
 *   n'est plus obligatoire pour le flux principal.
 *
 * .htaccess à conserver :
 *   RewriteEngine On
 *   RewriteRule ^recette(?:/.*)?$ /recette/index.php [L,QSA]
 */

// ---------- CONFIG ----------
$SITE_BASE       = '/';
$RECETTE_WEBROOT = '/recette';
$RECETTE_FSROOT  = __DIR__;

// ---------- HELPERS ----------
function listRecipeCategories(string $fsRoot): array {
  if (!is_dir($fsRoot)) return [];
  $cats = [];
  foreach (glob($fsRoot . '/*', GLOB_ONLYDIR) as $dir) {
    $slug = basename($dir);
    if ($slug === '' || $slug[0] === '_' || $slug === 'assets') continue;
    $count = count(glob($dir . '/*.html')) + count(glob($dir . '/*.php'));
    $label = ucfirst(str_replace(['-', '_'], ' ', $slug));
    $cats[] = ['slug'=>$slug,'label'=>$label,'count'=>$count];
  }
  usort($cats, fn($a,$b)=> strcmp($a['label'], $b['label']));
  return $cats;
}

function listRecipesInCategory(string $fsRoot, string $cat): array {
  $dir = rtrim($fsRoot,'/').'/'.$cat;
  if (!is_dir($dir)) return [];
  $items = [];
  foreach (['html','php'] as $ext) {
    foreach (glob($dir.'/*.'.$ext) as $file) {
      $base = basename($file);
      $slug = pathinfo($base, PATHINFO_FILENAME);
      $label = ucfirst(str_replace(['-', '_'], ' ', $slug));
      $items[] = [
        'slug'=>$slug,
        'label'=>$label,
        'ext'=>$ext,
        'url'=> '/recette/'.rawurlencode($cat).'/'.rawurlencode($slug).'.'.$ext
      ];
    }
  }
  usort($items, fn($a,$b)=> strcmp($a['label'],$b['label']));
  return $items;
}

function getActiveCategoryFromUrl(string $webroot): string {
  $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
  $segments = $path === '' ? [] : explode('/', $path);
  $root = trim($webroot, '/');
  $i = array_search($root, $segments, true);
  return ($i !== false && isset($segments[$i+1]) && $segments[$i+1] !== '') ? $segments[$i+1] : '';
}

// ---------- DATA ----------
$categories = listRecipeCategories($RECETTE_FSROOT);
// Pré-charger les recettes par catégorie pour le panneau déroulant
$recipesByCat = [];
foreach ($categories as $c) {
  $recipesByCat[$c['slug']] = listRecipesInCategory($RECETTE_FSROOT, $c['slug']);
}
$activeCat = getActiveCategoryFromUrl($RECETTE_WEBROOT);

// ---------- PAGE META ----------
$pageTitle = 'Recettes';
$pageDescription = "Navigation des recettes par catégories avec panneau déroulant.";
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recettes - Aniss D.exe</title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
  <link rel="stylesheet" href="<?= $SITE_BASE ?>css/style.css">
  <link rel="icon" href="<?= $SITE_BASE ?>img/index/image-principale-1.png" sizes="any">
  <style>
    /* Barre recettes (une seule barre) + panneau */
    .recette-bar{position:sticky;top:0;z-index:60;background:linear-gradient(135deg, rgba(255,255,255,.98), rgba(248,250,252,.98));border-bottom:1px solid #e5e7eb;backdrop-filter:saturate(140%) blur(6px)}
    .recette-bar .inner{display:grid;grid-template-columns:auto 1fr auto;gap:.5rem;align-items:center;max-width:1200px;margin:0 auto;padding:.5rem 1rem}
    .recette-bar .chips{display:flex;gap:.5rem;overflow-x:auto;scroll-snap-type:x proximity;padding:.25rem 0}
    .recette-bar .chip{flex:0 0 auto;scroll-snap-align:start;display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .85rem;border:1px solid #e5e7eb;border-radius:999px;background:#fff;font-size:.95rem;line-height:1;text-decoration:none;color:#111;user-select:none}
    .recette-bar .chip[aria-expanded="true"]{background:#111;color:#fff;border-color:#111}
    .recette-bar .count{opacity:.6;font-variant-numeric:tabular-nums}
    .recette-bar .tools{display:flex;gap:.5rem;align-items:center}
    .recette-bar input[type="search"], .recette-bar select{padding:.4rem .6rem;border:1px solid #e5e7eb;border-radius:.5rem;font-size:.95rem}
    .recette-bar .arrow{border:1px solid #e5e7eb;background:#fff;border-radius:.5rem;padding:.35rem .55rem;cursor:pointer}

    /* Panneau déroulant (one-at-a-time) */
    .recipes-panel{position:relative}
    .recipes-panel .panel{position:absolute;left:0;right:0;top:0;transform:translateY(0);background:#0b1220;color:#c9d1d9;border-bottom:1px solid #233046;box-shadow:0 24px 60px rgba(5,10,20,.45);display:none}
    .recipes-panel .panel[aria-hidden="false"]{display:block}
    .panel-inner{max-width:1200px;margin:0 auto;padding:14px 16px 18px}
    .panel-title{margin:4px 0 10px;color:#79c0ff;font-weight:700;letter-spacing:.05em;text-transform:uppercase}
    .panel-grid{display:grid;gap:10px;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr))}
    .panel-item a{display:block;padding:10px 12px;border:1px solid #233046;border-radius:10px;background:#111827;color:#e5e7eb;text-decoration:none}
    .panel-item a:hover{border-color:#3b82f6}

    @media (max-width:780px){
      .recette-bar .inner{grid-template-columns:auto 1fr}
      .recette-bar .tools{grid-column:1 / -1}
    }
  </style>
</head>
<body>

<header class="site-header">
  <nav class="site-nav" aria-label="Navigation principale">
    <a href="<?= $SITE_BASE ?>" class="logo">Aniss D.exe</a>
    <ul class="nav-links">
      <li><a href="<?= $SITE_BASE ?>">Accueil</a></li>
      <li><a href="<?= $SITE_BASE ?>CV/">Mon CV</a></li>
      <li><a href="<?= $SITE_BASE ?>about/">À propos</a></li>
      <li><a href="<?= $SITE_BASE ?>Projets/">Projets</a></li>
      <li><a href="<?= $SITE_BASE ?>blog/">Blog</a></li>
      <li><a href="<?= $RECETTE_WEBROOT ?>/">Recettes</a></li>
      <li><a href="<?= $SITE_BASE ?>sport/">Sport</a></li>
      <li><a href="<?= $SITE_BASE ?>contact/">Contact</a></li>
      <li><a href="<?= $SITE_BASE ?>Politique%20de%20confidentialit%C3%A9/">Politique de confidentialité</a></li>
    </ul>
  </nav>
</header>

<!-- Barre UNIQUE des recettes avec panneau déroulant -->
<div class="recette-bar" role="navigation" aria-label="Recettes">
  <div class="inner">
    <button class="arrow left" aria-label="Défiler vers la gauche" hidden>◀</button>
    <div class="chips" role="tablist" aria-label="Catégories">
      <?php foreach ($categories as $c): $slug=$c['slug']; $label=$c['label']; ?>
        <button class="chip" role="tab"
                aria-expanded="<?= $slug === $activeCat ? 'true' : 'false' ?>"
                data-cat="<?= htmlspecialchars($slug) ?>"
                aria-controls="panel-<?= htmlspecialchars($slug) ?>">
          <?= htmlspecialchars($label) ?>
          <span class="count"><?= $c['count'] ?: '' ?></span>
        </button>
      <?php endforeach; ?>
      <?php if (empty($categories)): ?>
        <span>Aucune catégorie trouvée dans <code><?= htmlspecialchars($RECETTE_WEBROOT) ?></code></span>
      <?php endif; ?>
    </div>
    <div class="tools">
      <input type="search" id="search-recette" placeholder="Rechercher une recette…" aria-label="Rechercher une recette">
      <select id="filter-difficulte" aria-label="Filtrer par difficulté">
        <option value="">Difficulté</option>
        <option value="facile">Facile</option>
        <option value="moyenne">Moyenne</option>
        <option value="difficile">Difficile</option>
      </select>
      <button class="arrow right" aria-label="Défiler vers la droite">▶</button>
    </div>
  </div>

  <!-- Panneau déroulant (one-at-a-time), rendu server-side -->
  <div class="recipes-panel" id="recipes-panel">
    <?php foreach ($categories as $c): $slug=$c['slug']; $label=$c['label']; $items=$recipesByCat[$slug] ?? []; ?>
      <div class="panel" id="panel-<?= htmlspecialchars($slug) ?>" aria-hidden="<?= $slug === $activeCat ? 'false' : 'true' ?>">
        <div class="panel-inner">
          <div class="panel-title"><?= htmlspecialchars($label) ?></div>
          <div class="panel-grid">
            <?php foreach ($items as $r): ?>
              <div class="panel-item"><a href="<?= htmlspecialchars($r['url']) ?>"><?= htmlspecialchars($r['label']) ?></a></div>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
              <p>Aucune recette trouvée dans <code>/recette/<?= htmlspecialchars($slug) ?></code>.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<main>
  <!-- Contenu libre (facultatif), on peut garder un aperçu -->
  <section class="about" aria-labelledby="title-allcats">
    <h2 id="title-allcats">Toutes les catégories</h2>
    <div class="grid" role="list">
      <?php foreach ($categories as $c): ?>
        <article class="card" role="listitem">
          <h3><?= htmlspecialchars($c['label']) ?></h3>
          <p><?= $c['count'] ?> recette(s)</p>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<footer class="site-footer">
  <div class="site-footer-content">
    <p>&copy; <?= date('Y') ?> Aniss D.exe · <a href="<?= $SITE_BASE ?>contact/" class="footer-link">Contact</a></p>
  </div>
</footer>

<script>
  (() => {
    // Défilement horizontal des chips
    const scroll = document.querySelector('.chips');
    const leftBtn = document.querySelector('.arrow.left');
    const rightBtn = document.querySelector('.arrow.right');

    const toggleArrows = () => {
      if (!scroll) return;
      leftBtn.hidden = scroll.scrollLeft < 10;
      rightBtn.hidden = (scroll.scrollWidth - scroll.clientWidth - scroll.scrollLeft) < 10;
    };
    if (scroll) {
      toggleArrows();
      scroll.addEventListener('scroll', toggleArrows);
      leftBtn?.addEventListener('click', ()=> scroll.scrollBy({left:-240, behavior:'smooth'}));
      rightBtn?.addEventListener('click',()=> scroll.scrollBy({left: 240, behavior:'smooth'}));

      // drag-to-scroll
      let dragging=false, startX=0, startLeft=0, id=0;
      scroll.addEventListener('pointerdown', e=>{ dragging=true; startX=e.clientX; startLeft=scroll.scrollLeft; id=e.pointerId; scroll.setPointerCapture(id); });
      scroll.addEventListener('pointermove', e=>{ if(!dragging) return; scroll.scrollLeft = startLeft - (e.clientX - startX); });
      scroll.addEventListener('pointerup',   ()=>{ dragging=false; });
      scroll.addEventListener('pointercancel',()=>{ dragging=false; });
    }

    // Panneau déroulant logic
    const chips = document.querySelectorAll('.chip[role="tab"]');
    const panels = document.querySelectorAll('.recipes-panel .panel');
    const closeAll = () => {
      chips.forEach(c => c.setAttribute('aria-expanded','false'));
      panels.forEach(p => p.setAttribute('aria-hidden','true'));
    };
    chips.forEach(chip => {
      chip.addEventListener('click', () => {
        const cat = chip.dataset.cat;
        const panel = document.getElementById('panel-'+cat);
        const isOpen = chip.getAttribute('aria-expanded') === 'true';
        closeAll();
        if (!isOpen && panel) {
          chip.setAttribute('aria-expanded','true');
          panel.setAttribute('aria-hidden','false');
        }
      });
    });
    // Fermer si clic en dehors du panneau / de la barre
    document.addEventListener('click', (e) => {
      const bar = document.querySelector('.recette-bar');
      if (!bar?.contains(e.target)) closeAll();
    });
    // Esc pour fermer
    document.addEventListener('keydown', (e)=>{
      if (e.key === 'Escape') closeAll();
    });

    // Recherche (optionnelle)
    const search = document.getElementById('search-recette');
    const selectDiff = document.getElementById('filter-difficulte');
    search?.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && search.value.trim()) {
        const q = encodeURIComponent(search.value.trim());
        const d = selectDiff?.value ? '&d='+encodeURIComponent(selectDiff.value) : '';
        // Page de recherche à implémenter si besoin
        location.href = '<?= $RECETTE_WEBROOT ?>/recherche/?q='+q+d;
      }
    });
  })();
</script>
</body>
</html>
