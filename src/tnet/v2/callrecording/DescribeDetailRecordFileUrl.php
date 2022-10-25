<?php


namespace mysticzap\tinetclink\tnet\v2\callrecording;


use mysticzap\tinetclink\tnet\v2\Api;

/**
 * 6.3.4. 查看通话详情录音地址
 * @link [third api doc]https://develop.clink.cn/develop/api/cc.html#_查看通话详情录音地址
 *
 * @package mysticzap\tinetclink\tnet\v2\callrecording
 */
class DescribeDetailRecordFileUrl extends Api
{
    /**
     * @var array 请求包含的参数，以及是否必填
     */
    public $requestParamsRequired = [
        // 通话记录唯一标识
        "mainUniqueId" => true,
        // 通话记录唯一标识
        "uniqueId" => true,
        // 不传递获取mp3格式录音，传递时获取wav格式录音。1：双轨录音客户侧，2：双轨录音座席侧，3：两侧合成录音
        "recordSide" => false,
        // 获取录音地址超时时长，单位为秒，默认为一小时，范围在一到二十四小时。
        "timeout" => false,
    ];

    public $apiName = "查看通话详情录音地址";
    public $api = '/cc/describe_detail_record_file_url';
    public $method = self::METHOD_GET;

}