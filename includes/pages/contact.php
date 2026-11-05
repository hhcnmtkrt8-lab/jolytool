<?php
$contact = content()['contact'];
$L = t('contactLabels');
$cn = is_cn();

$item = function (string $label, string $valueHtml, string $note = '') {
    echo '<div class="contact-item">';
    echo '<span class="contact-item__label">' . e($label) . '</span>';
    echo '<p class="contact-item__value">' . $valueHtml . '</p>';
    if ($note !== '') {
        echo '<p class="contact-item__note">' . e($note) . '</p>';
    }
    echo '</div>';
};
?>
<section class="page-head">
  <div class="shell page-head__inner">
    <div data-reveal>
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?= e(u()) ?>"><?= e(t('breadcrumbHome')) ?></a><span>/</span>
        <span><?= e(t('contactPageTitle')) ?></span>
      </nav>
      <h1 class="page-head__title"><?= e(t('contactPageTitle')) ?></h1>
    </div>
    <p class="page-head__aside" data-reveal><?= e(t('contactPageAside')) ?></p>
  </div>
</section>

<section class="section">
  <div class="shell split">
    <div data-reveal>
      <span class="eyebrow"><?= e($L['company']) ?></span>
      <h2 class="h2" style="margin-top:20px"><?= e(c('company.' . lang() . '.name')) ?></h2>
      <p class="lede" style="margin-top:18px"><?= e($cn ? c('company.cn.intro') : c('company.en.intro2')) ?></p>
      <div class="contact-grid" style="margin-top:clamp(28px,4vw,44px)">
        <?php
          $item($L['tel'], '<a href="' . e($contact['telHref']) . '">' . e($cn ? $contact['telCN'] : $contact['tel']) . '</a>');
          $item($L['fax'], '<a href="' . e($contact['faxHref']) . '">' . e($cn ? $contact['faxCN'] : $contact['fax']) . '</a>');
          $item($L['email'], '<a href="mailto:' . e($contact['email']) . '">' . e($contact['email']) . '</a>');
          $item($L['whatsapp'], '<a href="' . e($contact['whatsappHref']) . '" target="_blank" rel="noopener">' . e($contact['whatsapp']) . '</a>', t('contactNoteWhatsapp'));
          $item($L['skype'], e($contact['skype']));
        ?>
      </div>
    </div>
    <div data-reveal style="--reveal-delay:120ms">
      <?= tile(3, $cn ? $contact['addressCN'] : $contact['addressEN']) ?>
      <div class="contact-item" style="border-top:1px solid var(--line);margin-top:28px">
        <span class="contact-item__label"><?= e($L['address']) ?></span>
        <p class="contact-item__value"><?= e($cn ? $contact['addressCN'] : $contact['addressEN']) ?></p>
      </div>
    </div>
  </div>
</section>

<?php require ROOT . '/includes/partials/cta.php'; ?>
