<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();
logout();
redirect('login.php');
