<?php

namespace AutoProxyCurl\Http;

class Curl
{
    /** @var resource cURL handle */
    private $ch;

    /** @var mixed The response */
    private $response = false;

    /**
     * Curl constructor.
     * @param $url
     * @param array $options
     * @param bool $proxy
     */
    public function __construct($url, array $options = array(), $proxy = false)
    {
        $this->ch = curl_init($url);

        foreach ($options as $key => $val) {
            curl_setopt($this->ch, $key, $val);
        }

        if($proxy){
            $proxy = explode(':', $this->ProxyGetir());
            curl_setopt($this->ch, CURLOPT_PROXY, $proxy[0]);
            curl_setopt($this->ch, CURLOPT_PROXYPORT, $proxy[1]);
        }

        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * Get the response
     * @return string
     * @throws \RuntimeException On cURL error
     */
    public function getResponse()
    {
        if ($this->response) {
            return $this->response;
        }

        $response = curl_exec($this->ch);
        $error    = curl_error($this->ch);
        $errno    = curl_errno($this->ch);

        if (is_resource($this->ch)) {
            curl_close($this->ch);
        }

        if (0 !== $errno) {
            throw new \RuntimeException($error, $errno);
        }

        return $this->response = $response;
    }

    /**
     * @return mixed
     */
    function ProxyGetir()
    {
        $curl = curl_init();
        $s = array(
            CURLOPT_URL => "https://www.sslproxies.org", CURLOPT_REFERER => "https://google.com.tr",
            CURLOPT_SSL_VERIFYPEER => false, CURLOPT_RETURNTRANSFER => FALSE,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36",
            CURLOPT_RETURNTRANSFER => TRUE, CURLOPT_FOLLOWLOCATION => TRUE
        );
        curl_setopt_array($curl,$s);
        $response = curl_exec($curl);
        curl_close($curl);
        $regex = '@<td>(.*?)</td>@si';
        preg_match_all($regex,$response,$return);
        $proxyAndPorts = $return[1];
        $proxy = array();
        $port = array();
        $randomProxy = array();
        $sayiFank = array();
        for($i=0; $i < 400; $i+=4) {$proxy[] = $proxyAndPorts[$i];}
        for ($i=1; $i < 400; $i+=4) {$port[] = $proxyAndPorts[$i];}
        $merge = array_merge($proxy,$port);
        for ($i=100; $i < 200; $i++) {$sayiFank[] =  $i;}
        for ($i=0; $i < 99 ; $i++){$randomProxy[] = $merge[$i].":".$merge[$sayiFank[$i]];}
        return $randomProxy[rand(0,98)];
    }

    /**
     * Let echo out the response
     * @return string
     */
    public function __toString()
    {
        return $this->getResponse();
    }
}