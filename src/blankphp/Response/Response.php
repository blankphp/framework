<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Response;

use BlankPhp\Response\Traits\Type;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    use Type;

    /**
     * @var string
     */
    protected $result = '';

    /**
     * @var array
     */
    protected $headerStack = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $removeHeaderStack = [];

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var string
     */
    protected $reasonPhrase = '';

    /**
     * @var string
     */
    protected $protocolVersion = '';

    public function __construct($result)
    {
        $result = is_array($result) ? json_encode($result) : $result;
        $this->result = (string) $result;
        // type设置
    }

    public function getHeaderStack(): array
    {
        return $this->headerStack;
    }

    /**
     * @param null $key
     */
    public function setHeaderStack(array $headerStack, $key = null): void
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
            $this->header($item);
        }
    }

    /**
     * @param $item
     * @param $value
     *
     * @return $this
     */
    public function header($item, $value = null)
    {
        if (!empty($value)) {
            // 是否为header格式?
            header($this->parseHeaderLine($item, $value));
        } else {
            header($this->getHeaderLine($item));
        }

        return $this;
    }

    /**
     * @param $value
     */
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

    /**
     * @return $this
     */
    public function prepare(): Response
    {
        return $this;
    }

    /**
     * @param $string
     */
    public function isJson($string): bool
    {
        return 0 === strpos($string, '{');
    }

    public function returnSend(): string
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @param $version
     *
     * @return $this|Response
     */
    public function withProtocolVersion($version)
    {
        $this->protocolVersion = $version;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name)
    {
        return isset($this->headers[$name]);
    }

    public function getHeader($name)
    {
        return $this->headers[$name];
    }

    public function getHeaderLine($name): string
    {
        return $this->parseHeaderLine($name, $this->getHeader($name));
    }

    private function parseHeaderLine($key, $value): string
    {
        return sprintf('%s: %s', $key, $value);
    }

    public function withHeader($name, $value)
    {
        return $this->header($name, $value);
    }

    public function withAddedHeader($name, $value)
    {
        $this->headerStack[] = [$name, $value];

        return $this;
    }

    public function withoutHeader($name)
    {
        $this->removeHeaderStack[] = $name;

        return $this;
    }

    public function getBody(): string
    {
        return $this->result;
    }

    public function withBody(StreamInterface $body)
    {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    protected function setStatusCode($code, $reasonPhrase = '')
    {
        $this->statusCode = $code;
        $this->reasonPhrase = $reasonPhrase;

        return $this;
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        return $this->setStatusCode($code, $reasonPhrase);
    }

    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }
}
