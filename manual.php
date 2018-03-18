<?php

if ( php_sapi_name() != "cli" ) {
  print "This script must be run from the command line\n";
  exit();
}

require_once("holidays.php");
require_once("motouk.php");
require_once("Phapper/src/phapper.php");
include_once("config.php");

function checkCurrentDailyCommute($reddit)
{
  echo "Checking for current thread\r\n";

  $hasPost = false;

  $today = new Datetime();
  $today->setTime(0, 0, 0);

  $result = $reddit->search('daily commute', SUBREDDIT, null, 'day', null, 5);

  $posts = $result->data->children;
  foreach($posts AS $post)
  {
    $postDate = new DateTime('@'.$post->data->created_utc);
    $postDate->setTime(0, 0, 0);

    $hasPost = ($postDate == $today);
    if ($hasPost)
      break;
  }

  return $hasPost;
}

function postDailyCommute($reddit)
{
  $dailyCommute = new DailyCommute();

  echo "Posting thread: {$dailyCommute->getTitle()}\r\n";

  $content = <<<EOT
# {$dailyCommute->getTitle()}

Morning, looks like the regular daily commute posters are having a lie in, so this post has been automatically generated.

Ride safe.

### Forecast

EOT;

  foreach($dailyCommute->getWeather() AS $period)
  {
    $content .= "#### {$period['title']}\r\n";
    $content .= "{$period['content']}\r\n";
  }

  $content .= <<<EOT

### Sun times
* {$dailyCommute->getSunset()}
* {$dailyCommute->getSunrise()}

### Discord
{$dailyCommute->getDiscordLink()}

EOT;

  $floof = $dailyCommute->getFloof();
if ($floof)
{
  $content .= "\r\n### Floof\r\n";
  $content .= $floof;
  $content .= "\r\n\r\n";
}

$content .= <<<EOT
### Bot Information
This has been an automatic post. If you have any questions, suggestions or bugs please message /u/namtabmai

If you'd like to do tomorrow's daily commute thread, you can find an automatically generated template [here](http://www.keyboardcowboy.co.uk/motoukdailycommute/),
you just need to replace the bits between the <> brackets.

EOT;

  $response = $reddit->submitTextPost(SUBREDDIT, $dailyCommute->getTitle(), $content, false);
}

$reddit = new Phapper(
  Phapperconfig::$username,
  Phapperconfig::$password,
  PhapperConfig::$app_id,
  PhapperConfig::$app_secret,
  PhapperConfig::$user_agent
);

if (!is_holiday() && !checkCurrentDailyCommute($reddit))
{
  postDailyCommute($reddit);
}

?>
