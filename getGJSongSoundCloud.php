<?php
include "connection.php";
$songid = $_POST["songID"];
if($songid <= 80000000){
$url = 'http://www.boomlings.com/database/getGJSongInfo.php';
$data = array('songID' => $songid, 'secret' => 'Wmfd2893gb7');
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo $result;
}else{
$query3=$db->prepare("select * from song where ID = '".$songid."'");
$query3->execute();
if($query3->rowCount() == 0) {
echo "-1";
}else{
$result3 = $query3->fetchAll();
$result4 = $result3[0];
$songname = htmlspecialchars_decode($result4, ENT_QUOTES);
echo "1~|~".$result4["ID"]."~|~2~|~".$songname."~|~3~|~0~|~4~|~".$result4["artist"]."~|~5~|~".$result4["filesize"]."~|~6~|~~|~10~|~".$result4["scstreamlink"]."?client_id=".$scClientAPIKey."~|~7~|~~|~8~|~0";
}
}
?>