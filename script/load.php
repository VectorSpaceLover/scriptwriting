<?php

include('func.php');

$db = new DbConnection();

echo jsone_encode($db->fetch());