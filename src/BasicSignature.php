<?php


namespace mysticzap\tinetclink;

/**
 * 签名实现类
 * @package mysticzap\tinetclink
 */
abstract class BasicSignature implements ISignature
{

    /**
     * 请求前数据签名排序
     * @return mixed
     */
    public function ksort($requestParams){
        if(!is_array($requestParams)){
            return urldecode($requestParams);
        }
        foreach($requestParams as $key => $value){
            if(is_array($value)){
                $requestParams[$key] = $this->ksort($value);
            }else {
                $requestParams[$key] = urlencode($value);
            }
        }
        ksort($requestParams);
        return $requestParams;
    }

    /**
     * 参数排序后组装成字符串数据
     * 支持多层级数组转换
     * @param $requestParams
     * @param string $prefix
     * @return string
     *
     * @example：
     * $requestParams = [
     * "a" => 'a1',
     * "b" => [
     *      "ba" => "ba1",
     *      "bb" => "bb1",
     *      "bc" => [
     *          "bc1","bc2"
     *      ]
     *  ],
     * "c" => 'c1',
     * ];
     * result: a=a1&b[ba]=ba1&b[bb]=bb1&b[bc][0]=bc1&b[bc][1]=bc2&c=c1
     */
    public function a2sAfterKsort($requestParams, $prefix = ''){
        if(!is_array($requestParams)){
            return $requestParams;
        }
        $aData = [];
        foreach($requestParams as $key => $value){
            $newKey = !empty($prefix) ? ("{$prefix}[{$key}]") : $key;
            if(is_array($value)){
                $aData[] = $this->a2sAfterKsort($value, $newKey);
            }else {
                $aData[] = $newKey . "=" . urlencode($value);
            }
        }
        return implode("&", $aData);
    }
}