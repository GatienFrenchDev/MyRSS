<?php

require_once "../lib/tools.php";

print_r(getArticlesFromRSSFlux(1, "https://www.lemonde.fr/sante/rss_full.xml"));
