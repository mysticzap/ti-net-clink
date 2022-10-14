<?php


namespace mysticzap\tinetclink;


use Psr\Log\AbstractLogger;

/**
 * 日志类
 * @package mysticzap\tinetclink
 */
class Logger extends AbstractLogger
{
    const EMERGENCY_NUMBER = 128;
    const ALERT_NUMBER     = 64;
    const CRITICAL_NUMBER  = 32;
    const ERROR_NUMBER    = 16;
    const WARNING_NUMBER  = 8;
    const NOTICE_NUMBER    = 4;
    const INFO_NUMBER      = 2;
    const DEBUG_NUMBER    = 1;

    const LEVELS = [
        'emergency' => self::EMERGENCY_NUMBER,
        'alert' => self::ALERT_NUMBER,
        'critical' => self::CRITICAL_NUMBER,
        'error' => self::ERROR_NUMBER,
        'warning' => self::WARNING_NUMBER,
        'notice' => self::NOTICE_NUMBER,
        'info' => self::INFO_NUMBER,
        'debug' => self::DEBUG_NUMBER,
    ];

    public $logFile = '';
    /**
     * @var
     */
    public $logLevel = 1;
    /**
     * Logger constructor.
     */
    public function __construct($logFile, $logLevel = 1)
    {
        $this->logFile = $logFile;
        $this->logLevel = $logLevel;
    }

    /**
     * @var array
     */
    public $records = [];

    public $recordsByLevel = [];

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
    {
        $record = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];
        $iLevel = !empty(self::LEVELS[$level]) ? self::LEVELS[$level] : 0;
        if($this->logLevel & $iLevel){
            if(empty($this->logFile)){
                $dotPosition = strripos($this->logFile, '.');
                $logFile = $dotPosition !== false ? substr($this->logFile, 0, $dotPosition) . date("Y-m-d") . substr($this->logFile, $dotPosition) : $this->logFile;
                error_log(json_encode($record, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), 3, $logFile);
            } else {
                $this->recordsByLevel[$record['level']][] = $record;
                $this->records[] = $record;
            }
        }
    }

    public function hasRecords($level)
    {
        return isset($this->recordsByLevel[$level]);
    }

    public function hasRecord($record, $level)
    {
        if (is_string($record)) {
            $record = ['message' => $record];
        }
        return $this->hasRecordThatPasses(function ($rec) use ($record) {
            if ($rec['message'] !== $record['message']) {
                return false;
            }
            if (isset($record['context']) && $rec['context'] !== $record['context']) {
                return false;
            }
            return true;
        }, $level);
    }

    public function hasRecordThatContains($message, $level)
    {
        return $this->hasRecordThatPasses(function ($rec) use ($message) {
            return strpos($rec['message'], $message) !== false;
        }, $level);
    }

    public function hasRecordThatMatches($regex, $level)
    {
        return $this->hasRecordThatPasses(function ($rec) use ($regex) {
            return preg_match($regex, $rec['message']) > 0;
        }, $level);
    }

    public function hasRecordThatPasses(callable $predicate, $level)
    {
        if (!isset($this->recordsByLevel[$level])) {
            return false;
        }
        foreach ($this->recordsByLevel[$level] as $i => $rec) {
            if (call_user_func($predicate, $rec, $i)) {
                return true;
            }
        }
        return false;
    }

    public function __call($method, $args)
    {
        if (preg_match('/(.*)(Debug|Info|Notice|Warning|Error|Critical|Alert|Emergency)(.*)/', $method, $matches) > 0) {
            $genericMethod = $matches[1] . ('Records' !== $matches[3] ? 'Record' : '') . $matches[3];
            $level = strtolower($matches[2]);
            if (method_exists($this, $genericMethod)) {
                $args[] = $level;
                return call_user_func_array([$this, $genericMethod], $args);
            }
        }
        throw new \BadMethodCallException('Call to undefined method ' . get_class($this) . '::' . $method . '()');
    }

    public function reset()
    {
        $this->records = [];
        $this->recordsByLevel = [];
    }
}