<?php

require_once('../app/LangSyncMain.php');

$sync = new LangSyncMain();
$sync->doSync();
$sync->writeLog();

?>