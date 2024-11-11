<?php

declare(strict_types=1);
/**
 * This file is part of OpenSwoole IDE Helper.
 * @link     https://openswoole.com
 * @contact  hello@openswoole.com
 * @license  https://github.com/openswoole/library/blob/master/LICENSE
 */
namespace Swoole\Database;

class RedisConfig
{
    protected string $host = '127.0.0.1';

    protected int $port = 6379;

    protected float $timeout = 0.0;

    protected string $reserved = '';

    protected int $retry_interval = 0;

    protected float $read_timeout = 0.0;

    protected string $auth = '';

    protected int $dbIndex = 0;

    public function getHost(): string
    {
        return $this->host;
    }

    public function withHost($host): self
    {
        $this->host = $host;
        return $this;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function withPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    public function getTimeout(): float
    {
        return $this->timeout;
    }

    public function withTimeout(float $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function getReserved(): string
    {
        return $this->reserved;
    }

    public function withReserved(string $reserved): self
    {
        $this->reserved = $reserved;
        return $this;
    }

    public function getRetryInterval(): int
    {
        return $this->retry_interval;
    }

    public function withRetryInterval(int $retry_interval): self
    {
        $this->retry_interval = $retry_interval;
        return $this;
    }

    public function getReadTimeout(): float
    {
        return $this->read_timeout;
    }

    public function withReadTimeout(float $read_timeout): self
    {
        $this->read_timeout = $read_timeout;
        return $this;
    }

    public function getAuth(): string
    {
        return $this->auth;
    }

    public function withAuth(string $auth): self
    {
        $this->auth = $auth;
        return $this;
    }

    public function getDbIndex(): int
    {
        return $this->dbIndex;
    }

    public function withDbIndex(int $dbIndex): self
    {
        $this->dbIndex = $dbIndex;
        return $this;
    }
}
