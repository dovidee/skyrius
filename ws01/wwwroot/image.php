<?php
if (isset($_GET['img'])) {
    header("Content-Type: image/jpeg");
    include($_GET['img']);
}