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
    private $hash;     //hash utile
    //grande problema da risolvere: noi non abbiamo l'hash tra i dati e va messo olttre a mettere anche hash successivo e hash precedente. 
    //l'hash del blocco deve essere aggiornato ogni volta che si effettua una transazione ||FATTO||
    //con questo aggiorna anche l'hash successivo del blocco precedente e l'hash precendente del blocco succ

    //MENATA FESS (riga 13-15)
    

    public function __construct($actual,$id = null,$precedente = null){//metteri come parametro possibile $id = calculateId() se si puo cosi
        $this->actual = $actual;                            //almeno si puo aggiornare json attraverso quello siccome il filename è id
        $this->successivo = new Host();
        $this->first = new Host("192.168.12.10",80);
        $this->precedente = new Host();
        $this->transactions = [];
        $this->hash = $this->calculateHash();
        if($id == null)
            $this->id = $this->calculateId();   //fatto cio perche se è un nuovo blocco genero id
        else{
            $this->id = $id;                    //se id è passato allora poi faccio importJSON con i dati del blocco
            $this->importJson();
        } 
        //sbagliato salvare json ogni volta che si crea perche se io creo un oggetto ogni volta che ricerco si creano infinit file
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

    public function importJson(){
        $filename = "".$this->id.".json";
        $json = file_get_contents($filename);
        $array = json_decode($json, true);
        $trans = $array['transazioni'];
        $this->id = $array['id'];
        $this->hash = $array['hash'];
        $this->actual = new Host($array['actual IP'],$array['actual Port']);
        $this->successivo = new Host($array['successivo IP'],$array['successivo Port']);
        $this->precedente = new Host($array['precedente IP'],$array['precedente Port']);
        for($i=0;$i<count($trans);$i++){
            $t = $trans[$i];
            $this->addTransactions(new Transaction($t['mittente'],$t['destinatario'],$t['amount'],$t['time'],$t['hash']));
        }
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
        $filename = "".$this->id.".json";

        file_put_contents($filename, $json_data);                       //provato Download(fallimentare) per ora terrei cosi
    }

    private function updateOtherJson($host){
        //piuttosto che questo farei che ogni aggiunta di transazione o in generale modifica nella classe si faccia il saveJSON cosi almeno sovvrascrive
        //i dati tramite id
    }

    public function getSuccessivo(){
        return $this->successivo;
    }

    public function setSuccessivo($successivo){
        $this->successivo = $successivo;
        $this->calculateHash();            //Se si aggiunge successivo calcola hash
    }

    public function getPrimo(){
        return $this->first;
    }

    public function getPrecedente(){
        return $this->precedente;
    }

    public function setPrecedente($precedente){
        $this->precedente = $precedente;
        $this->calculateHash();            //Se si aggiunge precedente calcola hash
    }

    public function getActual(){
        return $this->actual;
    }

    public function getHash(){
        return $this->hash;
    }

    public function getId() {
        return $this->id;
    }
    public function getTransactions(){
        return $this->transactions;
    }

    public function addTransactions($trans) {
        array_push($this->transactions,$trans);
        $this->calculateHashtransaction();          //calcola hash ogni volta che aggiungi
        $this->calculateHash();
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
