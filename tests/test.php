<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/model/model.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/tools.php");


getArticlesFromRSSFlux(23, "https://www.youtube.com/feeds/videos.xml?channel_id=UCHRx3w9GE3r-iqpvIKsTgeQ");