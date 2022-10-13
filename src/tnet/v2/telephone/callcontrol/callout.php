<?php


namespace Mysticzap\TiNetClink\tnet\v2\telephone\callcontrol;


use mysticzap\tinetclink\tnet\v2\Api;

/**
 * 电话/呼叫控制/外呼 接口
 * @package mysticzap\tinetclink\tnet\v2\telephone\callcontrol
 *
 */
class callout extends Api
{
    public $apiName = "外呼";
    public $api = '/callout';
    public $method = self::METHOD_POST;

    public function send($params){
        $data  = $this->post($this->uri, $params);
        return $this->formatResponse($data);
    }

    /**
     * 返回数据格式化
     * 针对具体类实现时，需要重构成符合自己系统接口的数据格式
     * @param mixed $responseData
     * @return mixed|void
     */
    public function formatResponse($responseData){

    }
}