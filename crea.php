<?php
require_once("Block.php");
require_once("Host.php");
require_once("Transaction.php");

//  _________       ______      ____        _____
//  |         /\    |     \ || /    \      /     \     /\       \  /
//  |______  /  \   |     | || |    |      |          /  \       \/
//  |       /____\  |____/  || |    |      |   __    /____\      /
//  |      /      \ |    \  || |    |      |     |  /      \    /
//  |     /        \|____/  || \____/      \_____/ /        \  /

// HO AGGIUNTO ADDTRANSACTIONS 


//$block = new Block(new Host("192.168.12.15",80));
//$block->addTransactions(new Transaction("Alessio","Pietro","15",date("Y-m-d H:i:s")));
//$block->addTransactions(new Transaction("Fabio","Pietro","30",date("Y-m-d H:i:s")));
//$b2= new Block(new Host("192.168.12.10",80),new Host("192.168.12.15",80),new Host("192.168.12.15",80));

$first = new Block(new Host("192.168.12.10",80));
$block = new Block(new Host("192.168.12.15",80),$first->getActual());

for($i=0;$i<10;$i++){
    $block->addTransactions(new Transaction("Pietro","Alessio",$i,date("Y-m-d H:i:s")));
}

$block->saveJson()
?>