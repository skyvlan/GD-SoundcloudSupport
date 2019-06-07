<?php

{

// Turn off all error reporting
error_reporting(0);
include "connection.php";
include "filesize.php";
include "geturl.php"; 
$sndcldlink = htmlspecialchars($_POST["sclink"],ENT_QUOTES);
$query3=$db->prepare("select * from song where sclink = '".$sndcldlink."'");
$query3->execute();
if($query3->rowCount() == 0) {
$url = 'http://api.soundcloud.com/resolve?url='.$sndcldlink.'&client_id='.$scClientAPIKey.'';
$result = file_get_contents($url);
if (!$result){
	echo '<!DOCTYPE html>
<html>
  <head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <title>Soundcloud support for Geometry Dash</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="container">
      <div style="text-align: right;">
        <form id="contact" action="" method="post">
          <h3 style="text-align: center;">Song Not Found or Not Allowed to Use!</h3>
    
    </div>
  </body>
</html>
';
}else{

$all = json_decode($result, true);
$kind = htmlspecialchars($all["kind"], ENT_QUOTES);
$songname = htmlspecialchars($all["title"], ENT_QUOTES);
$artist = htmlspecialchars($all["user"]["username"], ENT_QUOTES);
$streamlink = htmlspecialchars($all["stream_url"], ENT_QUOTES);
$strlink2 = curl_init($streamlink);
curl_setopt($strlink2, CURLOPT_FOLLOWLOCATION, false);
$newUrl = curl_getinfo($strlink2, CURLINFO_EFFECTIVE_URL);

curl_close($strlink2);
$albumpic = htmlspecialchars($all["artwork_url"], ENT_QUOTES);
if($albumpic == NULL)
$albumpic = htmlspecialchars($all["user"]["avatar_url"], ENT_QUOTES);
$lctn = get_url(curl_init($streamlink.'?client_id='.$scClientAPIKey.''));
$fl1 = file_size($lctn);
$fl2 = ($fl1 / 1024000);
$filesize = floor($fl2 * 100) / 100;



$query2 = $db->prepare("INSERT INTO song (sclink, scstreamlink, artist, songname, albumpic, filesize)
VALUES ('$sndcldlink','$streamlink','$artist','$songname','$albumpic','$filesize')");
$query2->execute();
$gdid = $db->lastInsertId();
echo '<!DOCTYPE html>
<html>
  <head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <title>Soundcloud support for Geometry Dash</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="container">
      <div style="text-align: right;">
        <form id="contact" action="" method="post">
          <h3 style="text-align: center;">Song Found!</h3>
          <div style="text-align: left;"><b>Song Title:'.$songname.' &nbsp; &nbsp;
              &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;
              &nbsp; &nbsp;&nbsp; <img style="width: 124px; height: 124px;" alt="pic"

                src="'.$albumpic.'"> </b><br>
            <b>Artist: '.$artist.'</b><br>
            <b>Geometry </b><b>Dash ID: '.$gdid.'</b><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <br><br>
              Song Preview:<audio controls="controls" preload="none" src="'.$newUrl.'?client_id=e59b8f7f067d8c5bde919a45852ff90a"></audio><br>
            </b></div>
          &nbsp; &nbsp; &nbsp; &nbsp; <br>
          <div style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <b></b></div> </form>
      </div>
    </div>
  </body>
</html>
';
}

}else{
$result2 = $query3->fetchAll();
$results = $result2[0];
echo '<!DOCTYPE html>
<html>
  <head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <title>Soundcloud support for Geometry Dash</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="container">
      <div style="text-align: right;">
        <form id="contact" action="" method="post">
          <h3 style="text-align: center;">Song Found!</h3>
          <div style="text-align: left;"><b>Song Title:'.$results["songname"].' &nbsp; &nbsp;
              &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;
              &nbsp; &nbsp;&nbsp; <img style="width: 124px; height: 124px;" alt="pic"

                src="'.$results["albumpic"].'"> </b><br>
            <b>Artist: '.$results["artist"].'</b><br>
            <b>Geometry </b><b>Dash ID: '.$results["ID"].'</b><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <br><br>
              Song Preview:<audio controls="controls" preload="none" src="'.$results["scstreamlink"].'?client_id=e59b8f7f067d8c5bde919a45852ff90a"></audio><br>
            </b></div>
          &nbsp; &nbsp; &nbsp; &nbsp; <br>
          <div style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <b></b></div> </form>
      </div>
    </div>
  </body>
</html>
';
	

	}

}
?>