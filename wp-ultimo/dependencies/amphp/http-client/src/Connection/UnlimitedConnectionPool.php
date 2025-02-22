<?php

namespace WP_Ultimo\Dependencies\Amp\Http\Client\Connection;

use WP_Ultimo\Dependencies\Amp\CancellationToken;
use WP_Ultimo\Dependencies\Amp\Http\Client\Internal\ForbidSerialization;
use WP_Ultimo\Dependencies\Amp\Http\Client\Request;
use WP_Ultimo\Dependencies\Amp\Promise;
final class UnlimitedConnectionPool implements ConnectionPool
{
    use ForbidSerialization;
    /** @var ConnectionLimitingPool */
    private $pool;
    public function __construct(?ConnectionFactory $connectionFactory = null)
    {
        $this->pool = ConnectionLimitingPool::byAuthority(\PHP_INT_MAX, $connectionFactory);
    }
    public function __clone()
    {
        $this->pool = clone $this->pool;
    }
    public function getTotalConnectionAttempts() : int
    {
        return $this->pool->getTotalConnectionAttempts();
    }
    public function getTotalStreamRequests() : int
    {
        return $this->pool->getTotalStreamRequests();
    }
    public function getOpenConnectionCount() : int
    {
        return $this->pool->getOpenConnectionCount();
    }
    public function getStream(Request $request, CancellationToken $cancellation) : Promise
    {
        return $this->pool->getStream($request, $cancellation);
    }
}
