<?php
/** @var string $page_title */
/** @var string $active */
$flashes = take_flashes();
$navItems = [
    'dashboard' => ['仪表盘', 'dashboard.php'],
    'site' => ['页面文字', 'site.php'],
    'products' => ['产品', 'products.php'],
    'news' => ['新闻', 'news.php'],
    'images' => ['图片', 'images.php'],
    'contact' => ['联系方式', 'contact.php'],
    'password' => ['修改密码', 'password.php'],
];
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($page_title ?? '后台') ?> · 高朋建材</title>
<link rel="stylesheet" href="<?= e(admin_url('admin.css?2')) ?>">
</head>
<body>
<div class="app">
  <aside class="side">
    <div class="side__brand">
      <strong>GOPEX</strong>
      <span>网站后台</span>
    </div>
    <nav class="side__nav">
      <?php foreach ($navItems as $key => [$label, $file]): ?>
        <a href="<?= e(admin_url($file)) ?>" class="<?= ($active ?? '') === $key ? 'is-active' : '' ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="side__foot">
      <a href="/" target="_blank" rel="noopener">查看网站 ↗</a>
      <a href="<?= e(admin_url('logout.php')) ?>">退出登录</a>
    </div>
  </aside>
  <main class="main">
    <header class="topbar">
      <h1><?= e($page_title ?? '后台') ?></h1>
      <span class="topbar__hint">修改后点击「保存」即生效</span>
    </header>
    <?php foreach ($flashes as $f): ?>
      <div class="alert alert--<?= e($f['t']) ?>"><?= e($f['m']) ?></div>
    <?php endforeach; ?>
