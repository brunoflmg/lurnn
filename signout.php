<?php
    require_once "config.php";
    session_regenerate_id();
    session_destroy();

    echo "<script>window.location='/';</script>";
?>