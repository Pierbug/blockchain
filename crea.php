<?php
require_once("Block.php");
require_once("Host.php");
require_once("Transaction.php");

$block = new Block(new Host("192.168.12.15",80));

$block->addTransactions(new Transaction("Alessio","Pietro","15",date("Y-m-d H:i:s")));

$block->addTransactions(new Transaction("Fabio","Pietro","30",date("Y-m-d H:i:s")));

echo $block->saveJson();
?>