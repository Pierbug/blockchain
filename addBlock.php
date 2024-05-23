<?php
require_once("Block.php");
require_once("Host.php");
require_once("Transaction.php");
    if(isset($_POST['ipBlocco']) && isset($_POST['portaBlocco'])){
        $block = new Block(new Host($_POST['ipBlocco'],$_POST['portaBlocco']),$first);
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
    // function lastHost(){
    // $host = null;  
    //     if(){
        
    // }
    //funzione che controlla se il succ del primo blocco è null
    // allora ritorna il primo blocco
    // altrimenti controlla il blocco dopo, se il succ è null
    // allora ritorna succ
    // controlla blocco nuovo...
    //come controllare? dato host di succ, devi andare a quel server con quell'inidirizzo
    //prendere il file chiamato block.json perchè non lo dobbiamo rinominare anche con l'id sennò è un casino
?>
