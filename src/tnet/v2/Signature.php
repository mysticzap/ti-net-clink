<?php


namespace Mysticzap\TiNetClink\tnet\v2;


use Mysticzap\TiNetClink\BasicSignature;

class Signature extends BasicSignature
{
    /**
     * 对数据进行签名
     * @return string
     */
    public function signature($method, $api, $data){
        $api = 0 === stripos($api, 'https//') ? substr($api, 7):$api;
        $sSigntrueData = $method . $api .'?'. $data;
//        java: BasicSignature = URLEncode(base64(hmac-sha1(AccessKeySecret, urlParam)))
        return urlencode(base64_encode(hash_hmac('sha1', $sSigntrueData, true)));
    }

}