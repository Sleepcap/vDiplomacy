<?php

require_once('interactiveMap.php');

$IAmap = getIAmapObject();

$IAmap->drawMap(isset($_REQUEST['colorCheck']));

$IAmap->serveMap();
?>
