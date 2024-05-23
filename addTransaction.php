<?php
require_once("Block.php");
require_once("Host.php");
require_once("Transaction.php");
if(isset($_POST['t'])){                 
    $i = 0;
    $block = new Block(new Host("192.168.12.10",80));           
    while($i = 0){
        if(count($block->getTransactions()) == 100){                //in qualche maniera non funziona
            $next = $block->getSuccessivo();
            $block = new Block($next);
        }else{
            $block->addTransactions(new Transaction($_POST['mitt'],$_POST['dest'],$_POST['amou'],date('Y-m-d H:i:s')));
            $block->saveJson();
            $i++;
            echo "Tutto apposto";
        }
    }
    
}else{
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fai una transazione</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <form method="post" action="">
            <input type="text" name="mitt" placeholder="Mittente">
            <input type="text" name="dest" placeholder="Destinatario">
            <input type="text" name="amou" placeholder="Quantita">
            <input type="submit" name="t" value="Invia">
        </form>
    </body>
</html>
<?php
}
?>