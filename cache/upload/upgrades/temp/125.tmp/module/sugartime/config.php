<?php
/*
***** SugarTime *****
Developed by Paul K. Lynch, Everyday Interactive Networks (ein.com.au)
Mozilla Public License v1.1
*/
global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");


?>