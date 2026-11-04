<?php
$pageTitle = 'Жаттығулар';
$active = 'classes';
require 'includes/data.php';
require 'includes/header.php';
 
// $_GET арқылы іздеу және сүзу
$q   = get('q');
$cat = get('category', 'all');
 
// Рұқсат етілмеген категорияны қабылдамаймыз
if ($cat !== 'all' && !in_array($cat, $categories, true)) {
    $cat = 'all';
}
 
$filtered = array_filter($classes, function ($c) use ($q, $cat) {
    $matchText = $q === '' ||
        mb_stripos($c['title'], $q) !== false ||
        mb_stripos($c['trainer'], $q) !== false;
    $matchCat = $cat === 'all' || $c['category'] === $cat;
    return $matchText && $matchCat;
});
?>

  <div class="page-head">
    <div class="container">
      <h1>Жаттығулар кестесі</h1>
      <p>Іздеңіз, сүзіңіз және өзіңізге қолайлы жаттығуды таңдаңыз</p>
    </div>
  </div>

  <section>
    <div class="container">
      <form method="get" action="classes.php" class="filters">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Жаттығу немесе жаттықтырушы іздеу...">
        <select name="category">
          <option value="all">Барлық категория</option>
          <?php foreach ($categories as $c): ?>
          <option value="<?= e($c) ?>"<?= $cat === $c ? ' selected' : '' ?>><?= e($c) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Іздеу</button>
        <a href="classes.php" class="btn btn-outline btn-sm">Тазарту</a>
      </form>
 
      <p class="result-count">Табылды: <strong><?= count($filtered) ?></strong></p>
 
      <?php if (count($filtered) === 0): ?>
        <p class="empty" style="display:block">Ештеңе табылмады. Басқа сөзбен іздеп көріңіз.</p>
      <?php else: ?>
      <div class="grid grid-3">
        <?php foreach ($filtered as $c): ?>
        <article class="card class-card">
          <span class="tag"><?= e($c['category']) ?></span>
          <h3><?= e($c['title']) ?></h3>
          <p><?= e($c['desc']) ?></p>
          <div class="class-meta">
            <span>🧑‍🏫 <?= e($c['trainer']) ?></span>
            <span>🕒 <?= e($c['when']) ?></span>
            <span class="spots<?= $c['left'] <= 2 ? ' low' : '' ?>">Бос орын: <?= (int)$c['left'] ?> / <?= (int)$c['capacity'] ?></span>
          </div>
          <a href="login.php" class="btn btn-primary btn-sm">Жазылу</a>
        </article>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>
 
<?php require 'includes/footer.php'; ?>
