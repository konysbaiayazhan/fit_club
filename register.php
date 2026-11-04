<?php
$pageTitle = 'Тіркелу';
$active = 'register';
 
$errors  = [];
$success = false;
$old = ['full_name' => '', 'email' => '', 'phone' => ''];
 
// Форма POST арқылы жіберілсе ғана өңдейміз
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/functions.php';
 
    $data = [
        'full_name' => post('full_name'),
        'email'     => post('email'),
        'phone'     => post('phone'),
        'password'  => $_POST['password']  ?? '',
        'password2' => $_POST['password2'] ?? '',
    ];
    $old = ['full_name' => $data['full_name'], 'email' => $data['email'], 'phone' => $data['phone']];
 
    $errors = validate_register($data);
 
    if (!$errors) {
        // 11-аптада осы жерде деректер MySQL-ге жазылады
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $success = true;
        $old = ['full_name' => '', 'email' => '', 'phone' => ''];
    }
}
 
require 'includes/header.php';
?>

  <div class="auth-wrap">
    <div class="auth-card">
      <h1>Тіркелу</h1>
      <p class="sub">Fit Club мүшесі болыңыз</p>
      <?php if ($success): ?>
        <div class="success" style="display:block">Деректер дұрыс. Дерекқорға сақтау 11-аптада қосылады.</div>
      <?php endif; ?>
 
      <form method="post" action="register.php" data-validate novalidate>
        <div class="<?= field_class($errors, 'full_name') ?>">
          <label for="full_name">Аты-жөні</label>
          <input type="text" id="full_name" name="full_name" value="<?= e($old['full_name']) ?>" placeholder="Аты-жөніңіз">
          <?= field_error($errors, 'full_name') ?>
        </div>
        <div class="<?= field_class($errors, 'email') ?>">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= e($old['email']) ?>" placeholder="name@mail.com">
          <?= field_error($errors, 'email') ?>
        </div>
        <div class="<?= field_class($errors, 'phone') ?>">
          <label for="phone">Телефон</label>
          <input type="tel" id="phone" name="phone" value="<?= e($old['phone']) ?>" placeholder="+7 700 000 00 00">
          <?= field_error($errors, 'phone') ?>
        </div>
        <div class="<?= field_class($errors, 'password') ?>">
          <label for="password">Пароль</label>
          <input type="password" id="password" name="password" placeholder="Кемінде 6 таңба">
          <?= field_error($errors, 'password') ?>
        </div>
        <div class="<?= field_class($errors, 'password2') ?>">
          <label for="password2">Парольді қайталаңыз</label>
          <input type="password" id="password2" name="password2" placeholder="••••••">
          <?= field_error($errors, 'password2') ?>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Тіркелу</button>
      </form>
      <p class="auth-foot">Аккаунт бар ма? <a href="login.html">Кіру</a></p>
    </div>
  </div>

  <?php require 'includes/footer.php'; ?>
