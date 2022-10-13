<?php


namespace mysticzap\tinetclink;

use GuzzleHttp\Client  as GuzzleHttpClient;
use GuzzleHttp\RequestOptions;

/**
 * 接口基础类
 * @package mysticzap\tinetclink
 */
abstract class BasicApi implements IApi
{
    /**
     * request submit data type
     */
    const REQUEST_TYPE_FORM = 'form_params';
    const REQUEST_TYPE_JSON = 'json';
    /**
     * request method
     */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    /**
     * @var string 接口，不包含域名， 如 “/getuser"
     * 定义具体类时，把值固定
     */
    public $api = '';
    /**
     * @var string 接口名称
     * 定义具体类时，把值固定
     */
    public $apiName = '';
    /**
     * @var string 请求方式
     * 定义具体类时，把值固定
     */
    public $method = self::METHOD_GET;
    /**
     * @var array 请求包含的参数，以及是否必填
     * 定义具体类时，把值固定
     */
    public $requestParamsRequired = [];
    /**
     * @var GuzzleHttpClient
     */
    public $client;
    /**
     * @var BasicConfigure 第三方配置
     */
    public $configure;
    /**
     * @var BasicSignature 签名类
     */
    public $signature;
    /**
     * @var Logger 日志对象
     */
    public $logger;
    public function __construct(BasicConfigure $configure, BasicSignature $signature, Logger $logger = null)
    {
        // 初始化配置
        $this->configure = $configure;
        $this->logger = null !== $logger ? $logger:new Logger($this->configure->log, $this->configure->logLevel);
        // 签名类初始化
        $this->signature = $signature;
        // 初始化客户端配置
        $clientConfigureDefaults = [];
        // 接口基础域名
        if(!empty($this->configure->baseUri)){
            $clientConfigureDefaults['base_uri'] = $this->configure->baseUri;
        }
        // 接口调用超时设置
        if(!empty($this->configure->timeout)){
            $clientConfigureDefaults['timeout'] = $this->configure->timeout;
        }
        // 初始化 guzzle
        $this->client = new GuzzleHttpClient($clientConfigureDefaults);
    }

}