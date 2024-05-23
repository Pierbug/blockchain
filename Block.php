<?php
require("Host.php");
require("Transaction.php");

class Block {
    private $id;
    private $actual;
    private $first;
    private $transactions = [];
    private $successivo;
    private $precedente;

    //grande problema da risolvere: noi non abbiamo l'hash tra i dati e va messo olttre a mettere anche 
    //hash successivo e hash precedente. l'hash del blocco deve essere aggiornato ogni volta che si effettua una transazione,
    //con questo aggiorna anche l'hash successivo del blocco precedente e l'hash precendente del blocco succ
    

    public function __construct($actual,$first = null,$precedente = null){
        if($first == null){ 
            $this->actual = $actual;
            $this->successivo = new Host();
            $this->first = $actual;
            $this->precedente = new Host();
            $this->transactions = [];
            $this->id = $this->calculateId();
        }else{
            $this->actual = $actual;
            $this->successivo = new Host();
            $this->first = $first;
            $this->precedente = new Host();
            $this->transactions = [];
            $this->id = $this->calculateId();
            //aggiorna il blocco precedente con il giusto hash
        }
        $this->saveJson();   //almeno si salva in automatico
    }

    private function calculateHash() {
        $dataToHash = $this->id . 
            $this->actual->getIp() . 
            $this->actual->getPorta() . 
            ($this->successivo ? $this->successivo->getIp() : "") . 
            ($this->successivo ? $this->successivo->getPorta() : "") .
            $this->first->getIp() . 
            $this->first->getPorta() . 
            ($this->precedente ? $this->precedente->getIp() : "") . 
            ($this->precedente ? $this->precedente->getPorta() : "").
            $this->calculateHashtransaction();
        return hash('sha256', $dataToHash);
    }

    private function calculateHashtransaction(){
        $hash="";
        foreach($this->transactions as $trans){
            $hash.=$trans->getHash();
        }
        return $hash;
    }
    

    public function calculateId(){
        $s = "";
        for($i=0;$i<20;$i++){
            $x = rand(48,90);
            if($x > 57 && $x < 64)
                $i = $i - 1;
            else
                $s .= chr($x);
        }
        return $s;
    }
    public function saveJson(){
        //di solito il json si crea in automatico dato un oggetto, prova a cercare la funzione 
        //che lo faccia, se non la trovi fai così e fai la import json che sarà una funzione statica 
        //che potrà essere richiamata e ritornerà un oggetto block
        //così almeno quando andiamo a cercare l'ultimo host possiamo fare meglio
        //ad es in addblock quando devo cercare l'ultimo host, dato l'ip e la porta prendo il file json.block e creo 
        //l'oggetto e guardo se successivo è null
        $data = [
            'id' => $this->id,
            'hash' => $this->calculateHash(),
            'actual IP' => $this->actual->getIp(),
            'actual Port' => $this->actual->getPorta(),
            'first IP' => $this->first->getIp(),
            'first Port' => $this->first->getPorta(),
            'successivo IP' => $this->successivo->getIp(),
            'successivo Port' => $this->successivo->getPorta(), 
            'precedente IP' => $this->precedente->getIp(),
            'precedente Port' => $this->precedente->getPorta(),
            'transazioni' => $this->printArray()
        ];
        $json_data = json_encode($data, JSON_PRETTY_PRINT);

        $filename = 'block_'.$this->id.'.json';
        file_put_contents($filename, $json_data);
        // il file json deve essere salvato sull'host actual e non così
    }
    private function updateOtherJson($host){
        //funzione che servirà per aggiornare il json dell'host passato
        // quindi trova il metodo per aggiornare il file su un altro server data porta e id e aggiorna 
        // il json, so che a parole sono bravi tutti e nemmeno io lo so fare, ma proviamoci che magari, non dico tutto,
        // ma qualcosina possiamo fare
    }

    public function getSuccessivo(){
        return $this->successivo;
    }

    public function setSuccessivo($successivo){
        $this->successivo = $successivo;
    }

    public function getPrecedente(){
        return $this->precedente;
    }

    public function setPrecedente($precedente){
        $this->precedente = $precedente;
    }

    public function getActual(){
        return $this->actual;
    }

    public function getId() {
        return $this->id;
    }

    public function addTransactions($trans) {
        array_push($this->transactions,$trans);
        $this->saveJson();   //almeno si salva in automatico
    }
    private function printArray(){
        $arr=[];
        foreach($this->transactions as $trans){
            array_push($arr,["mittente" => $trans->getMittente(), "destinatario" => $trans->getDestinatario(), "amount" => $trans->getAmount(), "time" => $trans->getTime(), "hash" => $trans->getHash()]);
        }
        return $arr;
    }


}

?>
