<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();
require_login();

$data = content();
$contact = $data['contact'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $tel = post('tel');
    $fax = post('fax');
    $whatsapp = post('whatsapp');
    $email = post('email');

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('邮箱格式不正确。', 'err');
        redirect('contact.php');
    }

    $digits = static fn (string $v): string => preg_replace('/[^0-9+]/', '', $v) ?? '';

    $data['contact']['tel'] = $tel;
    $data['contact']['telHref'] = 'tel:' . $digits($tel);
    $data['contact']['telCN'] = post('telCN');
    $data['contact']['fax'] = $fax;
    $data['contact']['faxHref'] = 'tel:' . $digits($fax);
    $data['contact']['faxCN'] = post('faxCN');
    $data['contact']['email'] = $email;
    $data['contact']['skype'] = post('skype');
    $data['contact']['whatsapp'] = $whatsapp;
    $data['contact']['whatsappHref'] = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp);
    $data['contact']['addressEN'] = post('addressEN');
    $data['contact']['addressCN'] = post('addressCN');

    $saved = save_all($data);
    flash($saved ? '联系方式已保存。' : '保存失败，请检查 data 目录是否可写。', $saved ? 'ok' : 'err');
    redirect('contact.php');
}

$page_title = '联系方式';
$active = 'contact';
?>
<?php require __DIR__ . '/partials/head.php'; ?>

<section class="panel">
  <h2>联络信息</h2>
  <p class="panel__hint">这里修改的内容会同时出现在中英文站的页脚、联系页和产品页按钮上。</p>

  <form method="post" action="contact.php">
    <div class="grid-2">
      <div class="field">
        <label for="tel">电话（国际格式）<span class="field__help">例如 +86 513 89072121</span></label>
        <input id="tel" type="text" name="tel" value="<?= e($contact['tel']) ?>">
      </div>
      <div class="field">
        <label for="telCN">电话（国内格式）<span class="field__help">例如 0513-89072121</span></label>
        <input id="telCN" type="text" name="telCN" value="<?= e($contact['telCN']) ?>">
      </div>
      <div class="field">
        <label for="fax">传真</label>
        <input id="fax" type="text" name="fax" value="<?= e($contact['fax']) ?>">
      </div>
      <div class="field">
        <label for="faxCN">传真（国内格式）</label>
        <input id="faxCN" type="text" name="faxCN" value="<?= e($contact['faxCN']) ?>">
      </div>
      <div class="field">
        <label for="email">邮箱</label>
        <input id="email" type="text" name="email" value="<?= e($contact['email']) ?>">
      </div>
      <div class="field">
        <label for="skype">Skype</label>
        <input id="skype" type="text" name="skype" value="<?= e($contact['skype']) ?>">
      </div>
      <div class="field">
        <label for="whatsapp">WhatsApp 号码<span class="field__help">带国家代码，例如 +86 139 6294 3950</span></label>
        <input id="whatsapp" type="text" name="whatsapp" value="<?= e($contact['whatsapp']) ?>">
      </div>
    </div>

    <div class="field">
      <label for="addressEN">英文地址</label>
      <input id="addressEN" type="text" name="addressEN" value="<?= e($contact['addressEN']) ?>">
    </div>
    <div class="field">
      <label for="addressCN">中文地址</label>
      <input id="addressCN" type="text" name="addressCN" value="<?= e($contact['addressCN']) ?>">
    </div>

    <div class="actions" style="margin:24px -24px -22px;border-radius:0 0 8px 8px">
      <?= csrf_field() ?>
      <button class="btn" type="submit">保存修改</button>
    </div>
  </form>
</section>

<?php require __DIR__ . '/partials/foot.php'; ?>
