<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 27/07/17
 * Time: 16:31
 */
/*
 * Test data
 */
require_once('http_lite.php');
try {
    $api = Http::connect('www.itccompliance.co.uk', NULL, 'https')
        ->doGet('recruitment-webservice/api/list');
    $products = json_decode($api);
    if (!isset($products->error)) {
        foreach ($products as & $product)
        {
            $prod_keys = array_keys((array) $product);
            foreach($prod_keys as $key)
            {
                $product->{$key} = filter_var($product->{$key}, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
            }
        }
    }
    else
    {
       die($products->error);
    }
} catch (Http_Exception $e) {
    die('There was an ' . $e->getMessage() . '. Please try again');
}
//var_dump($products);
$product_details = array();
foreach($prod_keys as $key)
{
    try {
        $info = Http::connect('www.itccompliance.co.uk', NULL, 'https')
            ->doGet('recruitment-webservice/api/info', array('id' => $key));
        $details = json_decode($info);
        if (!isset($details->error)) {
            foreach ($details as & $detail)
            {
                $detl_keys = array_keys((array) $detail);
                foreach($detl_keys as $key)
                {
                    if ($key != 'suppliers')
                    {
                        $detail->{$key} = filter_var($detail->{$key}, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
                    }
                }
            }
            $product_details[] = $details;
        }
        else
        {
            $product_details[] = array(
                'error' => $details->error . ' to get key ' . $key
            );
        }
    } catch (Http_Exception $e) {
        die('There was an ' . $e->getMessage() . '. Please try again');
    }
}
//var_dump($product_details);
require_once('products.php');