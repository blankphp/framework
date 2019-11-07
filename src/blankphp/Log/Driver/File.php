<?php


namespace Blankphp\Log\Driver;


use Blankphp\Exception\Exception;
use Psr\Log\LoggerInterface;

class File implements LoggerInterface
{
    protected $options = [
        'dir' => APP_PATH . '/cache/log/',
        'max' => 2 * 1024 * 1024 * 8,
        'time_format' => "Y - M - D H:S:",
        'extension' => 'log',
    ];

    private $fileName = null;

    public function parseLog($type, $message, $context)
    {
        //将数组等转换未string类型
        $time = time();
        $content = "_____________【 $type 】 start time:[$time]_____________" . PHP_EOL;
        $content .= $message . PHP_EOL;
        $content .= var_export($context, true);
        $content .= "_____________          【 $type 】 stop   _____________" . PHP_EOL;
        return $content;
    }

    public function emergency($message, array $context = array())
    {
        // TODO: Implement emergency() method.
        return $this->parseLog('emergency', $message, $context);

    }

    public function alert($message, array $context = array())
    {
        // TODO: Implement alert() method.
        return $this->parseLog('alert', $message, $context);

    }

    public function critical($message, array $context = array())
    {
        // TODO: Implement critical() method.
        return $this->parseLog('critical', $message, $context);
    }

    public function error($message, array $context = array())
    {
        // TODO: Implement error() method.
        return $this->parseLog('error', $message, $context);
    }

    public function warning($message, array $context = array())
    {
        // TODO: Implement warning() method.
        return $this->parseLog('warning', $message, $context);
    }

    public function notice($message, array $context = array())
    {
        // TODO: Implement notice() method.
        return $this->parseLog('notice', $message, $context);
    }

    public function info($message, array $context = array())
    {
        return $this->parseLog('info', $message, $context);
    }

    public function debug($message, array $context = array())
    {
        // TODO: Implement debug() method.
        return $this->parseLog('debug', $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        //代理方法调用子方法
        $res = $this->{$level}($message, $context);
        $this->put($res);
    }

    public function put(string $data)
    {
        //判断目录是否存在
        if (!is_dir($this->options['dir'])) {
            mkdir($this->options['dir']);
        }
        //文件名
        if (empty($this->fileName)) {
            //数据格式
//            $this->fileName = date('Y-m-d-H i s') . '-log.' . $this->options['extension'];
            $this->fileName='day.log';
        }
        //写入文件
        if (is_dir($this->options['dir'])) {
            $file = fopen($this->options['dir'] . $this->fileName, 'a');
            fwrite($file, $data);
            fclose($file);
        }


    }
}