<?php 
namespace Controller;
use Model\Bigcommerce as bigcomModel;
class Bigcommerce {
    private $bigcommerce;
    
    public function __construct(){
    	//private api
        $this->bigcommerce = new bigcomModel(array(
            'type'     => 'private',
            'username' => 'your-user',
            'api_path' => 'https://your-store.mybigcommerce.com/api/v2/',
	    'api_token'=>'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
        ));
        /*
        //public api
        $this->bigcommerce = new bigcomModel(array(
            'type'=>'public',
            'context'=>'stores/your-store-context',
            'access_token'=>'your-access-token'
        ));
        */
    }
    
    public function execute($callback){
        if(is_callable($callback)){
            $callback($this);
        }
    }
}
