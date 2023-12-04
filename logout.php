<?php
session_start();
//Borramos toda la session
session_destroy();
header("location: /logistica ");
?>