Requirements
------------
PHP > 5.3

Instructions
------------
All process is executed in index.php

Private Auth
------------
***Get Legacy api accounts:***
-  Username
-  API Path
-  API Token

![sample](http://i.imgur.com/NJHVJIg.jpg)

***Set in Controller Bigcommerce.php - construct:***
```
$this->bigcommerce = new bigcomModel(array(
    'type'     => 'private',
    'username' => 'your-user',
    'api_path' => 'https://your-store.mybigcommerce.com/api/v2/',
    'api_token'=> 'your-api-token'
));
```

Public OAuth
-----------
***Set in Controller Bigcommerce.php - construct:***
```
$this->bigcommerce = new bigcomModel(array(
    'type'=>'public',
    'context'=>'stores/your-context',
    'access_token'=>'your-access-token'
));
```

OAuth get access token
----------------------
...Pending...

Porducts
-----------
***GET***

```
$bigcommerce->execute(function(\Controller\Bigcommerce $controller){
    print_r($controller->bigcommerce->getProducts());
});
```

***CREATE***

```
$bigcommerce->execute(function(\Controller\Bigcommerce $controller){
    $product = array('product'=>array(
        'name'        => 'Productdemo',
        'description' => 'from the php api',
        'type'        => 'physical',
        'weight'      => '1',
        'price'       => '20.00',
        'is_visible'  => true,
        'availability'=> 'available',
        'categories'  => array(15) //category id must be exist
    ));
    print_r($controller->bigcommerce->createProduct($product));
});
```

***UPDATE***

```
$bigcommerce->execute(function(\Controller\Bigcommerce $controller){
    $product = array('product'=>array(
        'id'     => 82, //from product created
        'price'  => 18,
        //'type'   => 'physical',
        //'is_visible'  => true,
        //'availability'=> 'available'
        'categories' => array(15)
    ));
    print_r($controller->bigcommerce->updateProduct($product));
});
```
