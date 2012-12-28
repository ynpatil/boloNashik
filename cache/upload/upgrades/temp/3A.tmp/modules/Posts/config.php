<?php

global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");

?>