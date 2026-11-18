<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_admin();

$pageTitle = 'Жаттығуларды басқару';
$active = 'admin';

$messages = [
    'created' => 'Жаттығу қосылды.',
    'updated' => 'Жаттығу өзгертілді.',
    'deleted' => 'Жаттығу өшірілді.',
];
$flash = $messages[$_GET['msg'] ?? ''] ?? '';

$rows = $pdo->query(
    'SELECT c.id, c.title, c.category, c.start_time, c.capacity, t.full_name AS trainer
     FROM classes c JOIN trainers t ON t.id = c.trainer_id
     ORDER BY c.start_time DESC'
)->fetchAll();

require 'includes/header.php';
?>

  <div class="page-head">
    <div class="container">
      <h1>Жаттығуларды басқару</h1>
      <p>Қосу, өзгерту және өшіру (тек әкімші)</p>
    </div>
  </div>

  <section>
    <div class="container">
      <?php if ($flash): ?>
        <div class="success" style="display:block"><?= e($flash) ?></div>
      <?php endif; ?>

      <p style="margin-bottom:18px"><a href="admin_class_edit.php" class="btn btn-primary btn-sm">+ Жаңа жаттығу</a></p>

      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr><th>№</th><th>Атауы</th><th>Категория</th><th>Жаттықтырушы</th><th>Күні</th><th>Орын</th><th></th></tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= (int)$r['id'] ?></td>
              <td><?= e($r['title']) ?></td>
              <td><?= e($r['category']) ?></td>
              <td><?= e($r['trainer']) ?></td>
              <td><?= e(date('d.m.Y H:i', strtotime($r['start_time']))) ?></td>
              <td><?= (int)$r['capacity'] ?></td>
              <td class="actions">
                <a href="admin_class_edit.php?id=<?= (int)$r['id'] ?>" class="btn btn-outline btn-sm">Өзгерту</a>
                <form method="post" action="admin_class_delete.php" onsubmit="return confirm('Жаттығуды өшіресіз бе?');">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Өшіру</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?>
            <tr><td colspan="7">Жаттығу жоқ. «Жаңа жаттығу» түймесін басыңыз.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

<?php require 'includes/footer.php'; ?>
