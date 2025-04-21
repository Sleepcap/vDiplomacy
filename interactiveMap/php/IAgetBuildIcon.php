<?php
require_once('interactiveMap.php');

$IAmap = getIAmapObject();

$IAmap->serveBuildIcon($_REQUEST['unitType']);
?>
