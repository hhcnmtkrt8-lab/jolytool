<?php
$contact = content()['contact'];
$navItems = t('nav', []);
$productLinks = array_slice(content()['products'], 0, 4);
?>
</main>
<footer class="footer">
  <div class="shell">
    <div class="footer__grid">
      <div class="footer__brand">
        <span class="brand__mark">GOPEX</span>
        <p><?= e(t('footerAbout')) ?></p>
      </div>
      <div class="footer__col">
        <h4><?= e(t('footerCompany')) ?></h4>
        <ul>
          <?php foreach ($navItems as $navItem): ?>
            <?php if (($navItem['key'] ?? '') === 'home') continue; ?>
            <li><a href="<?= e(u(($navItem['href'] ?? '') . '.html')) ?>"><?= e($navItem['label'] ?? '') ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="footer__col">
        <h4><?= e(t('footerProducts')) ?></h4>
        <ul>
          <?php foreach ($productLinks as $product): ?>
            <li><a href="<?= e(product_url($product['slug'])) ?>"><?= e($product[lang()]['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="footer__col">
        <h4><?= e(t('footerContact')) ?></h4>
        <ul>
          <li><a href="mailto:<?= e($contact['email']) ?>"><?= e($contact['email']) ?></a></li>
          <li><a href="<?= e($contact['telHref']) ?>"><?= e(is_cn() ? $contact['telCN'] : $contact['tel']) ?></a></li>
          <li><?= e(is_cn() ? $contact['addressCN'] : $contact['addressEN']) ?></li>
        </ul>
      </div>
    </div>
    <div class="footer__bottom">
      <span>© <?= date('Y') ?> <?= e(c('company.' . lang() . '.name')) ?> <?= e(t('footerRights')) ?></span>
      <span><?= e(t('footerNote')) ?></span>
    </div>
  </div>
</footer>
<script src="<?= e(img_url('js/main.js')) ?>" defer></script>
</body>
</html>
