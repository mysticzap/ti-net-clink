<?php


namespace mysticzap\tinetclink;

/**
 * 天润融通接口调用数据签名类
 *
 * @package mysticzap\tinetclink
 */
interface ISignature
{
    /**
     * 对数据进行签名
     * @param $method
     * @param $api
     * @param string $data 排序后通过a2sAfterKsort拼装成字符串后的数据 如：a=a1&b[ba]=ba1&b[bb]=bb1&b[bc][0]=bc1&b[bc][1]=bc2&c=c1
     * @return mixed
     */
    public function signature($method, $api, $data);

    /**
     * 请求前数据签名排序
     * @return mixed
     */
    public function ksort($requestParams);

    /**
     * 数据生成uri接口字符串拼装
     * @param $requestParamsAfterKsort
     * @return mixed
     */
    public function a2sAfterKsort($requestParamsAfterKsort);
}