<?php
class Host{
    private $ip;
    private $porta;
    private $hash;

    public function __construct($ip=null, $porta=null, $hash=null){
        $this->ip = $ip;
        $this->porta = $porta;
        $this->hash = $hash;
    }
    public function setIp($ip){
        $this->ip = $ip;
    }
    public function getIp(){
        return $this->ip;
    }
    public function setPorta($porta){
        $this->porta = $porta;
    }
    public function getPorta($porta){
        return $this->porta;
    }
    public function setHash($hash){
        $this->hash = $hash;
    }
    public function getHash(){
        return $this->hash;
    }

    public function toAssocArray() {
        return get_object_vars($this);
    }
}
?>