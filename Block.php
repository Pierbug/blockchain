<?php
require_once("Host.php");
require_once("Transaction.php");

class Block {
    private $id;
    private $actual;
    private $first;
    private $transactions = [];
    private $successivo;
    private $precedente;

    private $hash;
    private $hash_succ;
    private $hash_prec;

    public function __construct($actual,$first = null,$precedente = null){
        if($first == null){ 
            $this->actual = $actual;
            $this->successivo = new Host();
            $this->first = $actual;
            $this->precedente = new Host();
            $this->transactions = [];
            $this->id = $this->calculateId();
            $this->hash=$this->calculateHash();
            $this->hash_prec = null;
            $this->hash_succ = null;
        } else {
            if (file_exists('http://' . $actual->getIp() . ':' . $actual->getPorta() . '/block.json')) {
                $this->importjson();
            } else {
                $this->actual = $actual;
                $this->successivo = new Host();
                $this->first = $first;
                $this->precedente = new Host();
                $this->transactions = [];
                $this->id = $this->calculateId();
                $this->hash = $this->calculateHash();
                $this->hash_prec = $this->calculatePrecHash();
                $this->hash_succ = null;
                $this->updateOtherJson($this->precedente);
            }
        }
        $this->saveJson();   
    }
    private function calculatePrecHash(){
        $block= new Block($this->precedente,Block::firstHost());
        return $block->getHash();
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
        $data = [
            'id' => $this->id,
            'hash' => $this->hash,
            'hash_succ'=>$this->hash_succ,
            'hash_prec'=>$this->hash_prec,
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
        file_put_contents('http://' . $this->actual->getIp() . ':' . $this->actual->getPorta() . '/block.json', $json_data);
    }
    public function importJson(){
        $filename = 'http://' . $this->actual->getIp() . ':' . $this->actual->getPorta() . '/block.json';
        $json = file_get_contents($filename);
        $array = json_decode($json, true);
        $trans = $array['transazioni'];
        $this->id = $array['id'];
        $this->hash = $array['hash'];
        $this->hash_prec = $array['hash_prec'];
        $this->hash_succ = $array['hash_succ'];
        $this->first=new Host($array['first IP'],$array['first Port']);
        $this->actual = new Host($array['actual IP'],$array['actual Port']);
        $this->successivo = new Host($array['successivo IP'],$array['successivo Port']);
        $this->precedente = new Host($array['precedente IP'],$array['precedente Port']);
        for($i=0;$i<count($trans);$i++){
            $t = $trans[$i];
            $this->addTransactions(new Transaction($t['mittente'],$t['destinatario'],$t['amount'],$t['time']));
        }
    }
    private function updateOtherJson($host, $prec=true){
        $filename = 'http://' . $host->getIp() . ':' . $host->getPorta() . '/block.json';
        $json = file_get_contents($filename);
        $array = json_decode($json, true);
        $trans = $array['transazioni'];
        $id = $array['id'];
        $hash = $array['hash'];
        if ($prec) {
            $hash_prec = $this->hash_prec;
            $hash_succ = $array['hash_succ'];
        }else{
            $hash_prec = $array['hash_prec'];
            $hash_succ = $this->hash_succ;
        }
        
        $actual = new Host($array['actual IP'],$array['actual Port']);
        $successivo = new Host($array['successivo IP'],$array['successivo Port']);
        $precedente = new Host($array['precedente IP'],$array['precedente Port']);
        $first=new Host($array['first IP'],$array['first Port']);
        $data = [
            'id' => $id,
            'hash' => $hash,
            'hash_succ'=>$hash_succ,
            'hash_prec'=>$hash_prec,
            'actual IP' => $actual->getIp(),
            'actual Port' => $actual->getPorta(),
            'first IP' => $first->getIp(),
            'first Port' => $first->getPorta(),
            'successivo IP' => $successivo->getIp(),
            'successivo Port' => $successivo->getPorta(), 
            'precedente IP' => $precedente->getIp(),
            'precedente Port' => $precedente->getPorta(),
            'transazioni' => $trans
        ];
        $json_data = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filename, $json_data);
    }
    public function getHash(){
        return $this->hash;
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
        $this->saveJson();   
    }
    private function printArray(){
        $arr=[];
        foreach($this->transactions as $trans){
            array_push($arr,["mittente" => $trans->getMittente(), "destinatario" => $trans->getDestinatario(), "amount" => $trans->getAmount(), "time" => $trans->getTime(), "hash" => $trans->getHash()]);
        }
        return $arr;
    }
    static function firstHost(){
        return new Host("192.168.12.15",80);
    }
    static function lastHost(){
        $firstBlock=new Block(Block::firstHost(),Block::firstHost());
        if ($firstBlock->getSuccessivo() == null) {
            return $firstBlock->getActual();
        }else{
            $host=$firstBlock->getSuccessivo();
            while(true){
                $block = new Block($host, Block::firstHost());
                if($block->getSuccessivo()==null){
                    return $block->getActual();
                }else{
                    $host = $block->getSuccessivo();
                }
            }
        }   
    }
    static function hostExists($host)
    {
        return file_exists('http://' . $host->getIp() . ':' . $host->getPorta() . '/block.json');
    }



}
