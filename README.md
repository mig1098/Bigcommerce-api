Bigcommerce-api
===============

bigcommerce api okey

```
<?php

include 'Api.php';

define('BIGCOMM_API_TOKEN','456456456456456a35424789');//your token for private app
define('BIGCOMM_PATH','https://you-store.mybigcommerce.com');//your store
define('BIGCOMM_USERNAME','apolo1');

use Bigcommerce\Api\Client;

Client::configure(array(
    			'store_url' => BIGCOMM_PATH,
    			'username' => BIGCOMM_USERNAME,
    			'api_key' => BIGCOMM_API_TOKEN,
    		));
            
Client::setCipher('RC4-SHA');//rsa_rc4_128_sha, RC4-SHA, RSA_RC4_128_SHA
```
