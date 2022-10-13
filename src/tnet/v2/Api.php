<?php


namespace mysticzap\tinetclink\tnet\v2;


use GuzzleHttp\RequestOptions;
use http\Env\Request;
use mysticzap\tinetclink\BasicApi;
use mysticzap\tinetclink\BasicConfigure;
use mysticzap\tinetclink\BasicSignature;
use mysticzap\tinetclink\Exception;
use mysticzap\tinetclink\Logger;
use Psr\Http\Message\ResponseInterface;

/**
 * 天润融通2.0接口基础类
 * 第三方接口文档地址：https://develop.clink.cn/develop/api/cc.html#_%E5%A4%96%E5%91%BC
 * @package mysticzap\tinetclink\tnet\v2
 */
abstract class Api extends BasicApi
{
    public function __construct(BasicConfigure $configure, BasicSignature $signature, Logger $logger = null)
    {
        parent::__construct($configure, $signature, $logger);
    }

    /**
     * 请求时直接调用的接口
     * @param $params
     * @return mixed
     */
    public function send($params){
        $data  = $this->post($this->uri, $params);
        return $this->formatResponse($data);
    }

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


    public function post($uri, $postParams, $queryParams = [], $headers = [], $postParamType = 'json')
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
                return $data;
            } else {
                // 失败
                $this->logger->debug('调用天润2.0接口失败', [
                    "method"=>$method,
                    "uri"=>$uri,
                    "options" => $options,
                    "responseStatusCode" => $statusCode,
                    'response' => $data,
                ]);
                throw new ApiHttpException(ErrorCode::ERROR_API_CALL);
            }

        } catch(\Exception $e){
            throw $e;
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