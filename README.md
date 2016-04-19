## bigcommerce api ##


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

***Set in Controller construct Bigcommerce.php:***
```
$this->bigcommerce = new bigcomModel(array(
    'type'     => 'private',
    'username' => 'your-user',
    'api_path' => 'https://your-store.mybigcommerce.com/api/v2/',
    'api_token'=> 'your-api-token'
));
```

Public Auth
-----------
Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat

Create data
-----------
At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores

Update data
-----------
At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores

Delete data
-----------
At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores
