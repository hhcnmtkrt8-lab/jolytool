<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();

if (is_logged_in()) {
    redirect('dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if (attempt_login((string) ($_POST['password'] ?? ''))) {
        redirect('dashboard.php');
    }
    $error = '密码不正确。';
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>登录 · 网站后台</title>
<link rel="stylesheet" href="admin.css?2">
</head>
<body>
<div class="login-wrap">
  <form class="login-card" method="post" action="login.php">
    <h1>网站后台</h1>
    <p class="sub">南通高朋建材有限公司 · GOPEX</p>

    <?php if ($error !== ''): ?>
      <div class="alert alert--err" style="margin:0 0 18px"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="field">
      <label for="password">管理密码</label>
      <input id="password" type="password" name="password" autocomplete="current-password" autofocus required>
    </div>

    <?= csrf_field() ?>
    <button class="btn" type="submit">登录</button>

    <?php if (using_default_password()): ?>
      <p class="muted" style="margin-top:18px">初始密码为 <span class="mono">admin888</span>，登录后请立即在「修改密码」中更换。</p>
    <?php endif; ?>
  </form>
</div>
</body>
</html>
