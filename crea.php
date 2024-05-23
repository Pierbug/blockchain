<?php
require_once("Block.php");
require_once("Host.php");
require_once("Transaction.php");

//leggi i commenti che ti ho lasciato, una volta fatti tutti, credo sia impossibile che tu riesca in 3 ore, 
// ma anche solo se ne fai un quarto sono felice, una volta fatto scrivimi le cose che sei riuscito a fare, 
// grazue e buon lavoro fra #fabiogay

$block = new Block(new Host("192.168.12.15",80));

$block->addTransactions(new Transaction("Alessio","Pietro","15",date("Y-m-d H:i:s")));

$block->addTransactions(new Transaction("Fabio","Pietro","30",date("Y-m-d H:i:s")));

$b2= new Block(new Host("192.168.12.10",80),new Host("192.168.12.15",80),new Host("192.168.12.15",80));