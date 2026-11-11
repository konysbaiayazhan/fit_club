<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
redirect_if_logged_in();

$pageTitle = 'Тіркелу';
$active = 'register';
$errors = [];
$old = ['full_name' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $old = ['full_name' => $full_name, 'email' => $email, 'phone' => $phone];

    if (mb_strlen($full_name) < 3) {
        $errors['full_name'] = 'Аты-жөніңізді толық жазыңыз';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email дұрыс емес';
    }
    if (!preg_match('/^\+?\d{10,15}$/', preg_replace('/[\s()-]/', '', $phone))) {
        $errors['phone'] = 'Телефон нөмірін дұрыс жазыңыз';
    }
    if (strlen($password) < 6) {
        $errors['password'] = 'Пароль кемінде 6 таңба болуы керек';
    }
    if ($password2 !== $password) {
        $errors['password2'] = 'Парольдер сәйкес келмейді';
    }

    // Email бұрын тіркелген бе
    if (!isset($errors['email'])) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'Бұл email бұрын тіркелген';
        }
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            'INSERT INTO users (full_name, email, password_hash, phone) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$full_name, $email, $hash, $phone]);

        header('Location: login.php?registered=1');
        exit;
    }
}

require 'includes/header.php';
?>

  <div class="auth-wrap">
    <div class="auth-card">
      <h1>Тіркелу</h1>
      <p class="sub">Fit Club мүшесі болыңыз</p>

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
      <p class="auth-foot">Аккаунт бар ма? <a href="login.php">Кіру</a></p>
    </div>
  </div>

<?php require 'includes/footer.php'; ?>
