<?php 
namespace Controller;
use Model\Bigcommerce as bigcomModel;
class Bigcommerce {
    private $bigcommerce;
    private $keyindex = 1;
    private $publicKeys = array(
        array('context'=>'stores/xxxxxxxx','access_token'=>'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),//apolo royalty big
    );
    public function __construct(){
        //$this->bigcommerce = new bigcomModel(array('type'=>'private','username'=>BIGCOMMERCE_USERNAME,'api_path'=>BIGCOMMERCE_API_PATH,'api_token'=>BIGCOMMERCE_API_TOKEN));
        $this->bigcommerce = new bigcomModel(array('type'=>'public','context'=>$this->publicKeys[$this->keyindex]['context'],'access_token'=>$this->publicKeys[$this->keyindex]['access_token']));
    }
    public function index(){

        $a = parent::getUriSegment(1);
        switch($a){
            default:
                echo 'Wellcome to Bigcommerce Api';
            break;
        }
    }
    public function test(){
        print_r($GLOBALS);
    }


}