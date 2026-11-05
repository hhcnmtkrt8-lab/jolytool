<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();
redirect(is_logged_in() ? 'dashboard.php' : 'login.php');
