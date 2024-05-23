<?php
class Host{
    private $ip;
    private $porta;

    public function __construct($ip=null, $porta=null){
        $this->ip = $ip;
        $this->porta = $porta;
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
    public function getPorta(){
        return $this->porta;
    }

    public function toAssocArray() {
        return get_object_vars($this);
    }
}
?>