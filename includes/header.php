<?php
// Әр беттің басында: $pageTitle және $active айнымалылары беріледі
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
$pageTitle = $pageTitle ?? 'Fit Club';
$active    = $active ?? '';

$menu = [
    'home'     => ['index.php',          'Басты бет'],
    'plans'    => ['index.php#plans',    'Абонементтер'],
    'trainers' => ['index.php#trainers', 'Жаттықтырушылар'],
    'classes'  => ['classes.php',        'Жаттығулар'],
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
        <li><a href="index.php"<?= $active === 'home' ? ' class="active"' : '' ?>>Басты бет</a></li>
        <li><a href="index.php#plans">Абонементтер</a></li>
        <li><a href="index.php#trainers">Жаттықтырушылар</a></li>
        <li><a href="classes.php"<?= $active === 'classes' ? ' class="active"' : '' ?>>Жаттығулар</a></li>
        <?php if (is_logged_in()): ?>
          <li><a href="profile.php"<?= $active === 'profile' ? ' class="active"' : '' ?>><?= e($_SESSION['user_name']) ?></a></li>
          <li><a href="logout.php" class="btn btn-outline btn-sm">Шығу</a></li>
        <?php else: ?>
          <li><a href="login.php"<?= $active === 'login' ? ' class="active"' : '' ?>>Кіру</a></li>
          <li><a href="register.php" class="btn btn-primary btn-sm">Тіркелу</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </header>
