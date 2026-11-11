<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_login();

$pageTitle = 'Менің профилім';
$active = 'profile';

$stmt = $pdo->prepare('SELECT full_name, email, phone, role, created_at FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

require 'includes/header.php';
?>

  <div class="page-head">
    <div class="container">
      <h1>Менің профилім</h1>
      <p>Сәлем, <?= e($user['full_name']) ?>!</p>
    </div>
  </div>

  <section>
    <div class="container">
      <div class="card" style="max-width:520px">
        <h3>Жеке деректер</h3>
        <p><strong>Аты-жөні:</strong> <?= e($user['full_name']) ?></p>
        <p><strong>Email:</strong> <?= e($user['email']) ?></p>
        <p><strong>Телефон:</strong> <?= e($user['phone']) ?></p>
        <p><strong>Рөлі:</strong> <?= $user['role'] === 'admin' ? 'Әкімші' : 'Мүше' ?></p>
        <p><strong>Тіркелген күні:</strong> <?= e(date('d.m.Y', strtotime($user['created_at']))) ?></p>
        <br>
        <a href="logout.php" class="btn btn-outline btn-sm">Шығу</a>
      </div>
    </div>
  </section>

<?php require 'includes/footer.php'; ?>
