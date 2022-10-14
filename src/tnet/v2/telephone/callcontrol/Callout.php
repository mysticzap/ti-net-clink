<?php


namespace mysticzap\tinetclink\tnet\v2\telephone\callcontrol;


use mysticzap\tinetclink\tnet\v2\Api;
use mysticzap\tinetclink\tnet\v2\ApiRequestParameterException;
use mysticzap\tinetclink\tnet\v2\ErrorCode;

/**
 * 电话/呼叫控制/外呼 接口
 * @link [第三方接口文档](https://develop.clink.cn/develop/api/cc.html#_外呼)
 * @package mysticzap\tinetclink\tnet\v2\telephone\callcontrol
 *
 */
class Callout extends Api
{
    /**
     * @var array 请求包含的参数，以及是否必填
     */
    public $requestParamsRequired = [
        // 座席工号，4-11 位数字
        "cno" => true,
        // 客户电话，固话类型需要添加区号，手机类型不加 0，固话带IP话机以 “-” 分隔。 如果企业开启号码隐藏功能，可从弹屏事件中获取customerNumberKey的值，进行外呼
        "customerNumber" => true,
        // 自定义请求id,如果传了这个参数那么将返回传入值，如果未传该值，那么系统将自动生成一个
        "requestUniqueId" => false,
        // 外显方式，0：指定外显号码；1：指定外呼标识
        "type" => false,
        // 当外显方式为0时表示指定的外显号码，当外显类型为1时表示指定的外呼标识
        "clid" => false,
        // 呼叫座席侧超时时间，取值范围 5-60s，默认 30s
        "clientTimeout" => false,
        // 呼叫客户侧超时时间，取值范围 5-60s，默认 45s
        "customerTimeout" => false,
        // 用户自定义变量
        "userField" => false,
        // bindTel：临时绑定电话，绑定设备类型仅支持手机；用户可临时改变座席的绑定设备，如果此参数为空，则使用座席上线时绑定的设备进行外呼，如果传入号码，则座席外呼时使用新传递的号码作为绑定设备进行外呼。
        "bindTel" => false,
    ];

    public $apiName = "外呼";
    public $api = '/callout';
    public $method = self::METHOD_POST;

}