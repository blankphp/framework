<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Response;

use BlankPhp\Response\Traits\ResponseType;

class Response
{
    use ResponseType;

    /**
     * @var string
     */
    protected $result = '';

    /**
     * @var array
     */
    protected $headerStack = [];

    public function __construct($result)
    {
        $result = is_array($result) ? json_encode($result) : $result;
        $this->result = (string) $result;
        if ($this->isJson($this->result)) {
            $this->setType(self::$header['json']);
        } else {
            $this->setType(self::$header['html']);
        }
    }

    public function getHeaderStack(): array
    {
        return $this->headerStack;
    }

    /**
     * @param array $headerStack
     * @param null  $key
     */
    public function setHeaderStack($headerStack, $key = null): void
    {
        if (empty($key)) {
            $this->headerStack[] = $headerStack;
        } else {
            $this->headerStack[$key] = $headerStack;
        }
    }

    public function setHeader(): void
    {
        foreach ($this->getHeaderStack() as $item) {
            header($item);
        }
    }

    public function header($item): Response
    {
        if (is_numeric($item)) {
            header(self::$httpStatus[$item]);
        } elseif (in_array($item, self::$header, true)) {
            header($item);
        }

        return $this;
    }

    public function setContent($value): void
    {
        if (null === $value) {
            return;
        }
        if (is_array($value)) {
            $this->result = json_encode($value);

            return;
        }
        $this->result = (string) $value;
    }

    public function send(): void
    {
        $this->setHeader();
        ob_start();
        echo $this->result;
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        ob_end_flush();
    }

    public function prepare(): Response
    {
        return $this;
    }

    public function isJson($string): bool
    {
        return 0 === strpos($string, '{');
    }

    public function returnSend(): string
    {
        return $this->result;
    }
}
