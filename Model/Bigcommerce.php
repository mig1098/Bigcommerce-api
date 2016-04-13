<?php
namespace Model;
/**
 * BIGCOMMERCE PUBLIC AND PRIVATE APP
 * Using the last bigcommerce api release at 2015-06-30
 * From https://github.com/bigcommerce/bigcommerce-api-php
 * -------------------------------------------------------------
 * @author miglio <mig1098@hotmail.com>
 * @copyright 2015
 * -------------------------------------------------------------
 * DEVELOPER APP KEYS:
 * {@CLIENT_ID} , {@CLIENT_SECRET}
 * -------------------------------------------------------------
 * STORE PUBLIC OWNER KEYS:
 * {@access_token} , {@context}
 * -------------------------------------------------------------
 * STORE PRIVATE OWNER KEYS:
 * {@BIGCOMM_API_TOKEN} , {@BIGCOMM_PATH} , {@BIGCOMM_USERNAME}
 * -------------------------------------------------------------
 * */
use Bigcommerce\Api\Connection as BigcommerceCnx;
class Bigcommerce{
    public $LIMIT = 250;
    private $bigcommerce  = null;
    private $context      = null;
    private $access_token = null;
    const VERIFYPEER  = false;
    const BIG_PUBLIC  = 'public';
    const BIG_PRIVATE = 'private';
    //------------------------------------------------------------------
    // public keys
    //------------------------------------------------------------------
    public $CLIENT_ID     = ''; 
    public $CLIENT_SECRET = ''; 
    public $CLIENT_REDIRECR_URI = 'https://domain.com/folder/customer/install'; 
    //------------------------------------------------------------------
    // private keys
    //------------------------------------------------------------------
    private $BIGCOMM_API_TOKEN = '';
    private $BIGCOMM_PATH      = '';
    private $BIGCOMM_USERNAME  = '';
    private $TYPE   = null;
    //------------------------------------------------------------------
    // scopes
    //------------------------------------------------------------------
    private $SCOPES = array(
        0  => 'store/order/created',
        1  => 'store/order/updated',
        2  => 'store/order/archived',
        3  => 'store/order/statusUpdated',
        4  => 'store/product/created',
        5  => 'store/product/updated',
        6  => 'store/product/deleted',
        7  => 'store/customer/created',
        8  => 'store/customer/updated',
        9  => 'store/customer/deleted',
        10 => 'store/app/uninstalled'
    );
    public function __construct($args = array('type'=>'')){
        $this->TYPE = isset($args['type'])?$args['type']:false;
        if($this->TYPE == self::BIG_PUBLIC){
            $this->publickeys($args);
        }else if($this->TYPE == self::BIG_PRIVATE){
            $this->privatekeys($args);
        }else{
            exit('type is not defined[type can be public or private]');
        }
        $this->bigcommerce = new BigcommerceCnx();
    }
    public function privatekeys($data=array()){
        if(!empty($data['username']))$this->BIGCOMM_USERNAME   = $data['username'];
        if(!empty($data['api_path']))$this->BIGCOMM_PATH       = $data['api_path'];
        if(!empty($data['api_token']))$this->BIGCOMM_API_TOKEN = $data['api_token'];
    }
    public function publickeys($data=array()){
        if(!empty($data['context']))$this->context           = $data['context'];
        if(!empty($data['access_token']))$this->access_token = $data['access_token'];
    }
    public function setContext($value){
        $this->context = $value;
    }
    public function setAccessToken($value){
        $this->access_token = $value;
    }
    /**
     * PUBLIC AND PRIVATE METHODS
     **/
    //products or product
    public function getProducts($data=array()){
        $limit = !empty($data['limit'])?$data['limit']:$this->LIMIT;
        $page = !empty($data['page'])?$data['page']:1;
        if($this->TYPE == self::BIG_PUBLIC){
            $source = empty($data['product_id'])?'/v2/products':'/v2/products/'.$data['product_id'];
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = empty($data['product_id'])?'products?limit='.$limit.'&page='.$page:'products/'.$data['product_id'];
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    public function getProductsAllPaginated($ini=1,$breakin=0,$_total=0){//sample initiate in 2 and break then 3 loops
        /**
         * massive quantity of products can affect the performance of this method
         * */
        if($_total>0){
            $total = $_total;
        }else if($_total==-1){
            return false;
        }else{
            $total = $this->getProductsCount();
            $total = $total->count;
        }
        $limit = $this->LIMIT;
        $paginate = ($total >= $limit) ? ceil($total/$limit) : 1;
        $totals = array();
        $break = 1;
        for($i=$ini; $i<=$paginate; $i++){
            $products = $this->getProducts(array('page'=>$i,'limit'=>$limit));
            $totals = array_merge($totals,$products);
            if($breakin == $break){
                break;
            }
            $break+=1;
        }
        return $totals;
    }
    public function getProductsCount(){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/products/count';
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = 'products/count';
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    public function getProductFilter($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = empty($data['filter'])?'/v2/products':'/v2/products?'.http_build_query($data['filter']);
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = empty($data['filter'])?'products':'products?'.http_build_query($data['filter']);
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    public function getProductOptions($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/products/'.$data['product_id'].'/options';
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = 'products/'.$data['product_id'].'/options';
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    public function getProductOption($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/products/'.$data['product_id'].'/options/'.$data['option_id'];
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = 'products/'.$data['product_id'].'/options/'.$data['option_id'];
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    public function createProduct($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/products/';
            return $this->bigcommerce->post($this->setPublicToken($source),$data['product']);
        }
        $source = 'products/';
        return $this->bigcommerce->post($this->setPrivateToken($source),$data['product']);
    }
    public function updateProduct($data=array()){
        $product_id = $data['product']['id'];
        array_splice($data['product'],0,1);
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/products/'.$product_id;
            return $this->bigcommerce->put($this->setPublicToken($source),$data['product']);
        }
        $source = 'products/'.$product_id;
        return $this->bigcommerce->put($this->setPrivateToken($source),$data['product']);
    }
    public function deleteProduct($product_id){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/products/'.$product_id;
            return $this->bigcommerce->delete($this->setPublicToken($source));
        }
        $source = 'products/'.$product_id;
        return $this->bigcommerce->delete($this->setPrivateToken($source));
    }
    //product sku
    public function getProductSkus($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = empty($data['sku'])?'/v2/products/'.$data['product_id'].'/skus':'/v2/products/'.$data['product_id'].'/skus?sku='.$data['sku'];
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = empty($data['sku'])?'products/'.$data['product_id'].'/skus':'products/'.$data['product_id'].'/skus?sku='.$data['sku'];
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    //options
    public function getOptions($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/options';
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = 'options';
        return $this->bigcommerce->get($this->setPrivateToken($source));
    } 
    public function getOption($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/options/'.$data['option_id'];
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = 'options/'.$data['option_id'];
        return $this->bigcommerce->get($this->setPrivateToken($source));
    } 
    public function getOptionValues($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/options/'.$data['option_id'].'/values';
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = 'options/'.$data['option_id'].'/values';
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    //store info
    public function getStoreInformation(){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/store';
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = 'store';
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    //categories
    public function getCategories($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = empty($data['category_id'])?'/v2/categories':'/v2/categories/'.$data['category_id'];
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = empty($data['category_id'])?'categories':'categories/'.$data['category_id'];
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    //orders
    public function orderCreate($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/orders/';
            return $this->bigcommerce->post($this->setPublicToken($source),$data['order']);
        }
        $source = 'orders/';
        return $this->bigcommerce->post($this->setPrivateToken($source),$data['order']);
    }
    public function orderUpdate($data=array()){
        $order_id = $data['order']['id'];
        array_splice($data['order'],0,1);
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/orders/'.$order_id;
            return $this->bigcommerce->put($this->setPublicToken($source),$data['order']);
        }
        $source = 'orders/'.$order_id;
        return $this->bigcommerce->put($this->setPrivateToken($source),$data['order']);
    }
    public function orderDelete($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/orders/'.$data['order_id'];
            return $this->bigcommerce->delete($this->setPublicToken($source));
        }
        $source = 'orders/'.$data['order_id'];
        return $this->bigcommerce->delete($this->setPrivateToken($source));
    }
    public function getOrders($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = empty($data['order_id'])?'/v2/orders':'/v2/orders/'.$data['order_id'];
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = empty($data['order_id'])?'orders':'orders/'.$data['order_id'];
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    public function getOrdersFilter($data=array()){
        $filter = (!empty($data['filter']))?'?'.http_build_query($data['filter']):'';
        if($this->TYPE == self::BIG_PUBLIC){
            $source = empty($data['order_id'])?'/v2/orders'.$filter:'/v2/orders/'.$data['order_id'];
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = empty($data['order_id'])?'orders'.$filter:'orders/'.$data['order_id'];
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    public function orderProducts($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/orders/'.$data['order_id'].'/products';
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = 'orders/'.$data['order_id'].'/products';
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    public function orderCoupons($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = empty($data['coupon_id'])?'/v2/orders/'.$data['order_id'].'/coupons/'.$data['coupon_id']:'/v2/orders/'.$data['order_id'].'/coupons';
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = empty($data['coupon_id'])?'orders/'.$data['order_id'].'/coupons/'.$data['coupon_id']:'orders/'.$data['order_id'].'/coupons';
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    public function orderShippingAddress($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = empty($data['shipp_address_id'])?'/v2/orders/'.$data['order_id'].'/shipping_addresses/'.$data['shipp_address_id']:'/v2/orders/'.$data['order_id'].'/shipping_addresses';
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = empty($data['shipp_address_id'])?'orders/'.$data['order_id'].'/shipping_addresses/'.$data['shipp_address_id']:'orders/'.$data['order_id'].'/shipping_addresses';
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    //customer
    public function getCustomers($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = empty($data['customer_id'])?(empty($data['query']))?'/v2/customers':'/v2/customers?'.$data['query']:'/v2/customers/'.$data['customer_id'];
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = empty($data['customer_id'])?(empty($data['query']))?'customers':'customers?'.$data['query']:'customers/'.$data['customer_id'];
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    //webhooks
    public function webhookCreate($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/hooks';
            return $this->bigcommerce->post($this->setPublicToken($source),$data['webhook']);
        }
        $source = 'hooks';
        return $this->bigcommerce->post($this->setPrivateToken($source),$data['webhook']);
    }
    public function webhookUpdate($data=array()){
        $webhook_id = $data['webhook']['id'];
        array_splice($data['webhook'],0,1);
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/hooks/'.$webhook_id;
            return $this->bigcommerce->put($this->setPublicToken($source),$data['webhook']);
        }
        $source = 'hooks/'.$webhook_id;
        return $this->bigcommerce->put($this->setPrivateToken($source),$data['webhook']);
    }
    public function webhookDelete($webhook_id){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/hooks/'.$webhook_id;
            return $this->bigcommerce->delete($this->setPublicToken($source));
        }
        $source = 'hooks/'.$webhook_id;
        return $this->bigcommerce->delete($this->setPrivateToken($source));
    }
    public function getWebhoooks($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = empty($data['webhook_id'])?'/v2/hooks':'/v2/hooks/'.$data['webhook_id'];
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = empty($data['webhook_id'])?'hooks':'hooks/'.$data['webhook_id'];
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    //Bulk pricing rules applied to a product.
    public function getBulkPricing($data=array()){
        if($this->TYPE == self::BIG_PUBLIC){
            $source = '/v2/products/'.$data['product_id'].'/discount_rules';
            return $this->bigcommerce->get($this->setPublicToken($source));
        }
        $source = 'products/'.$data['product_id'].'/discount_rules';
        return $this->bigcommerce->get($this->setPrivateToken($source));
    }
    //scopes
    public function getScope($index){
        return $this->SCOPES[$index];
    }
    //OAUTH AUTHORIZE
    public function get($action,$callback){
        $this->bigcommerce->verifyPeer(self::VERIFYPEER);
        switch($action){
            case '/auth/callback':
                return $callback($this,$this->bigcommerce);
            break;
            case '/load':
                return $callback($this,$this->bigcommerce);
            break;
        }
    }
    //
    private function setPublicToken($source){
        $tokenUrl = "https://api.bigcommerce.com/".$this->context.$source;
        $this->bigcommerce->verifyPeer(self::VERIFYPEER);
        $this->bigcommerce->addHeader('X-Auth-Client', $this->CLIENT_ID);//X-Custom-Auth-Header
        $this->bigcommerce->addHeader('X-Auth-Token', $this->access_token);//X-Auth-Token
        //var_dump($tokenUrl);
        return $tokenUrl;
    }
    //
    private function setPrivateToken($source){
        $this->bigcommerce->authenticateBasic($this->BIGCOMM_USERNAME,$this->BIGCOMM_API_TOKEN);
        $this->bigcommerce->verifyPeer(self::VERIFYPEER);
        $tokenUrl = $this->BIGCOMM_PATH.$source;
        //var_dump($tokenUrl);
        return $tokenUrl;
    }
    //Error
    public function getLastError(){
        return $this->bigcommerce->getLastError();
    }
}