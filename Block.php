<?php
require_once("Host.php");

class Block {
    private $id;
    private $actual;
    private $transactions = [];
    private $successivo;
    private $precedente;

    public function __construct($actual,$precedente = null){
        $this->actual = $actual;
        $this->successivo = new Host();
        if($precedente == null)
            $this->precedente = new Host();
        else
            $this->precedente = $precedente;
        $this->transactions = [];
        $this->id= $this->calculateId();
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

    public function getId() {
        return $this->id;
    }

    public function addTransactions($trans) {
        array_push($this->transactions,$trans);
    }

}

?>
