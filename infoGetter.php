<?php
/* Get commencement dates */
    // $query = "SELECT * FROM `comm_dates`";
    // $result = mysqli_query($link, $query) or die(mysqli_error($link));
    // $dates = array();
    // while ($row = mysqli_fetch_assoc($result)) {
    //     array_push($dates, $row);
    // }
    
    /* Get college listing */
    $query = "SELECT * FROM `colleges` ORDER BY `name` ASC";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $colleges = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($colleges, $row);
    }
    
    /* Get packages */
    $query = "SELECT * FROM `packages` WHERE `pkgClass`='0'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $bachelor_pkg = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($bachelor_pkg, $row);
    }

    $query2 = "SELECT * FROM `packages` WHERE `pkgClass`='1'";
    $result2 = mysqli_query($link, $query2) or die(mysqli_error($link));
    $master_pkg = array();
    while ($row2 = mysqli_fetch_assoc($result2)) {
        array_push($master_pkg, $row2);
    }

    $query3 = "SELECT * FROM `packages` WHERE `pkgClass`='2'";
    $result3 = mysqli_query($link, $query3) or die(mysqli_error($link));
    $phd_pkg = array();
    while ($row3 = mysqli_fetch_assoc($result3)) {
        array_push($phd_pkg, $row3);
    }
    
    /* Get individual items listing */
    $query4 = "SELECT * FROM `items` ORDER BY `sku` DESC"; 
    $result4 = mysqli_query($link, $query4) or die(mysqli_error($link));
    $items = array();
    while ($row4 = mysqli_fetch_assoc($result4)) {
        array_push($items, $row4);
    }
    
    /* if quantity has not already been chosen, default to 0 instead of blank */
    // $i = 0;
    // foreach ($items as $item) {
    //     $i++;
    //     if (!(isset($_SESSION['indItems' . $i])) || empty($_SESSION['indItems' . $i]))
    //         $_SESSION['indItems' . $i] = '0';
    // }

    ?>
