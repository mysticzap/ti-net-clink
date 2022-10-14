<?php


namespace mysticzap\tinetclink\tnet\v2\seatsetting;


use mysticzap\tinetclink\tnet\v2\Api;

/**
 * 3.1.6. 查看座席外显号码
 * @link [第三方文档地址](https://develop.clink.cn/develop/api/cc.html#%E6%9F%A5%E7%9C%8B%E5%BA%A7%E5%B8%AD%E5%A4%96%E6%98%BE%E5%8F%B7%E7%A0%81)
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
    public $api = '/describe_client_ob_clid';
    public $method = self::METHOD_GET;
}