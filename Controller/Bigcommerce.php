<?php 
namespace Controller;
use Model\Bigcommerce as bigcomModel;
class Bigcommerce {
    private $bigcommerce;
    
    public function __construct(){
        $this->bigcommerce = new bigcomModel(array(
            'type'     => 'private',
            'username' => 'your-user',
            'api_path' => 'https://your-store.mybigcommerce.com/api/v2/',
			'api_token'=>'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
        ));
        //
        
    }
    
    public function index($action){
        switch($action){
            case 'store-info':
                print_r($this->bigcommerce->getStoreInformation());
            break;
            default:
                echo 'bigcommerce api';
            break;
        }
    }
}