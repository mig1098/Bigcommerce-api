<?php
include './Library/Api.php';
include './Model/Bigcommerce.php';
include './Controller/Bigcommerce.php';
/**
begin api
**/
$bigcommerce = new \Controller\Bigcommerce();
//
$bigcommerce->index('store-info');