<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);        // 0 болса: жаңа жаттығу (Create), болмаса: өзгерту (Update)
$isEdit = $id > 0;
$pageTitle = $isEdit ? 'Жаттығуды өзгерту' : 'Жаңа жаттығу';
$active = 'admin';

$trainers = $pdo->query('SELECT id, full_name FROM trainers ORDER BY full_name')->fetchAll();
$trainerIds = array_column($trainers, 'id');

$errors = [];
$old = [
    'title' => '', 'category' => CATEGORIES[0], 'trainer_id' => '',
    'start_time' => '', 'duration_min' => '60', 'capacity' => '15', 'description' => '',
];

// Өзгерту болса, бұрынғы деректерді дерекқордан алу (Read)
if ($isEdit && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $pdo->prepare('SELECT * FROM classes WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        header('Location: admin_classes.php');
        exit;
    }
    $old = [
        'title' => $row['title'], 'category' => $row['category'], 'trainer_id' => (string)$row['trainer_id'],
        'start_time' => date('Y-m-d\TH:i', strtotime($row['start_time'])),
        'duration_min' => (string)$row['duration_min'], 'capacity' => (string)$row['capacity'],
        'description' => (string)$row['description'],
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'title'        => trim($_POST['title'] ?? ''),
        'category'     => $_POST['category'] ?? '',
        'trainer_id'   => $_POST['trainer_id'] ?? '',
        'start_time'   => $_POST['start_time'] ?? '',
        'duration_min' => trim($_POST['duration_min'] ?? ''),
        'capacity'     => trim($_POST['capacity'] ?? ''),
        'description'  => trim($_POST['description'] ?? ''),
    ];

    if (mb_strlen($old['title']) < 3 || mb_strlen($old['title']) > 100) {
        $errors['title'] = 'Атауы 3-тен 100 таңбаға дейін болуы керек';
    }
    if (!in_array($old['category'], CATEGORIES, true)) {
        $errors['category'] = 'Категорияны таңдаңыз';
    }
    if (!in_array((int)$old['trainer_id'], array_map('intval', $trainerIds), true)) {
        $errors['trainer_id'] = 'Жаттықтырушыны таңдаңыз';
    }
    $dt = DateTime::createFromFormat('Y-m-d\TH:i', $old['start_time']);
    if (!$dt || $dt->format('Y-m-d\TH:i') !== $old['start_time']) {
        $errors['start_time'] = 'Күні мен уақытын дұрыс таңдаңыз';
    }
    if (!ctype_digit($old['duration_min']) || (int)$old['duration_min'] < 15 || (int)$old['duration_min'] > 240) {
        $errors['duration_min'] = 'Ұзақтығы 15-тен 240 минутқа дейін';
    }
    if (!ctype_digit($old['capacity']) || (int)$old['capacity'] < 1 || (int)$old['capacity'] > 100) {
        $errors['capacity'] = 'Орын саны 1-ден 100-ге дейін';
    }
    if (mb_strlen($old['description']) > 500) {
        $errors['description'] = 'Сипаттама 500 таңбадан аспауы керек';
    }

    if (!$errors) {
        $values = [
            $old['title'], $old['description'], $old['category'], (int)$old['trainer_id'],
            $dt->format('Y-m-d H:i:00'), (int)$old['duration_min'], (int)$old['capacity'],
        ];
        if ($isEdit) {
            $stmt = $pdo->prepare(
                'UPDATE classes SET title=?, description=?, category=?, trainer_id=?,
                 start_time=?, duration_min=?, capacity=? WHERE id=?'
            );
            $stmt->execute([...$values, $id]);
            header('Location: admin_classes.php?msg=updated');
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO classes (title, description, category, trainer_id, start_time, duration_min, capacity)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute($values);
            header('Location: admin_classes.php?msg=created');
        }
        exit;
    }
}

require 'includes/header.php';
?>

  <div class="page-head">
    <div class="container">
      <h1><?= e($pageTitle) ?></h1>
    </div>
  </div>

  <section>
    <div class="container">
      <div class="card" style="max-width:620px">
        <form method="post" action="admin_class_edit.php<?= $isEdit ? '?id=' . $id : '' ?>" novalidate>
          <div class="<?= field_class($errors, 'title') ?>">
            <label for="title">Атауы</label>
            <input type="text" id="title" name="title" value="<?= e($old['title']) ?>">
            <?= field_error($errors, 'title') ?>
          </div>
          <div class="<?= field_class($errors, 'category') ?>">
            <label for="category">Категория</label>
            <select id="category" name="category">
              <?php foreach (CATEGORIES as $c): ?>
                <option value="<?= e($c) ?>"<?= $old['category'] === $c ? ' selected' : '' ?>><?= e($c) ?></option>
              <?php endforeach; ?>
            </select>
            <?= field_error($errors, 'category') ?>
          </div>
          <div class="<?= field_class($errors, 'trainer_id') ?>">
            <label for="trainer_id">Жаттықтырушы</label>
            <select id="trainer_id" name="trainer_id">
              <option value="">— таңдаңыз —</option>
              <?php foreach ($trainers as $t): ?>
                <option value="<?= (int)$t['id'] ?>"<?= (string)$t['id'] === (string)$old['trainer_id'] ? ' selected' : '' ?>><?= e($t['full_name']) ?></option>
              <?php endforeach; ?>
            </select>
            <?= field_error($errors, 'trainer_id') ?>
          </div>
          <div class="<?= field_class($errors, 'start_time') ?>">
            <label for="start_time">Күні мен уақыты</label>
            <input type="datetime-local" id="start_time" name="start_time" value="<?= e($old['start_time']) ?>">
            <?= field_error($errors, 'start_time') ?>
          </div>
          <div class="<?= field_class($errors, 'duration_min') ?>">
            <label for="duration_min">Ұзақтығы (минут)</label>
            <input type="number" id="duration_min" name="duration_min" value="<?= e($old['duration_min']) ?>">
            <?= field_error($errors, 'duration_min') ?>
          </div>
          <div class="<?= field_class($errors, 'capacity') ?>">
            <label for="capacity">Орын саны</label>
            <input type="number" id="capacity" name="capacity" value="<?= e($old['capacity']) ?>">
            <?= field_error($errors, 'capacity') ?>
          </div>
          <div class="<?= field_class($errors, 'description') ?>">
            <label for="description">Сипаттама</label>
            <textarea id="description" name="description" rows="4"><?= e($old['description']) ?></textarea>
            <?= field_error($errors, 'description') ?>
          </div>
          <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Сақтау' : 'Қосу' ?></button>
          <a href="admin_classes.php" class="btn btn-outline">Болдырмау</a>
        </form>
      </div>
    </div>
  </section>

<?php require 'includes/footer.php'; ?>
