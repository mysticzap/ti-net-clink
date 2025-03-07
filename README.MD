# 天润融通接口2.0版本sdk
### 使用composer加载
```shell
composer require mysticzap/ti-net-clink
```
#### 注意仓库源
```json
{
  "repositories": {
    "packagist": {
      "type": "composer",
      "url": "https://repo.packagist.org"
    }
  }
}
```

### 第三方接口调用的配置格式
```PHP
$config = [
    "baseUri" => '', // 接口域名
    "accessKeyId" => '',// key_id,
    "accessKeySecret" => '', // key_secret,
    "log" => '', // 日志地址
    "logFileNameFormat" => '',// 日志文件名格式化，日期格式：Ymd
    "logLevel" => 0, // 打印日志等级
    "expires" => 600, //接口签名中额过期时长，单位秒，最小1秒，最大1天=86400
]
$configure = new Configure($config);// 配置类
$logger = new Logger($configure->log, $configure->logLevel, $configure->logFileNameFormat);
$signature = new Signature($configure, $logger); // 签名类
// 统计接口
$tinetApi = new StatInvestigationByCno($configure, $signature, $logger);
$response = $tinetApi->send($params);
```
