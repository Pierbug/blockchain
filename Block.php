<?php
require_once("Host.php");

class Block {
    private $id;
    private $actual;
    private $transactions = [];
    private $successivo;
    private $precedente;

    private $hash;

    public function __construct($actual,$precedente = null){
        $this->actual = $actual;
        $this->successivo = new Host();
        if($precedente == null)
            $this->precedente = new Host();
        else
            $this->precedente = $precedente;
        $this->transactions = [];
        $this->id = $this->calculateId();
        $this->hash = $this->calculateHash();
    }
    public function calculateHash(){
        $s = "";
        for($i=0;$i<20;$i++){
            $x = rand(48,90);
            if($x < 58 || $x > 64)
                $s .= chr($i);
            else
                $i = $i - 1;
        }
        return $s;
    }
    public function calculateId(){
        $s = "";
        for($i=0;$i<20;$i++){
            $x = rand(48,90);
            if($x < 58 || $x > 64)
                $s .= chr($i);
            else
                $i = $i - 1;
        }
        return $s;
    }

    public function saveJson(){
        $data = [
            'id' => $this->id,
            'actual IP' => $this->actual->getIp(),
            'actual Port' => $this->actual->getPorta(),
            'successivo IP' => $this->successivo->getIp(),
            'successivo Port' => $this->successivo->getPorta(), 
            'precedente IP' => $this->precedente->getIp(),
            'precedente Port' => $this->precedente->getPorta()
        ];

        $json_data = json_encode($data, JSON_PRETTY_PRINT);

        $filename = 'block_' . $this->id . '.json';
        file_put_contents($filename, $json_data);
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
    }

}

?>
