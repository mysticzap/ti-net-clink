<?php


namespace mysticzap\tinetclink\tnet\v2\stat;


use mysticzap\tinetclink\tnet\v2\Api;

/**
 * 8.2. 座席工作量报表
 * @link [third api doc]https://develop.clink.cn/develop/api/cc.html#_座席工作量报表
 * @package mysticzap\tinetclink\tnet\v2\stat
 */
class StatClientWorkload extends Api
{
    /**
     * @var array 请求包含的参数，以及是否必填
     */
    public $requestParamsRequired = [
        // 同步日期，时间格式(yyyyMMdd)
        "date" => true,
        // 指定需要显示的字段 (默认全部，传入statKey可返回按键值)
        "fields" => false,
        // 统计方式 (默认为2)
        //取值范围为[2,3]; 2:汇总统计;3:分时统计
        //注：分时统计下只会返回存在座席工作情况的数据，若座席在该时段没有工作或进行登录，则不会返回该时段的数据
        "statisticMethod" => false,
    ];

    public $apiName = "座席工作量报表";
    public $api = '/cc/stat_client_workload';
    public $method = self::METHOD_POST;
}