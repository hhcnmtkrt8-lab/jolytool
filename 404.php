<?php
http_response_code(404);
require __DIR__ . '/includes/bootstrap.php';

$meta = [
    'title' => '404 | GOPEX',
    'desc' => 'Page not found.',
    'active' => '',
    'canonical' => SITE_URL . '/404',
];

render_page('not-found', $meta);
