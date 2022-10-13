<?php


namespace Mysticzap\TiNetClink\tnet\v2;


use GuzzleHttp\RequestOptions;
use http\Env\Request;
use Mysticzap\TiNetClink\BasicApi;
use Mysticzap\TiNetClink\Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * 天润融通2.0接口基础类
 * @package Mysticzap\TiNetClink\tnet\v2
 */
class Api extends BasicApi
{
    /**
     * 生成时间戳
     * @return false|string
     */
    protected function generateTimestamp(){
        return date("Y-m-dTH:i:sZ");
    }

    /**
     * get接口调用
     * @param 接口地址 $uri
     * @param 参数 $params
     * @param array $headers
     * @return mixed|void
     */
    public function get($uri, $params = [], $headers = [])
    {
        // TODO: Implement get() method.
        $params = array_merge([
            "AccessKeyId" => $this->configure->accessKeyId,
            "Expires" => $this->configure->expires,
            "Timestamp" => $this->generateTimestamp(),
        ], $params);
        // 判断是否带域名的全路径接口，不是则补全
        $fullUri = false === stripos($uri, $this->configure->baseUri) ? $this->configure->baseUri . $uri : $uri;
        // 用于签名
        $sParams = $this->a2sAfterKsort($this->ksort($params));
        $signature = $this->signature($fullUri, $sParams, self::METHOD_GET);

        $options = [
            "query"=> "{$sParams}&Signature={$signature}"
        ];
        if(!empty($headers)){
            $options[RequestOptions::HEADERS] = $headers;
        }
        return $this->request($uri, $options, self::METHOD_GET);
    }


    public function post($uri, $postParams, $queryParams, $headers = [], $postParamType = 'json')
    {
        $options = [];
        if(!empty($postParams)){
            $options[$postParamType] = $postParams;
        }
        $params = array_merge([
            "AccessKeyId" => $this->configure->accessKeyId,
            "Expires" => $this->configure->expires,
            "Timestamp" => $this->generateTimestamp(),
        ], $queryParams);
        // 判断是否带域名的全路径接口，不是则补全
        $fullUri = false === stripos($uri, $this->configure->baseUri) ? $this->configure->baseUri . $uri : $uri;
        // 用于签名
        $sParams = $this->a2sAfterKsort($this->ksort($params));
        $signature = $this->signature($fullUri, $sParams, self::METHOD_POST);
        return $this->request("{$uri}?{$sParams}&Signature={$signature}", $options, self::METHOD_POST);
    }

    public function request($uri, $options, $method = 'GET')
    {
        try{
            $response = $this->client->request($method, $uri, $options);
            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);
            // 成功
            if($statusCode >= 200 && $statusCode < 300){

            } else {
                // 失败
                Logger::getInstance()->debug();
            }

        } catch(\Exception $e){
            throw new Exception("", '', $e);
        }


    }

    /**
     * 生成前面
     * @param $uri
     * @param string $params  排序号的参数
     */
    public function signature($uri, $params, $method = 'GET'){
        // 判断是否带域名的全路径接口，不是则补全
        $uri = false === stripos($uri, $this->configure->baseUri) ? $this->configure->baseUri . $uri : $uri;
        return  $this->signature->signature($method, $uri, $params);
    }
}