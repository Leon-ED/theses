<?php
require_once("../Model/searchModel.php");

$resultats = getSearchResults();
$stats = getStatsFromResults($resultats);
