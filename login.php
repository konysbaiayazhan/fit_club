<?php
$pageTitle = 'Кіру';
$active = 'login';
 
$errors  = [];
$success = false;
$old = ['email' => ''];
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/functions.php';
 
    $data = [
        'email'    => post('email'),
        'password' => $_POST['password'] ?? '',
    ];
    $old['email'] = $data['email'];
 
    $errors = validate_login($data);
 
    if (!$errors) {
        // 11-аптада осы жерде email мен пароль дерекқор арқылы тексеріледі
        $success = true;
    }
}
 
require 'includes/header.php';
?>

  <div class="auth-wrap">
    <div class="auth-card">
      <h1>Қош келдіңіз</h1>
      <p class="sub">Аккаунтыңызға кіріңіз</p>
      <?php if ($success): ?>
        <div class="success" style="display:block">Деректер дұрыс. Аутентификация 11-аптада қосылады.</div>
      <?php endif; ?>
 
      <form method="post" action="login.php" data-validate novalidate>
        <div class="<?= field_class($errors, 'email') ?>">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= e($old['email']) ?>" placeholder="name@mail.com">
          <?= field_error($errors, 'email') ?>
        </div>
        <div class="<?= field_class($errors, 'password') ?>">
          <label for="password">Пароль</label>
          <input type="password" id="password" name="password" placeholder="••••••">
          <?= field_error($errors, 'password') ?>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Кіру</button>
      </form>
      <p class="auth-foot">Аккаунт жоқ па? <a href="register.html">Тіркелу</a></p>
    </div>
  </div>

  <?php require 'includes/footer.php'; ?>
