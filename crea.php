<?php
require_once("Block.php");
require_once("Host.php");
require_once("Transaction.php");

$block = new Block(new Host("192.168.12.15",80));
?>