<?php
require_once(getenv("PROJECT_ROOT") . 'vendor/autoload.php');
$decoded = verify_token(get_cookie(), device_id());
echo json_encode($decoded);
die();
