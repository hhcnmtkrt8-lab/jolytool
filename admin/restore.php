<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('dashboard.php');
}
check_csrf();

$name = basename((string) ($_POST['file'] ?? ''));
$file = BACKUP_DIR . '/' . $name;

if ($name === '' || !preg_match('/^content-[0-9-]+\.json$/', $name) || !is_file($file)) {
    flash('找不到这份备份。', 'err');
    redirect('dashboard.php');
}

if (!is_dir(BACKUP_DIR)) {
    @mkdir(BACKUP_DIR, 0775, true);
}
@copy(CONTENT_FILE, BACKUP_DIR . '/content-' . date('Ymd-His') . '-before-restore.json');
@copy($file, CONTENT_FILE);

flash('已恢复到 ' . $name . '，前台刷新即可查看。');
redirect('dashboard.php');
