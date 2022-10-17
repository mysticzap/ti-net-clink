<?php


namespace mysticzap\tinetclink;

/**
 * 签名实现类
 * @package mysticzap\tinetclink
 */
abstract class BasicSignature implements ISignature
{

    /**
     * @var BasicConfigure
     */
    public $configure;
    /**
     * @var Logger 日志类
     */
    public $logger;
    public function __construct(BasicConfigure $configure, Logger $logger){
        $this->configure = $configure;
        $this->logger = $logger;
    }
    /**
     * 请求前数据签名排序
     * @return mixed
     */
    public function ksort($requestParams){
        if(!is_array($requestParams)){
            return $requestParams;
        }
        foreach($requestParams as $key => $value){
            if(is_array($value)){
                $requestParams[$key] = $this->ksort($value);
            }else {
                $requestParams[$key] = $value;
            }
        }
        ksort($requestParams);
        $this->logger->debug("签名参数排序后", [$requestParams]);
        return $requestParams;
    }

    /**
     * 参数排序后组装成字符串数据
     * 支持多层级数组转换
     * @param $requestParams
     * @param string $prefix
     * @return array
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
     * result: 一维数组
     */
    public function a2sBeforeKsort($requestParams, $prefix = ''){
        if(!is_array($requestParams)){
            return $requestParams;
        }
        $aData = [];
        foreach($requestParams as $key => $value){
            $newKey = !empty($prefix) ? ("{$prefix}[{$key}]") : $key;
            if(is_array($value)){
                $aData = array_merge($aData, $this->a2sBeforeKsort($value, $newKey));
            }else {
                $aData[$newKey] = $value;
            }
        }

        $this->logger->debug("参数多维转一维数组", [$aData]);
        return $aData;
    }


    /**
     * 获得生成url上的参数数据
     * @param array $paramsAfterSort
     * @return string;
     */
    public function getHttpBuildQuery($paramsAfterSort){
        $sParamsAfterSort = $paramsAfterSort;
        if(is_array($paramsAfterSort)){
            $aHttBuildQuery = [];
            foreach($paramsAfterSort as $k => $v){
                $aHttBuildQuery[] = urlencode($k) . '=' . urlencode($v);
            }
            $sParamsAfterSort = implode("&", $aHttBuildQuery);

        }
        $this->logger->debug("签名参数字符串", [$sParamsAfterSort]);
        return $sParamsAfterSort;
    }
}