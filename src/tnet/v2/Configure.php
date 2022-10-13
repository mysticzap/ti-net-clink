<?php


namespace mysticzap\tinetclink\tnet\v2;


use mysticzap\tinetclink\BasicConfigure;

/**
 * 天润融通2.0接口配置
 * @property $baseUri 接口域名地址
 * @property $timeout 请求超时
 * @package mysticzap\tinetclink\tnet\v2
 */
class Configure extends BasicConfigure
{
    /**
     * @var string 天润融通2.0接口地址
     * 北京平台：https://api-bj.clink.cn
     * 上海平台：https://api-sh.clink.cn
     */
    public $baseUri = "https://api-sh.clink.cn";
    /**
     * 请求超时设置
     * @var float
     */
    public $timeout = 2.0;
    /**
     * @var string 访问key的ID
     */
    public $accessKeyId = '';
    /**
     * @var string 访问key秘钥
     */
    public $accessKeySecret = '';
    /**
     * @var int 签名有效时长, 最长可设置：86400（24小时）
     */
    public $expires = 7200;
    /**
     * @var bool 是否打开调试，记录日志
     */
    public $debug = true;
    /**
     * @var string 日志记录地址
     */
    public $log = '';
    /**
     * 日志等级
     * 可选值: emergency:128,alert:64,critical:32,error:16, warning:8,notice:4,info:2,debug:1, disabled:0
     * @var string
     */
    public $logLevel = 255;

}