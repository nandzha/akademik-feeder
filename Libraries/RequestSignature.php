<?php
namespace Libraries;
use Resources;

class RequestSignature {
    
    public function __construct(){
        
        $this->session = new Resources\Session;
    }
    
    public function generate(){
        
        $signature = md5( uniqid(rand(), true) );
        $this->session->setValue( 'postSignature', $signature );
        
        return $signature;
    }
    
    public function validate($signature){
        
        if($signature == $this->session->getValue( 'postSignature') )
            return true;
        
        return false;
    }
}