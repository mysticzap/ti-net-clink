<?php


namespace mysticzap\tinetclink\tnet\v2;


use mysticzap\tinetclink\Exception;
use Throwable;

/**
 * 接口请求异常类
 * @package mysticzap\tinetclink\tnet\v2
 */
class ApiHttpException extends Exception
{private $statusCode = null;

    /**
     * ApiHttpException constructor.
     * @param int $code 错误码
     * @param array $params 组装消息参数
     * @param null $message 指定消息， 当指定消息内容时，组装消息无效
     * @param int $statusCode http状态码
     * @param Throwable|null $previous
     */
    public function __construct($code = 0, $params = [], $message = null, $statusCode = 400, Throwable $previous = null)
    {
        $this->statusCode = $statusCode;
        $message = !empty($message) ? $message : ErrorCode::getMessage($code,$params);
        parent::__construct($message, $code, $previous);
    }
    /**
     * http状态码
     */
    public function getStatusCode(){
        return $this->statusCode;
    }
}