<?php
require_once(__DIR__ . '/autoload.php');
require_once(__DIR__ . '/get-rooms.php');
require_once(__DIR__ . '/get-features.php');

$selectedRoomId = (isset($_SESSION['roomId'])) ? $_SESSION['roomId'] : 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/water.css@2/out/water.css'>
    <link rel="stylesheet" href="css/style.css">
    <title><?= $_ENV['HOTEL_NAME']; ?></title>
    <script>
        const selectedRoomId = <?= $selectedRoomId; ?>;
    </script>
</head>

<body>
    <header>
        <a href="index.php">
            <h1><?= $_ENV['HOTEL_NAME']; ?></h1>
        </a>
    </header>
    <main>

        <h2>Our Rooms</h2>
        <div class="choose-comfort">
            <form id="choose-comfort-form" action="get-calendar.php" method="post">
                <input type="submit" class="show-availability" name="choose-comfort" value="Budget"></input>
                <input type="submit" class="show-availability" name="choose-comfort" value="Standard"></input>
                <input type="submit" class="show-availability" name="choose-comfort" value="Luxury"></input>
            </form>
            <?php
            foreach ($rooms as $key => $room) { ?>
                <div class="room-info">
                    <h3><?= $room['comfort_level']; ?></h3>
                    <p><?= $room['description']; ?></p>
                    <p>Room rate: <span class="room-price"><?= $room['price'] . '.00'; ?></span> USD</p>
                </div>
            <?php
            } ?>
        </div>
        <?php
        // Echo the default calendar or the user-selected calendar, if it exists in the session variable
        if (isset($_SESSION['calendar'])) {
            echo $_SESSION['calendar'];
            unset($_SESSION['calendar']);
        } else {
            // Default to room 1 (budget)
            require_once(__DIR__ . '/get-calendar.php');
        }
        ?>

        <form class="booking-form" action="make-reservation.php" method="post">
            <h2>Optional Extras</h2>
            <div class="features">
                <?= $featuresHtml; ?>
            </div>
            <h2>Your Reservation</h2>
            <label for="first-name">First name</label>
            <input type="text" name="first-name" id="first-name" required>
            <label for="last-name">Last name</label>
            <input type="text" name="last-name" id="last-name" required>
            <label for="room-type">Room</label>
            <select name="room-type" id="room-type" readonly>
                <?php
                foreach ($rooms as $key => $room) { ?>
                    <option value="<?= $room['id']; ?>" <?= ($room['id'] == $selectedRoomId) ? 'selected' : ''; ?>><?= $room['comfort_level']; ?></option>
                <?php

                } ?>
            </select>
            <label for="arrival">Arrival</label>
            <div class="date-container">
                <input type="text" name="arrival" id="arrival" min="2024-01-01" max="2024-01-31" placeholder="Use calendar above" readonly>
                <a href="#calendar"><i class="fa-regular fa-calendar-days"></i></a>
            </div>
            <label for="departure">Departure</label>
            <div class="date-container">
                <input type="text" name="departure" id="departure" min="2024-01-01" max="2024-01-31" placeholder="Use calendar above" readonly>
                <a href="#calendar"><i class="fa-regular fa-calendar-days"></i></a>
            </div>
            <label for="room-price">Room subtotal (USD)</label>
            <input type="text" name="room-price" id="room-price" value="0" readonly>
            <label for="features-price">Extras subtotal (USD)</label>
            <ul id="selected-features">

            </ul>
            <input type="text" name="features-price" id="features-price" value="0" readonly>
            <label for="total-price">Total price (USD)</label>
            <input type="text" name="total-price" id="total-price" value="0" readonly>
            <label for="transfer-code">Transfer code</label>
            <input type="text" name="transfer-code" id="transfer-code" required>
            <button type="submit">Book</button>
        </form>
    </main>
    <footer></footer>
    <script src="js/script.js"></script>
</body>

</html>