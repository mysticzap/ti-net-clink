<?php


namespace mysticzap\tinetclink\tnet\v2;


use mysticzap\tinetclink\BasicSignature;

class Signature extends BasicSignature
{
    /**
     * 对数据进行签名
     * @return string
     */
    public function signature($method, $api, $data){
        $api = 0 === stripos($api, 'https://') ? substr($api, 8):$api;
        $sSigntrueData = $method . $api .'?'. $data;
//        java: BasicSignature = URLEncode(base64(hmac-sha1(AccessKeySecret, urlParam)))
        $this->logger->debug("签名参数数据：" , [$sSigntrueData]);
        $result = urlencode(base64_encode(hash_hmac('sha1', $sSigntrueData, $this->configure->accessKeySecret, true)));
        $this->logger->debug("签名：", [$result]);
        return $result;
    }

}