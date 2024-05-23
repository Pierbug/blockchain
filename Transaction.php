<?php
class Transaction {
    private $mittente;
    private $destinatario;
    private $amount;
    private $time;
    private $hash;

    function __construct($mittente, $destinatario, $amount, $time, $hash = null) {
        $this->setMittente($mittente);
        $this->setDestinatario($destinatario);
        $this->setAmount($amount);
        $this->setTime($time);
        if($hash == null)
            $this->hash = $this->calculateHash();
        else
            $this->hash = $hash;        //faccio cio perche se importo transazioni hanno gia l'hash
    }

    private function calculateHash() {
        $dataToHash = $this->mittente . $this->destinatario . $this->amount . $this->time;
        return hash('sha256', $dataToHash);
    }

    public function getMittente() {
        return $this->mittente;
    }

    public function setMittente($mittente) {
        $this->mittente = $mittente;
        $this->calculateHash();
    }

    public function getDestinatario() {
        return $this->destinatario;
    }

    public function setDestinatario($destinatario) {
        $this->destinatario = $destinatario;
        $this->calculateHash();
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
        $this->calculateHash(); 
    }

    public function getTime() {
        return $this->time;
    }

    public function setTime($time) {
        $this->time = $time;
        $this->calculateHash(); 
    }

    public function getHash() {
        return $this->hash;
    }
}
?>