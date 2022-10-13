<?php


namespace mysticzap\tinetclink;


interface IApi
{
    /**
     * 发送请求
     * @param $params request parameter
     * @param array $headers  request header
     * @return mixed
     */
    public function send($params, $headers = []);

    /**
     * 格式化返回数据
     * @param mixed $responseData 返回的数据
     * @return mixed
     */
    public function formatResponse($responseData);
    public function signature($uri, $params);
    /**
     * 发送get请求
     * @param $uri 接口地址
     * @param $params 参数
     * @param array $headers 请求头
     * @return mixed 返回数据
     */
    public function get($uri, $params, $headers = []);

    /**
     * 发送post请求
     * post 请求默认json数据发送
     * @param $uri 接口地址
     * @param $postParams post参数
     * @param $queryParams url上的参数
     * @param array $headers 请求头
     * @return mixed 返回数据
     */
    public function post($uri, $postParams, $queryParams, $headers = [], $postParamType = 'json');

    /**
     * 发送请求
     *
     * @param $uri 接口地址
     * @param $options 请求options参数， 参考guzzle/Client的options属性
     * @param array $header 请求头
     * @param string $method 请求方式
     * @return mixed 返回数据
     */
    public function request($uri, $options, $method = 'GET');
}