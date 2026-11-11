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
 
<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
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
