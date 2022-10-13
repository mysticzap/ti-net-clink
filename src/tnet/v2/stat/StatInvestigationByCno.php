<?php


namespace mysticzap\tinetclink\tnet\v2\stat;


use mysticzap\tinetclink\tnet\v2\Api;

/**
 * 满意度报表-按座席统计
 * @link [third api doc]https://develop.clink.cn/develop/api/cc.html#_%E6%BB%A1%E6%84%8F%E5%BA%A6%E6%8A%A5%E8%A1%A8_%E6%8C%89%E5%BA%A7%E5%B8%AD%E7%BB%9F%E8%AE%A1
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
    public $api = '/stat_investigation_by_cno';
    public $method = self::METHOD_POST;
}