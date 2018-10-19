
<?php
if (isset($_GET['location']) && !empty($_GET['location'])) {

    // first get the city coordinates using google geocoding api
    $google_api_key = "YOUR_KEY";
   
    $maps_url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($_GET['location']).'&key='.$google_api_key;
    $maps_json = file_get_contents($maps_url);
    $maps_array = json_decode($maps_json, true);
    $lat = $maps_array['results'][0]['geometry']['location']['lat'];
    $lng = $maps_array['results'][0]['geometry']['location']['lng'];

    $locError;
    if ($lat==null || $lng==null){
        $locError = true; //location not found
    }else{
        $locError = false;
        $darkSky_api_key = "YOUR_KEY";
        $weather_url = 'https://api.darksky.net/forecast/'.$darkSky_api_key.'/'.$lat.','.$lng;

        $output = json_decode(file_get_contents($weather_url), true);


//        to print json file content
//
//        echo '<pre>';
//        print_r($output);
//        echo '</pre>';

        $temp = $output['currently']['temperature'];
        $temp = round(($temp - 32)*5/9); // conversion from F to C and rounding

        $humidity = $output['currently']['humidity'];
        $humidity = round($humidity*100);

        $wind = $output['currently']['windSpeed'];

        $condition = $output['currently']['icon'];

        $icon;
        if ($condition == 'clear-day'){
            $icon = '<i class="wi wi-day-sunny"></i>';
        }else if ($condition == 'clear-night') {
            $icon = '<i class="wi wi-night-clear"></i>';
        }else if ($condition == 'rain') {
            $icon = '<i class="wi wi-rain"></i>';
        }else if ($condition == 'snow') {
            $icon = '<i class="wi wi-snow"></i>';
        }else if ($condition == 'sleet') {
            $icon = '<i class="wi wi-sleet"></i>';
        }else if ($condition == 'wind') {
            $icon = '<i class="wi wi-strong-wind"></i>';
        }else if ($condition == 'fog') {
            $icon = '<i class="wi wi-fog"></i>';
        }else if ($condition == 'cloudy') {
            $icon = '<i class="wi wi-cloudy "></i>';
        }else if ($condition == 'partly-cloudy-day') {
            $icon = '<i class="wi wi-day-sunny-overcast"></i>';
        }else if ($condition == 'partly-cloudy-night') {
            $icon = '<i class="wi wi-night-alt-partly-cloudy"></i>';
        }else {
            $icon = '<i class="wi wi-na"></i>';
        }




    }


}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Weather</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/weather-icons.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>
<div class="bg">
<div class="container">
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="container text-center">
                <h1 class="display-1 " id="wh">Weather</h1> <hr>
                <form class="form-inline text-center" action="" method="GET">
                    <label class="sr-only" for="inlineFormInputName2">City: </label>
                    <input type="text" name="location" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="London, Paris, ...">
                    <button type="submit" class="btn btn-primary mb-2">Search</button>
                </form>
            </div>
            <br><br>
            <?php if(isset($_GET['location']) && !empty($_GET['location'])){ ?>
            <div class="weather">
                <div class="current">
                    <?php if (!$locError): ?>
                    <div class="info">
                        <div>&nbsp;</div>
                        <div class="city"><small><small>City:</small></small> <?php echo $_GET['location'];?></div>
                        <div class="temp"><?php echo $temp.' ';?><small></small><i class="wi wi-celsius"></i></div>
                        <div class="humidity"><?php echo $humidity.' ';?> <span class="wi wi-humidity"></span></div>
                        <div class="wind"><span class="wi wi-strong-wind"></span><?php echo ' '.$wind;?> m/s</div>
                        <div>&nbsp;</div>
                    </div>
                    <div class="icon">
<!--                        <div class="condition">--><?php //echo "<small><small><small><small><small>$condition</small></small></small></small></small>";?><!--</div>-->
                        <?php echo $icon;?>
                    </div>
                    <?php else: ?>
                        <h3 class="display-3 " id="wh">City not found</h3>
                    <?php endif; ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="css/style.css" />

</div>
</body>
</html>