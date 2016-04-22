Requirements
------------
PHP > 5.3

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

Create data
-----------
At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores

Update data
-----------
At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores

Delete data
-----------
At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores
