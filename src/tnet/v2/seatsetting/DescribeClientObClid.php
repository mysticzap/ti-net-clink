<?php


namespace mysticzap\tinetclink\tnet\v2\seatsetting;


use mysticzap\tinetclink\tnet\v2\Api;

/**
 * 3.1.6. 查看座席外显号码
 * @link [第三方文档地址](https://develop.clink.cn/develop/api/cc.html#_查看座席外显号码)
 * @package mysticzap\tinetclink\tnet\v2\seatsetting
 */
class DescribeClientObClid extends Api
{
    /**
     * @var array 请求包含的参数，以及是否必填
     */
    public $requestParamsRequired = [
        //座席号
        "cno" => true,
    ];

    public $apiName = "查看座席外显号码";
    public $api = '/cc/describe_client_ob_clid';
    public $method = self::METHOD_GET;
}