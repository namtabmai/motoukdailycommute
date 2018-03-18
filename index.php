<?php

require('motouk.php');

header('Content-Type: text/html');

$dailyCommute = new DailyCommute();

date_default_timezone_set("Europe/London");
$now = new DateTime('now', new DateTimeZone('Europe/London'));

$URL="http://datapoint.metoffice.gov.uk/public/data/txt/wxfcs/regionalforecast/json/515?key=".API_KEY;

$londonLong = 51.30;
$londonLat = 0.07;

$contents = file_get_contents($URL);
$weather = json_decode($contents);

$floof = $dailyCommute->getFloof();

?>
<html>
  <head>
  <title><?= $dailyCommute->getTitle(); ?></title>
  </head>
  <body>
    <h1># <?= $dailyCommute->getTitle(); ?></h1>
    <p>&lt;custom preamble, make fun of me if you see me post this&gt;</p>
    <h2>### Forecast</h2>
      <?php foreach($dailyCommute->getWeather() AS $period): ?>
        <h3>#### <?= $period['title']; ?></h3>
        <p><?php echo $period['content']; ?></p>
      <?php endforeach; ?>
    <h2>### Sun times</h2>
    <ul style="list-style: none">
      <li>* <?= $dailyCommute->getSunset(); ?></li>
      <li>* <?= $dailyCommute->getSunrise(); ?></li>
    </ul>
    <h2>### Discord</h2>
    <p><?= $dailyCommute->getDiscordLink(); ?></p>
    <?php if($floof): ?>
    <h2>### Floof</h2>
    <p><?= $floof; ?></p>
    <?php endif; ?>
  </body>
</html>
