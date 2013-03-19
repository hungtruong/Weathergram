<?php

$conditions = array('chanceflurries'=>'snow', 
	'chancerain'=>'rain', 
	'chancesleet'=>'snow',
	'chancesnow'=>'snow',
	'chancetstorms'=>'thunderstorms',
	'clear'=>'sunny',
	'cloudy'=>'cloudy',
	'flurries'=>'snow',
	'fog'=>'fog',
	'hazy'=>'fog',
	'mostlycloudy'=>'cloudy',
	'mostlysunny'=>'sunny',
	'partlycloudy'=>'cloudy',
	'partlysunny'=>'sunny',
	'sleet'=>'snow',
	'snow'=>'snow',
	'sunny'=>'sunny',
	'tstorms'=>'thunderstorms',
	'unknown'=>'sunny',
	'rain'=>'rain'
	);
	
$wunderground_api_key = 'YOUR_API_KEY_HERE';

$songs = array(
	'rain'=>array('blameitontherain','raindropskeepfallin','herecomestherainagain'),
	'cloudy'=>array('getoffmycloud','cloudy'),
	'snow'=>array('letitsnow'),
	'sunny'=>array('mygirl','herecomesthesun'),
	'thunderstorms'=>array('thunderstruck','bohemianrhapsody'),
	'fog'=>array('afoggyday')
	);

$zipcode = $_REQUEST['Digits'];
$url = "http://api.wunderground.com/api/" . $wunderground_api_key ."/geolookup/conditions/forecast/q/" . $zipcode . ".json";
$ch = curl_init();
$timeout = 2; // set to zero for no timeout
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$file_contents = curl_exec($ch);
curl_close($ch);

$forecast = json_decode($file_contents, true);
$location = $forecast['location']['city'];
$forecast = $forecast['forecast']['txt_forecast']['forecastday'];


echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<Response>";
echo "<Say>Your forecast for " . $location . ".</Say>";
echo "<Pause/>";
foreach($forecast as $forecastday) {
  $night = preg_match('/Night/', $forecastday['title'], $matches);
  if(!$night) {
    $condition = $conditions[$forecastday['icon']];
    shuffle($songs[$condition]);
    $song = $songs[$condition][0];
    echo "<Say>" . $forecastday['title'] . "</Say>";
    echo "<Pause/>";
    echo "<Play>http://". $_SERVER['SERVER_NAME'] . "/weathergram/" . $song . ".mp3</Play>";
    echo "<Pause/>";
  }
}
echo "<Say>Thank you for using weathergram. Call again later for more weather.</Say>";
echo "</Response>";
?>
