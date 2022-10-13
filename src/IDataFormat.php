<?php


namespace mysticzap\tinetclink;

/**
 * 数据格式化接口
 * @package mysticzap\tinetclink
 */
interface IDataFormat
{
    /**
     * 请求数据转换定义
     * @return mixed
     */
    public function requestDataConvertDefinition();

    /**
     * 返回数据转换定义
     * @return mixed
     */
    public function responseDataConvertDefinition();
    /**
     * 请求数据本地格式化
     * @return mixed
     */
    public function requestLocalFormat();
    /**
     * 请求数据远端格式化
     * @return mixed
     */
    public function requestRemoteFormat();
    /**
     * 返回数据本地格式化
     * @return mixed
     */
    public function responseLocalFormat();
    /**
     * 返回数据远端格式化
     * @return mixed
     */
    public function responseRemoteFormat();
}