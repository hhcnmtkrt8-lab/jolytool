<?php
require __DIR__ . '/includes/bootstrap.php';

$contact = content()['contact'];
$meta = [
    'title' => t('contactPageTitle') . ' | ' . c('company.' . lang() . '.shortName'),
    'desc' => c('company.' . lang() . '.name') . ' · ' . $contact['email'] . ' · '
        . (is_cn() ? $contact['addressCN'] : $contact['addressEN']),
    'active' => 'contact',
    'canonical' => SITE_URL . u('contact.html'),
];

render_page('contact', $meta);
