<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Simple Open Weather API PHP Class</title>
</head>

<body>
        <?php

        echo "<h1>Weather API - PHP Class</h1>\n\n";

        include("weather-api.class.php");

        $weather = new Weather('geocode');

        /*
        echo "<pre>";
        print_r($weather->getCoordinates("Concórdia", "BR"));
        echo "</pre>";

        echo "<hr/>";

        echo "<pre>";
        print_r($weather->getCoordinatesReverse("-27.2342", "-52.0278"));
        echo "</pre>";

        echo "<hr/>";
*/
        echo "<pre>";
        print_r($weather->getForecast("Concórdia", "BR"));
        echo "</pre>";

        ?>
</body>

</html>