<?php


namespace mysticzap\tinetclink\tnet\v2;


use mysticzap\tinetclink\Exception;
use Throwable;

/**
 * 接口请求异常类
 * @package mysticzap\tinetclink\tnet\v2
 */
class ApiHttpException extends Exception
{
    /**
     * ApiHttpException constructor.
     * @param int $code 错误码，
     * @param array $params 消息转换参数
     * @param Throwable|null $previous
     */
    public function __construct($code = 0, $params = [], Throwable $previous = null)
    {
        $message = ErrorCode::getMessage($code,$params);
        parent::__construct($message, $code, $previous);
    }
}