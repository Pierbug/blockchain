<?php
require_once("Block.php");
require_once("Host.php");
require_once("Transaction.php");
    if(isset($_POST['ipBlocco']) && isset($_POST['portaBlocco'])){
        new Block(new Host($_POST['ipBlocco'],$_POST['portaBlocco']),Block::firstHost(),Block::lastHost());
        header("Location: index.html");
    }else{
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aggiungi un Blocco</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Aggiungi un Blocco</h1>
        <div class="form-container">
            <form method="post" action="addBlock.php">
                <label for="ipBlocco">IP Blocco:</label>
                <input type="text" id="ipBlocco" name="ipBlocco" required>
                <label for="portaBlocco">Porta Blocco:</label>
                <input type="number" id="portaBlocco" name="portaBlocco" required>
                <button type="submit" class="red-button">Invia</button>
            </form>
        </div>
    </body>
</html>
<?php
    }
    
    
    
?>
