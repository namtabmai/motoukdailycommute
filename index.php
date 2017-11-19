<?php

// Includes API_KEY definition
require('config.php');

header('Content-Type: text/html');

date_default_timezone_set("Europe/London");
$now = new DateTime('now', new DateTimeZone('Europe/London'));

$URL="http://datapoint.metoffice.gov.uk/public/data/txt/wxfcs/regionalforecast/json/515?key=".API_KEY;

$londonLong = 51.30;
$londonLat = 0.07;

$contents = file_get_contents($URL);
$weather = json_decode($contents);

?>
<html>
  <head>
  <title>Your Daily Commute - <?= date('d/m/Y'); ?></title>
  </head>
<body>
<h1># Your Daily Commute - <?= date('d/m/Y'); ?></h1>
<p><custom preamble, make fun of me if you see me post this></p>
<h2>### Forecast</h2>
<?php foreach($weather->RegionalFcst->FcstPeriods->Period[0]->Paragraph AS $period): ?>
<h3>#### <?= $period->title; ?></h3>
<p><?php echo $period->{'$'}; ?></p>
<?php endforeach; ?>
<h2>### Sun times</h2>
<ul style="list-style: none">
<li>* Sun set <?= date_sunset($now->getTimestamp(), SUNFUNCS_RET_STRING, $londonLong, $londonLat, 90, $now->getOffset() / 3600); ?></li>
<li>* Sun rise tomorrow <?= date_sunrise($now->getTimestamp() + (24 * 60 * 60), SUNFUNCS_RET_STRING, $londonLong, $londonLat, 90, $now->getOffset() / 3600); ?></li>
</ul>
<h2>### Discord</h2>
<p>Bored at work, or just taking a break from riding. Drop by the ["unofficial" discord server](https://discord.gg/01041pCFK93AeJTJZ)</p>
<p><other fluff></p>
</body>
</html>
