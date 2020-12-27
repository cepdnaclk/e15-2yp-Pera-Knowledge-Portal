<?php

define('CLIENT_ID', '542495510501-nbc2tq9as12i7a4uehc2mo98onq0rfht.apps.googleusercontent.com');
define('CLIENT_SECRET', 'DpLIXX8Ltc5wF4F1rfws5HmA');

if ($_SERVER['HTTP_HOST'] == 'localhost') {
   define('CLIENT_REDIRECT_URL', 'http://localhost/CO227-Project/web/public/dashboard/login/gAuth.php');
} else {
   define('CLIENT_REDIRECT_URL', 'https://apps.ceykod.com/apps/co227/dashboard/login/gAuth.php');
}




?>
