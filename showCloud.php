<?php
//om
	require_once('include/cloud/wordCloud.php');
    $randomWords = array("Computer", "Skateboarding", "PC", "music", "music", "music", "music", "PHP", "C", "XHTML", "eminem", "programming", "forums", "webmasterworld","Chill out", "email", "forums", "Computer", "GTA", "css", "mysql", "sql", "css", "mysql", "sql","forums", "internet", "class", "object", "method", "music", "music", "music", "music", "gui", "encryption");
    $cloud = new wordCloud($randomWords);
    $cloud->addWord("music", 12);
    $cloud->addWord("downloads", 8);
    $cloud->addWord("internet", 17);
    $cloud->addWord("php", 22);
    $cloud->addWord("css", 32);
    $cloud->addWord("lotsofcode", 60);
    $myCloud = $cloud->showCloud("array");
    foreach ($myCloud as $key => $value) {
        echo ' <a href="path/to/tags/'.$value['word'].'" _fcksavedurl=""path/to/tags/'.$value['word'].'"" _fcksavedurl=""path/to/tags/'.$value['word'].'"" style="font-size: 1.'.$value['sizeRange'].'em">'.$value['word'].'</a> &nbsp;';
    }
?>