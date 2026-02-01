<?php
session_start();
session_destroy(); // currentMember = nullptr
header("Location: index.php");
?>