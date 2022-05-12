<?php
$link = new mysqli('localhost', 'student', 'PASS', 'graduation');

if(mysqli_connect_errno()){
    printf("Connection failed: %s\n", mysqli_connect_error());
    exit();
}
