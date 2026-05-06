<?php
include "pick_times.php"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opening times and prices</title>
    <link rel="stylesheet" href="./css/timenprice_style.css">
</head>
<body>
    <div id="times">
        <p class="header">Staff is around and the doors are open:</p>
        <?= ($MonOpen ?? false) ? "" : "<p>Opening hours have not been set</p>" ?>
        <ul>
            <li id="dayMonday">Monday: <time datetime="<?= $MonOpenTime ?? '' ?>" class="startTime"><?= $MonOpenTime ?? '' ?></time> to <time datetime="<?= $MonCloseTime ?? '' ?>" class="endTime"><?= $MonCloseTime ?? '' ?></time></li>
            <li id="dayTuesday">Tuesday: <time datetime="<?= $TueOpenTime ?? '' ?>" class="startTime"><?= $TueOpenTime ?? '' ?></time> to <time datetime="<?= $TueCloseTime ?? '' ?>" class="endTime"><?= $TueCloseTime ?? ''?></time></li>
            <li id="dayWednesday">Wednesday: <time datetime="<?= $WedOpenTime ?? '' ?>" class="startTime"><?= $WedOpenTime ?? '' ?></time> to <time datetime="<?= $WedCloseTime ?? '' ?>" class="endTime"><?= $WedCloseTime ?? ''?></time></li>
            <li id="dayThursday">Thursday: <time datetime="<?= $ThuOpenTime ?? ''?>" class="startTime"><?= $ThuOpenTime ?? '' ?></time> to <time datetime="<?= $ThuCloseTime ?? '' ?>" class="endTime"><?= $ThuCloseTime ?? '' ?></time></li>
            <li id="dayFriday">Friday: <time datetime="<?= $FriOpenTime ?? ''?>" class="startTime"><?= $FriOpenTime ?? '' ?></time> to <time datetime="<?= $FriCloseTime ?? '' ?>" class="endTime"><?= $FriCloseTime ?? ''?></time></li>
            <li id="daySaturday">Saturday: <time datetime="<?= $SatOpenTime ?? '' ?>" class="startTime"><?= $SatOpenTime ?? '' ?></time> to <time datetime="<?= $SatCloseTime ?? '' ?>" class="endTime"><?= $SatCloseTime ?? '' ?></time></li>
            <li id="daySunday">Sunday: <time datetime="<?= $SunOpenTime ?? '' ?>" class="startTime"><?= $SunOpenTime ?? '' ?></time> to <time datetime="<?= $SunCloseTime ?? '' ?>" class="endTime"><?= $SunCloseTime ?? '' ?></time></li>
        </ul>
        <p>Outside these times you can visit the gym freely with a Health Fitness Plus-Gym pass any time of the day!</p>
        <p id="isopen"></p>
    </div>
    <div id="prices">
        <p class="header">Prices:</p>
        <p>Single gym visit: 5€</p>
        <p>Health Fitness Plus-Gym pass containing 10 visits: 45€</p>
        <p>Bottle of mountain spring water : 10€</p>
        <p>'HULK'Protein bar : 5€</p>
        <p>Courses range from 50 to 200€ depending on the course. </p>

    </div>
    <script src="./js/timenprice_script.js"></script>
</body>
</html>
