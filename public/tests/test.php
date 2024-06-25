<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/tools.php";

print_r(getArticlesFromRSSFlux(64, "https://vercel.com/atom"));

