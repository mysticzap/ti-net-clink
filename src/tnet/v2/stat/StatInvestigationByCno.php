<?php


namespace mysticzap\tinetclink\tnet\v2\stat;


use mysticzap\tinetclink\tnet\v2\Api;

/**
 * 8.9. 满意度报表-按座席统计
 * @link [third api doc]https://develop.clink.cn/develop/api/cc.html#_满意度报表-按座席统计
 * @package mysticzap\tinetclink\tnet\v2\stat
 */
class StatInvestigationByCno extends Api
{

    /**
     * @var array 请求包含的参数，以及是否必填
     */
    public $requestParamsRequired = [
        // 同步日期，时间格式(yyyyMMdd)
        "date" => true,
        // 指定需要显示的字段 (默认全部，传入statKey可返回按键值)
        "fields" => false,
        // 呼叫类型 (呼入: ib,呼出: ob)
        "callType" => false,
    ];

    public $apiName = "满意度报表-按座席统计";
    public $api = '/cc/stat_investigation_by_cno';
    public $method = self::METHOD_POST;
}