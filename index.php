<?php
$pageTitle = 'Басты бет';
$active = 'home';
require 'includes/data.php';
require 'includes/header.php';
?>

  <section class="hero">
    <div class="container">
    <div class="hero-inner">
      <h1>Күшті болу бүгіннен басталады</h1>
      <p>Абонемент таңдап, жаттығуларға онлайн жазылыңыз. Тәжірибелі жаттықтырушылар мен ыңғайлы кесте бір жерде.</p>
      <a href="register.php" class="btn btn-primary">Қазір бастау</a>
      <a href="classes.php" class="btn btn-outline">Кестені көру</a>
      <div class="stats">
        <div><strong><?= count($trainers) ?></strong><span>жаттықтырушы</span></div>
          <div><strong><?= count($classes) ?></strong><span>апталық жаттығу</span></div>
        <div><strong>500+</strong><span>мүше</span></div>
      </div>
    </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-title">
        <h2>Неге Fit Club?</h2>
        <p>Жаттығуға қажеттінің бәрі бір жерде</p>
      </div>
      <div class="grid grid-3">
        <div class="card"><div class="icon">🏋️</div><h3>Заманауи құрал-жабдық</h3><p>Күш, кардио және функционалды жаттығуларға арналған аймақтар.</p></div>
        <div class="card"><div class="icon">📅</div><h3>Онлайн жазылу</h3><p>Кестені көріп, бос орын бар жаттығуға бір басуда жазылыңыз.</p></div>
        <div class="card"><div class="icon">🧑‍🏫</div><h3>Тәжірибелі жаттықтырушылар</h3><p>Сертификатталған мамандар әр деңгейге көмектеседі.</p></div>
      </div>
    </div>
  </section>

  <section class="alt" id="plans">
    <div class="container">
      <div class="section-title">
        <h2>Абонементтер</h2>
        <p>Өзіңізге ыңғайлы мерзімді таңдаңыз</p>
      </div>
      <div class="grid grid-3">
        <?php foreach ($plans as $plan): ?>
        <div class="card plan<?= $plan['featured'] ? ' featured' : '' ?>">
          <?php if ($plan['featured']): ?><span class="badge">Танымал</span><?php endif; ?>
          <h3><?= e($plan['name']) ?></h3>
          <div class="price"><?= number_format($plan['price'], 0, '', ' ') ?> ₸<small> / <?= (int)$plan['days'] ?> күн</small></div>
          <ul>
            <?php foreach ($plan['features'] as $f): ?><li><?= e($f) ?></li><?php endforeach; ?>
          </ul>
          <a href="register.php" class="btn <?= $plan['featured'] ? 'btn-primary' : 'btn-outline' ?> btn-block">Таңдау</a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="trainers">
    <div class="container">
      <div class="section-title">
        <h2>Жаттықтырушылар</h2>
        <p>Сізге көмектесетін мамандар</p>
      </div>
      <div class="grid grid-3">
        <?php foreach ($trainers as $t): ?>
        <div class="card trainer">
          <div class="avatar"><?= e(mb_substr($t['name'], 0, 1)) ?></div>
          <h3><?= e($t['name']) ?></h3>
          <div class="role"><?= e($t['role']) ?></div>
          <p><?= e($t['bio']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php require 'includes/footer.php'; ?>
