<?php


namespace Mysticzap\TiNetClink;

use GuzzleHttp\Client  as GuzzleHttpClient;
use GuzzleHttp\RequestOptions;

/**
 * 接口基础类
 * @package Mysticzap\TiNetClink
 */
abstract class BasicApi implements IApi
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    /**
     * @var string 接口，不包含域名， 如 “/getuser"
     */
    public $api = '';
    /**
     * @var string 接口名称
     */
    public $apiName = '';
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
    public $log;
    public function __construct(BasicConfigure $configure, BasicSignature $signature)
    {
        // 初始化配置
        $this->configure = $configure;

        $this->log = new Logger($this->configure->log, $this->configure->debug, $this->configure->logLevel);
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