<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('news.php');
}
check_csrf();

$slug = (string) ($_POST['slug'] ?? '');
$data = content();

$kept = [];
$removed = false;
foreach ($data['news'] as $item) {
    if ($item['slug'] === $slug) {
        $removed = true;
        continue;
    }
    $kept[] = $item;
}

if (!$removed) {
    flash('没有找到这条新闻。', 'err');
    redirect('news.php');
}

$data['news'] = $kept;
$saved = save_all($data);
flash($saved ? '新闻已删除。' : '删除失败，请检查 data 目录是否可写。', $saved ? 'ok' : 'err');
redirect('news.php');
