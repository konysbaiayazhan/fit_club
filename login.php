<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
redirect_if_logged_in();

$pageTitle = 'Кіру';
$active = 'login';
$errors = [];
$old = ['email' => ''];
$registered = isset($_GET['registered']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $old['email'] = $email;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email дұрыс емес';
    }
    if ($password === '') {
        $errors['password'] = 'Парольді енгізіңіз';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id, full_name, password_hash, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role']      = $user['role'];
            header('Location: profile.php');
            exit;
        }
        $errors['password'] = 'Email немесе пароль дұрыс емес';
    }
}

require 'includes/header.php';
?>

  <div class="auth-wrap">
    <div class="auth-card">
      <h1>Қош келдіңіз</h1>
      <p class="sub">Аккаунтыңызға кіріңіз</p>

      <?php if ($registered): ?>
        <div class="success" style="display:block">Тіркелу сәтті өтті. Енді кіріңіз.</div>
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
      <p class="auth-foot">Аккаунт жоқ па? <a href="register.php">Тіркелу</a></p>
    </div>
  </div>

<?php require 'includes/footer.php'; ?>
