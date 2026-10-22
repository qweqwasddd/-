<?php
require_once 'includes/functions.php';

unset($_SESSION['user']);
flash('Вы вышли из аккаунта');
redirect('index.php');
