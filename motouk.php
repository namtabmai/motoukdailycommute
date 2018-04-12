<?php

// Includes API_KEY definition
include_once('config.php');

class DailyCommute
{
  private $now = null;

  private $URL = "http://datapoint.metoffice.gov.uk/public/data/txt/wxfcs/regionalforecast/json/515?key=".API_KEY;

  private $location = array(51.30, 0.07);

  private $date = null;

  private $weather = null;

  function __construct()
  {
    date_default_timezone_set("Europe/London");
    $this->now = new DateTime('now', new DateTimeZone('Europe/London'));

    $contents = file_get_contents($this->URL);
    $this->weather = json_decode($contents);
  }

  function getRandomImage($multireddit)
  {
    $data = file_get_contents("https://www.reddit.com/user/namtabmai/m/${multireddit}/.json");
    $json = JSON_decode($data);

    $posts = array();
    foreach($json->data->children AS $post)
    {
      if (!($post->data->is_self))
      {
        $posts[] = $post;
      }
    }

    if (empty($posts))
      return false;

    $i = rand(0, count($posts));

    $randomPost = $posts[$i];

    return $randomPost->data->url;
  }

  function getBike()
  {
    return $this->getRandomImage("motoukbikepics");
  }

  function getFloof()
  {
    return $this->getRandomImage("motoukfloof");
  }

  function getTitle()
  {
    return "Your Daily Commute - " . date('d/m/Y');
  }

  function getWeather()
  {
    $periods = array();
    foreach($this->weather->RegionalFcst->FcstPeriods->Period[0]->Paragraph AS $period)
    {
      $periods[] = array(
        'title' => $period->title,
        'content' => $period->{'$'}
      );
    }

    return $periods;
  }

  function getSunset()
  {
    return "Sun set " . date_sunset($this->now->getTimestamp(), SUNFUNCS_RET_STRING, $this->location[0], $this->location[1], 90, $this->now->getOffset() / 3600);
  }

  function getSunrise()
  {
    return "Sun rise tomorrow " . date_sunrise($this->now->getTimestamp() + (24 * 60 * 60), SUNFUNCS_RET_STRING, $this->location[0], $this->location[1], 90, $this->now->getOffset() / 3600);
  }

  function getDiscordLink()
  {
    return "Bored at work, or just taking a break from riding. Drop by the [\"unofficial\" discord server](https://discord.gg/k5D5uhH)";
  }
};

?>
