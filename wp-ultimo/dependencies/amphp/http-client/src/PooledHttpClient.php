<?php

namespace WP_Ultimo\Dependencies\Amp\Http\Client;

use WP_Ultimo\Dependencies\Amp\CancellationToken;
use WP_Ultimo\Dependencies\Amp\Http\Client\Connection\ConnectionPool;
use WP_Ultimo\Dependencies\Amp\Http\Client\Connection\InterceptedStream;
use WP_Ultimo\Dependencies\Amp\Http\Client\Connection\Stream;
use WP_Ultimo\Dependencies\Amp\Http\Client\Connection\UnlimitedConnectionPool;
use WP_Ultimo\Dependencies\Amp\Http\Client\Internal\ForbidCloning;
use WP_Ultimo\Dependencies\Amp\Http\Client\Internal\ForbidSerialization;
use WP_Ultimo\Dependencies\Amp\Promise;
use function WP_Ultimo\Dependencies\Amp\call;
final class PooledHttpClient implements DelegateHttpClient
{
    use ForbidCloning;
    use ForbidSerialization;
    /** @var ConnectionPool */
    private $connectionPool;
    /** @var NetworkInterceptor[] */
    private $networkInterceptors = [];
    public function __construct(?ConnectionPool $connectionPool = null)
    {
        $this->connectionPool = $connectionPool ?? new UnlimitedConnectionPool();
    }
    public function request(Request $request, CancellationToken $cancellation) : Promise
    {
        return call(function () use($request, $cancellation) {
            foreach ($request->getEventListeners() as $eventListener) {
                (yield $eventListener->startRequest($request));
            }
            $stream = (yield $this->connectionPool->getStream($request, $cancellation));
            \assert($stream instanceof Stream);
            foreach (\array_reverse($this->networkInterceptors) as $interceptor) {
                $stream = new InterceptedStream($stream, $interceptor);
            }
            return (yield $stream->request($request, $cancellation));
        });
    }
    /**
     * Adds a network interceptor.
     *
     * Network interceptors are only invoked if the request requires network access, i.e. there's no short-circuit by
     * an application interceptor, e.g. a cache.
     *
     * Whether the given network interceptor will be respected for currently running requests is undefined.
     *
     * Any new requests have to take the new interceptor into account.
     *
     * @param NetworkInterceptor $networkInterceptor
     *
     * @return self
     */
    public function intercept(NetworkInterceptor $networkInterceptor) : self
    {
        $clone = clone $this;
        $clone->networkInterceptors[] = $networkInterceptor;
        return $clone;
    }
}
