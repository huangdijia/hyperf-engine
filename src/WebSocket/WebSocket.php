<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\Engine\WebSocket;

use Hyperf\Engine\Contract\WebSocket\WebSocketInterface;
use Swoole\Http\Response;
use Swoole\WebSocket\CloseFrame;

class WebSocket implements WebSocketInterface
{
    /**
     * @var Response
     */
    protected $connection;

    /**
     * @var array<string, callable>
     */
    protected $events = [];

    public function __construct(Response $connection)
    {
        $this->connection = $connection;
    }

    public function on(string $event, callable $callback): void
    {
        $this->events[$event] = $callback;
    }

    public function start(): void
    {
        $this->connection->upgrade();

        while (true) {
            $frame = $this->connection->recv();
            if ($frame === false || $frame instanceof CloseFrame || $frame === '') {
                $callback = $this->events[static::ON_CLOSE];
                $callback($this->connection, $this->connection->fd);
                break;
            }

            $callback = $this->events[static::ON_MESSAGE];
            $callback($this->connection, $frame);
        }

        $this->connection = null;
        $this->events = [];
    }
}
