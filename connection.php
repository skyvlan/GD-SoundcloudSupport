<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";
$scClientAPIKey= "";
try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>