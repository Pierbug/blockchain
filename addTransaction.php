<?php
require_once("Block.php");
require_once("Host.php");
require_once("Transaction.php");
if(isset($_POST['ipBlocco']) && isset($_POST['portaBlocco']) && isset($_POST['mittente']) 
&& isset($_POST['destinatario']) && isset($_POST['amount']))
{
    if(Block::hostExists(new Host($_POST['ipBlocco'], $_POST['porta'])))
    {
        $block=new Block(new Host($_POST['ipBlocco'], $_POST['porta']),Block::firstHost(),Block::lastHost());
        $block->addTransactions(new Transaction($_POST['mittente'], $_POST['destinatario'],$_POST['amount'], time()));
        header("Location: index.html");

    }else{
        header("Location: index.html");
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
        <h1>Aggiungi una transazione</h1>
        <div class="form-container">
        <form method="post" action="addTransaction.php">
                <label for="ipBlocco">IP Blocco:</label>
                <input type="text" id="ipBlocco" name="ipBlocco" required>
                <label for="portaBlocco">Porta Blocco:</label>
                <input type="number" id="portaBlocco" name="portaBlocco" required>
                <label for="ipBlocco">Mittente:</label>
                <input type="text" name="mittente" placeholder="Mittente">
                <label for="ipBlocco">Destinatario:</label>
                <input type="text" name="destinatario" placeholder="Destinatario">
                <label for="ipBlocco">Quantita:</label>
                <input type="text" name="amount" placeholder="Quantita">
                <input type="submit" value="Invia">
        </form>
        </div>
    </body>
</html>
<?php
}
?>