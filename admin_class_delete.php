<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_admin();

// Өшіру тек POST арқылы (сілтемемен өшіруге болмайды)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $pdo->prepare('DELETE FROM classes WHERE id = ?');
        $stmt->execute([$id]);
    }
}
header('Location: admin_classes.php?msg=deleted');
exit;
