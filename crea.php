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


$block = new Block(new Host("192.168.12.15",80));
$id = $block->getId();
$ip1 = $block->getActual();          //QUESTA é LA PROVA CHE FUNZIONA PERCHE PURE SE $b2 ho messo il '''Mio indirizzo''' ma volevo 
echo "primo: ".$ip1->getIp();        //vedere il blocco con id = $id infatti i dati di $b2 pure se nuovo blocco sono identici a $block

for($i=0;$i<20;$i++){
    $block->addTransactions(new Transaction("mittente",'destinatario','amount','time','hash'));
}
$block->saveJson();


$b2 = new Block(new Host("192.168.12.100",80),$id);
$ip2 = $b2->getActual();
echo "secondo: ".$ip2->getIp();

?>