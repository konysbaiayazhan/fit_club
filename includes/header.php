<?php
// Әр беттің басында: $pageTitle және $active айнымалылары беріледі
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'Fit Club';
$active    = $active ?? '';

$menu = [
    'home'     => ['index.php',          'Басты бет'],
    'plans'    => ['index.php#plans',    'Абонементтер'],
    'trainers' => ['index.php#trainers', 'Жаттықтырушылар'],
    'classes'  => ['classes.php',        'Жаттығулар'],
    'login'    => ['login.php',          'Кіру'],
];
?>
<!DOCTYPE html>
<html lang="kk">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | Fit Club</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="container nav">
      <a href="index.php" class="logo">Fit<span>Club</span></a>
      <button class="burger" aria-label="Мәзір">☰</button>
      <ul class="nav-links">
        <?php foreach ($menu as $key => [$url, $label]): ?>
        <li><a href="<?= e($url) ?>"<?= $active === $key ? ' class="active"' : '' ?>><?= e($label) ?></a></li>
        <?php endforeach; ?>
        <li><a href="register.php" class="btn btn-primary btn-sm">Тіркелу</a></li>
      </ul>
    </div>
  </header>
