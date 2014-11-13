<?php
    
include __DIR__.'/../../vendor/autoload.php';

/**
 * Authentication
 *
 * You'll get the client id and secret at the plaform (API Access) 
 **/
$Client = new Productsup\Client();
$Client->id = 1234;
$Client->secret = 'simsalabim';

$ProductService = new Productsup\Service\ProductData($Client);

/**
 * Optional, define chunk site to submit products in parts using multiple 
 * post requests. Default is 5000
 */
$ProductService->setPostLimit(1000);

$Reference = new Productsup\Platform\Site\Reference();

/**
 * In case you have a productsup site id 
 **/
$Reference->setKey($Reference::REFERENCE_SITE);
$Reference->setValue(397); // Site ID

/**
 * In case you want to use your own reference
 **/
//$Reference->setKey('merchant_id'); // A site tag
//$Reference->setValue(1234); // Value of the tag

$ProductService->setReference($Reference);

/** 
 * Adding one product to insert.
 *
 * A product is represented by an array.
 * There is no fixed structure you have to follow,
 * the keys you use will become the column name for the resulting upload
 *
 * note: you have to call commit() at the end before the data actually gets handled
 */
$ProductService->insert(array(
        'id' => 123,
        'price' => 39.90,
        'description' => 'some text',
    )
);

$ProductService->insert(array(
        'id' => 124,
        'price' => 99.99,
        'description_de' => 'ein text',
    )
);


// adding 5000 random "products"
for($i=0;$i<5000;$i++) {
    $ProductService->insert(
        array(
            'id' => uniqid(),
            'test' => md5(uniqid()),
            'created' => microtime(true),
            'price' => mt_rand(0,1000000)/100
        )
    );
}

/**
 * deleting products works the same as inserting products:
 */
$ProductService->delete(array(
        'id' => 123,
    )
);


/**
 * if you added all products, call commit to start the processing:
 *
 * note: you may not insert or delete products after the submit.
 * if you have more products to insert, please create a new service
 */
$result = $ProductService->commit();
var_dump($result);

/**
 * if you encounter any problems after you started the upload, you can tell the api to discard all uploaded data:
 */
//$result = $ProductService->discard();
