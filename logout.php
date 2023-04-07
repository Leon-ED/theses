<?php
session_start();
session_regenerate_id();
unset($_SESSION);
setcookie(session_name(), '', time() - 3600, '/');
session_destroy();
header("Location: ./");