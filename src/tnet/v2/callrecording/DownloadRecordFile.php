<?php


namespace mysticzap\tinetclink\tnet\v2\callrecording;


use mysticzap\tinetclink\tnet\v2\Api;

/**
 * 6.3.1. 下载通话录音文件
 * @package mysticzap\tinetclink\tnet\v2\callrecording
 */
class DownloadRecordFile extends Api
{
    /**
     * @var array 请求包含的参数，以及是否必填
     */
    public $requestParamsRequired = [
        // 通话记录唯一标识
        "mainUniqueId" => true,
        // 不传递获取mp3格式录音，传递时获取wav格式录音。1：双轨录音客户侧，2：双轨录音座席侧，3：两侧合成录音
        "recordSide" => false,
    ];

    public $apiName = "下载通话录音文件";
    public $api = '/download_record_file';
    public $method = self::METHOD_GET;

}