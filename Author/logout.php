<?php

session_start();
session_destroy();
header("Location: /TechTala/Authentication/homepage.html");
exit;

?>