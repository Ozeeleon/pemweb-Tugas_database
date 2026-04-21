<?php
require_once 'config.php';

if (isLoggedIn()) {
    redirect('profile.php');
} else {
    redirect('login.php');
}
?>