<?php


namespace mysticzap\tinetclink;

/**
 * 配置信息
 * @property $baseUri 接口域名地址
 * @property $timeout 请求超时
 * @package mysticzap\tinetclink
 */
abstract class BasicConfigure
{

    public function __construct($configs = []){
        // 初始化配置数据
        if(!empty($configs)){
            foreach($configs as $key => $value){
                if(property_exists($this, $key)){
                    $this->$key = $value;
                }
            }
        }
    }
    /**
     * @var string 接口域名地址
     * 北京平台：https://api-bj.clink.cn
     * 上海平台：https://api-sh.clink.cn
     */
    public $baseUri = "";
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
     * @var string 日志记录地址
     * @example /data/log/logfilename.log
     */
    public $log = '';
    /**
     * @var string 日志文件名生成格式， 如果为空，则直接为原始的$this->log的值，否则是date的时间格式值
     * @example 按日生成日志："Ymd" /data/log/logfilename20221012.log， 按小时：”YmdH" /data/log/logfilename2022101205.log
     */
    public $logFileNameFormat = "Ymd";
    /**
     * 日志等级
     * 可选值: emergency:128,alert:64,critical:32,error:16, warning:8,notice:4,info:2,debug:1
     * @var string
     */
    public $logLevel = 0;


}