<?php

require '../config.php';
require DOL_DOCUMENT_ROOT . '/product/class/product.class.php';


$get = GETPOST('get', 'alpha');

switch ($get) {
	case 'priceProduct':
		getPricrProduct(GETPOST('fk_product'));
		break;
}


function getPricrProduct($fk_product) {
	global $db;
	
	$price = 0;
	
	if (!empty($fk_product))
	{
		$product = new Product($db);
		if ($product->fetch($fk_product) > 0)
		{
			$price = price($product->price);
		}	
	}
	
	__out($price);
}
