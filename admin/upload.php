<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('images.php');
}
check_csrf();

$target = (string) ($_POST['target'] ?? '');
$lang = (($_POST['lang'] ?? 'en') === 'cn') ? 'cn' : 'en';
$data = content();

$back = 'images.php';
$destRel = '';
$maxDim = 2400;

if ($target === 'product') {
    $slug = (string) ($_POST['slug'] ?? '');
    $exists = false;
    foreach ($data['products'] as $product) {
        if ($product['slug'] === $slug) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        flash('产品不存在。', 'err');
        redirect('products.php');
    }
    $destRel = 'img/products/' . $slug . '.jpg';
    $maxDim = 1400;
    $back = 'product-edit.php?slug=' . urlencode($slug) . '&lang=' . $lang;
} elseif ($target === 'hero') {
    $destRel = (string) deep_get($data, 'images.hero', 'img/mesh-hero.jpg');
    $maxDim = 2400;
} elseif ($target === 'macro') {
    $destRel = (string) deep_get($data, 'images.macro', 'img/mesh-macro.jpg');
    $maxDim = 1600;
} else {
    flash('未知的上传目标。', 'err');
    redirect('images.php');
}

$error = store_image($_FILES['image'] ?? [], $destRel, $maxDim);
if ($error !== null) {
    flash($error, 'err');
} else {
    flash('图片已更新。前台刷新即可看到新图。');
}

redirect($back);
