<?php


namespace mysticzap\tinetclink\tnet\v2;

/**
 * Class ErrorCode
 * @package mysticzap\tinetclink\tnet\v2
 */
class ErrorCode
{
    const ERROR_API_CALL = 900001;
    const ERROR_REQUEST_PARAMETER_DEFECT = 900002;

    public static $messages = [
        self::ERROR_API_CALL => "天润接口异常，请重试",
        self::ERROR_REQUEST_PARAMETER_DEFECT => "请求参数缺失",
        self::ERROR_API_METHOD => "天润接口调用方式错误",
    ];

    /**
     * 获得错误码对应消息
     * @param $code 错误码
     * @param array $params 消息参数替换
     * @return string 消息字符串
     */
    public static function getMessage($code, $params = []){
        $key = self::$messages[self::ERROR_API_CALL];
        if(in_array($code, self::$messages)){
            $key = self::$messages[$code];
        }
        $params = is_array($params) ? $params : [$params];
        return $params ? vsprintf($key, $params) : $key;
    }

}