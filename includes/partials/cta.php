<section class="cta-band">
  <div class="shell cta-band__inner">
    <div data-reveal>
      <h2 class="h2"><?= e(t('ctaTitle')) ?></h2>
      <p><?= e(t('ctaText')) ?></p>
    </div>
    <div class="hero__actions" data-reveal>
      <a class="btn" href="<?= e(u('contact.html')) ?>"><?= e(t('ctaBtn')) ?><span class="arrow" aria-hidden="true">→</span></a>
      <a class="btn btn--ghost" href="<?= e(content()['contact']['whatsappHref']) ?>" target="_blank" rel="noopener"><?= e(t('ctaBtn2')) ?></a>
    </div>
  </div>
</section>
