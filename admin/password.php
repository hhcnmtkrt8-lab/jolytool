<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();
require_login();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $current = (string) ($_POST['current'] ?? '');
    $next = (string) ($_POST['next'] ?? '');
    $confirm = (string) ($_POST['confirm'] ?? '');

    if (!password_verify($current, credentials()['password'])) {
        $error = '当前密码不正确。';
    } elseif (strlen($next) < 8) {
        $error = '新密码至少需要 8 位。';
    } elseif ($next !== $confirm) {
        $error = '两次输入的新密码不一致。';
    } else {
        save_credentials(['password' => password_hash($next, PASSWORD_DEFAULT), 'updated' => date('c')]);
        flash('密码已更新，请记住新密码。');
        redirect('dashboard.php');
    }
}

$page_title = '修改密码';
$active = 'password';
?>
<?php require __DIR__ . '/partials/head.php'; ?>

<section class="panel" style="max-width:520px">
  <h2>修改管理密码</h2>
  <p class="panel__hint">建议使用 8 位以上、包含字母和数字的密码，并告知其他需要维护网站的同时事。</p>

  <?php if ($error !== ''): ?>
    <div class="alert alert--err" style="margin:0 0 18px"><?= e($error) ?></div>
  <?php endif; ?>

  <form method="post" action="password.php">
    <div class="field">
      <label for="current">当前密码</label>
      <input id="current" type="password" name="current" autocomplete="current-password" required>
    </div>
    <div class="field">
      <label for="next">新密码<span class="field__help">至少 8 位</span></label>
      <input id="next" type="password" name="next" autocomplete="new-password" required>
    </div>
    <div class="field">
      <label for="confirm">再次输入新密码</label>
      <input id="confirm" type="password" name="confirm" autocomplete="new-password" required>
    </div>

    <div class="actions" style="margin:24px -24px -22px;border-radius:0 0 8px 8px">
      <?= csrf_field() ?>
      <button class="btn" type="submit">保存新密码</button>
    </div>
  </form>
</section>

<?php require __DIR__ . '/partials/foot.php'; ?>
