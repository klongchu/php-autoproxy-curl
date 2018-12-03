<?php

include 'curlProxy.class.php';

try {
    // kullanımı
    $curl = new \AutoProxyCurl\Http\Curl('https://www.myip.com/', array(), false);

    echo $curl;
} catch (\RuntimeException $ex) {
    die(sprintf('Http error %s with code %d', $ex->getMessage(), $ex->getCode()));
}
