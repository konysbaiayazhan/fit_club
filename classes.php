<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$pageTitle = 'Жаттығулар';
$active = 'classes';

// ---- 1. GET параметрлерін алу ----
$q     = trim($_GET['q'] ?? '');
$cat   = $_GET['category'] ?? 'all';
$date  = $_GET['date'] ?? '';
$sort  = $_GET['sort'] ?? 'date_asc';
$page  = max(1, (int)($_GET['page'] ?? 1));
$perPage = 6;

if ($cat !== 'all' && !in_array($cat, CATEGORIES, true)) $cat = 'all';
if ($date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $date = '';

// Сұрыптау: тек рұқсат етілген нұсқалар (SQL-инъекциядан қорғау)
$sorts = [
    'date_asc'  => ['Күні: ерте → кеш',     'c.start_time ASC'],
    'date_desc' => ['Күні: кеш → ерте',     'c.start_time DESC'],
    'title'     => ['Атауы: А → Я',          'c.title ASC'],
    'spots'     => ['Бос орын: көбі бірінші', 'spots_left DESC'],
];
if (!isset($sorts[$sort])) $sort = 'date_asc';

// ---- 2. WHERE шарттарын құру ----
$where  = ['c.start_time >= NOW()'];
$params = [];

if ($q !== '') {
    $where[]  = '(c.title LIKE ? OR t.full_name LIKE ?)';
    $params[] = "%$q%";
    $params[] = "%$q%";
}
if ($cat !== 'all') {
    $where[]  = 'c.category = ?';
    $params[] = $cat;
}
if ($date !== '') {
    $where[]  = 'DATE(c.start_time) = ?';
    $params[] = $date;
}
$whereSql = 'WHERE ' . implode(' AND ', $where);

// ---- 3. Барлық нәтиже саны (пагинация үшін) ----
$stmt = $pdo->prepare("SELECT COUNT(*) FROM classes c JOIN trainers t ON t.id = c.trainer_id $whereSql");
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

$pages = max(1, (int)ceil($total / $perPage));
if ($page > $pages) $page = $pages;
$offset = ($page - 1) * $perPage;

// ---- 4. Ағымдағы беттің жаттығулары ----
$sql = "SELECT c.*, t.full_name AS trainer,
               c.capacity - (SELECT COUNT(*) FROM class_bookings b
                             WHERE b.class_id = c.id AND b.status = 'booked') AS spots_left
        FROM classes c
        JOIN trainers t ON t.id = c.trainer_id
        $whereSql
        ORDER BY {$sorts[$sort][1]}
        LIMIT ? OFFSET ?";
$stmt = $pdo->prepare($sql);
foreach ($params as $i => $value) {
    $stmt->bindValue($i + 1, $value);
}
$n = count($params);
$stmt->bindValue($n + 1, $perPage, PDO::PARAM_INT);
$stmt->bindValue($n + 2, $offset, PDO::PARAM_INT);
$stmt->execute();
$classes = $stmt->fetchAll();

// Пагинация сілтемелері үшін қалған сүзгілерді сақтау
function page_url(int $p): string
{
    $query = $_GET;
    $query['page'] = $p;
    return 'classes.php?' . http_build_query($query);
}

require 'includes/header.php';
?>

  <div class="page-head">
    <div class="container">
      <h1>Жаттығулар кестесі</h1>
      <p>Іздеңіз, сүзіңіз және өзіңізге қолайлы жаттығуды таңдаңыз</p>
    </div>
  </div>

  <section>
    <div class="container">
      <form method="get" action="classes.php" class="filters">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Жаттығу немесе жаттықтырушы іздеу...">
        <select name="category">
          <option value="all">Барлық категория</option>
          <?php foreach (CATEGORIES as $c): ?>
            <option value="<?= e($c) ?>"<?= $cat === $c ? ' selected' : '' ?>><?= e($c) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="date" name="date" value="<?= e($date) ?>">
        <select name="sort">
          <?php foreach ($sorts as $key => [$label]): ?>
            <option value="<?= e($key) ?>"<?= $sort === $key ? ' selected' : '' ?>><?= e($label) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Іздеу</button>
        <a href="classes.php" class="btn btn-outline btn-sm">Тазарту</a>
      </form>

      <p class="result-count">Табылды: <strong><?= $total ?></strong></p>

      <?php if ($total === 0): ?>
        <p class="empty" style="display:block">Ештеңе табылмады. Басқа сүзгіні таңдап көріңіз.</p>
      <?php else: ?>
      <div class="grid grid-3">
        <?php foreach ($classes as $c): ?>
        <article class="card class-card">
          <span class="tag"><?= e($c['category']) ?></span>
          <h3><?= e($c['title']) ?></h3>
          <p><?= e($c['description']) ?></p>
          <div class="class-meta">
            <span>🧑‍🏫 <?= e($c['trainer']) ?></span>
            <span>🕒 <?= e(date('d.m.Y H:i', strtotime($c['start_time']))) ?> · <?= (int)$c['duration_min'] ?> мин</span>
            <span class="spots<?= $c['spots_left'] <= 2 ? ' low' : '' ?>">Бос орын: <?= max(0, (int)$c['spots_left']) ?> / <?= (int)$c['capacity'] ?></span>
          </div>
          <a href="login.php" class="btn btn-primary btn-sm">Жазылу</a>
        </article>
        <?php endforeach; ?>
      </div>

      <?php if ($pages > 1): ?>
      <nav class="pagination">
        <?php if ($page > 1): ?><a href="<?= e(page_url($page - 1)) ?>">‹ Алдыңғы</a><?php endif; ?>
        <?php for ($i = 1; $i <= $pages; $i++): ?>
          <a href="<?= e(page_url($i)) ?>"<?= $i === $page ? ' class="current"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($page < $pages): ?><a href="<?= e(page_url($page + 1)) ?>">Келесі ›</a><?php endif; ?>
      </nav>
      <?php endif; ?>
      <?php endif; ?>
    </div>
  </section>

<?php require 'includes/footer.php'; ?>
