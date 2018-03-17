<?php

$WS = "https://kayaposoft.com/enrico/json/v2.0/?action=getHolidaysForMonth&month=%u&year=%u&country=gb&holidayType=public_holiday";

function is_holiday()
{
  global $WS;

  $today = new DateTime();

  $day = $today->format('d');
  $month = $today->format('m');
  $year = $today->format('Y');

  $url = sprintf($WS, $month, $year);

  try
  {
    echo "Querying: $url\r\n";

    $json = file_get_contents($url);
    $holidays = json_decode($json);

    foreach($holidays AS $holiday)
    {
      if ($holiday->date->day == $day)
      {
        echo "Today is the {$holiday->name[0]->text}\r\n";
        return true;
      }
    }
  }
  catch(Exception $e)
  {
    echo "Could not query for bank holiday\r\n$e\r\n";
  }

  return false;
}


?>
