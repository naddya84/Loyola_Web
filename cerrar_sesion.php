<?php

session_name("loyola");
session_start();

unset($_SESSION['usuario']);

header('Location: index.php');

?> 