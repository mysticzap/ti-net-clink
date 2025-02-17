<?php


namespace mysticzap\tinetclink\tnet\v2;


use GuzzleHttp\Exception\RequestException;
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

    /**
     * 验证请求参数必填数据是否存在
     * @param array $param
     */
    public function validation(array $params = []){
        if(empty($this->requestParamsRequired)){
            return true;
        }
        foreach($this->requestParamsRequired as $key => $required){
            // 必填，但是不存在，抛出错误
            if($required == true && !isset($params[$key])){
                $this->logger->debug("请求参数缺失", [
                    'parameterName' => $key
                ]);
                throw new ApiRequestParameterException(ErrorCode::ERROR_REQUEST_PARAMETER_DEFECT);
            }
        }
    }
    /**
     * 返回数据格式化
     * 针对具体类实现时，需要重构成符合自己系统接口的数据格式
     * @param mixed $responseData
     * @return mixed|void
     */
    public function formatResponse($responseData){
        return $responseData;
    }
    /**
     * 请求时直接调用的接口
     * @param array $params 请求参数
     * @param string $postParamType 请求发送数据格式，与Content-Type匹配
     * @param array $headers http request header
     * @return mixed
     */
    public function send($params, $postParamType = self::REQUEST_TYPE_FORM, $headers = []){
        switch ($this->method){
            case self::METHOD_GET:
                $data  = $this->get($this->api, $params, $headers);
                break;
            case self::METHOD_POST:
                $data  = $this->post($this->api, $params, [], $headers, $postParamType);
                break;
            default:
                throw new ApiHttpException(ErrorCode::ERROR_API_METHOD);
        }

        return $this->formatResponse($data);
    }

    /**
     * 生成时间戳
     * @return false|string
     */
    protected function generateTimestamp(){
        $time = time();
        $date = new \DateTime('now', new \DateTimeZone("UTC"));
        $sDate = $date->format('Y-m-d\\TH:i:s\\Z');
        $this->logger->debug("生成签名时间戳", ['date'=>$sDate]);
        return $sDate;
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
        $fullUri = false === stripos($uri, $this->configure->baseUri) ? ($this->configure->baseUri . $uri) : $uri;
        // 用于签名
        $aParams = $this->signature->ksort($this->signature->a2sBeforeKsort($params));
        $sParams = $this->signature->getHttpBuildQuery($aParams);
        $signature = $this->signature($fullUri, $sParams, self::METHOD_GET);

        $options = [
//            "query"=> "{$sParams}&Signature={$signature}"
        ];
        if(!empty($headers)){
            $options[RequestOptions::HEADERS] = $headers;
        }
        return $this->request($uri . "?{$sParams}&Signature={$signature}", $options, self::METHOD_GET);
    }


    public function post($uri, $postParams, $queryParams = [], $headers = [], $postParamType = self::REQUEST_TYPE_FORM)
    {
        $options = [];
        if(!empty($postParams)){
            $options[$postParamType] = $postParams;
        }

        $this->logger->debug("天润接口post参数", ['option' =>$options]);
        $params = array_merge([
            "AccessKeyId" => $this->configure->accessKeyId,
            "Expires" => $this->configure->expires,
            "Timestamp" => $this->generateTimestamp(),
        ], $queryParams);
        $this->logger->debug("天润接口url参数", ['queryParams' =>$params]);
        // 判断是否带域名的全路径接口，不是则补全
        $fullUri = false === stripos($uri, $this->configure->baseUri) ? $this->configure->baseUri . $uri : $uri;
        // 用于签名
        $aParams = $this->signature->ksort($this->signature->a2sBeforeKsort($params));
        $sParams = $this->signature->getHttpBuildQuery($aParams);
        $signature = $this->signature($fullUri, $sParams, self::METHOD_POST);
        return $this->request("{$uri}?{$sParams}&Signature={$signature}", $options, self::METHOD_POST);
    }

    public function request($uri, $options, $method = 'GET')
    {
        try{
            $response = $this->client->request($method, $uri, $options);
            $statusCode = $response->getStatusCode();
            $data = !empty($response->getBody()) ? json_decode($response->getBody(), true) : [];
            // 成功
            if($statusCode >= 200 && $statusCode < 300){
                return $data;
            } else {
                throw new ApiHttpException(ErrorCode::ERROR_API_CALL);
            }
        } catch(\Exception $e){
            // 失败
            $errorLog = [
                "method"=>$method,
                "uri"=>$uri,
                "options" => $options,
            ];
            if($e instanceof RequestException){
                // 获得状态码不是200的返回的错误数据
                $body = $e->getResponse()->getBody();
                $aBody = !empty($body)?json_decode($body, true):[];
                $statusCode = $e->getResponse()->getStatusCode();
                $errorLog['statusCode'] = $statusCode;
                $errorLog['body'] = $aBody;

                $thirdErrorCode = $aBody['error']['code']??"";
                // 过滤掉指定的第三方错误，不记录日志
                $filterErrors = ['DownloadNotExistsError'];
                if(!(!empty($thirdErrorCode) && in_array($thirdErrorCode,$filterErrors))){
                    $this->logger->error('调用天润2.0接口失败', $errorLog);
                }
                throw new ApiHttpException(ErrorCode::ERROR_API_CALL, [],
                    $aBody['error']['code'], $statusCode);
            } else {
                $this->logger->error('调用天润2.0接口失败', $errorLog);
                throw $e;
            }
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