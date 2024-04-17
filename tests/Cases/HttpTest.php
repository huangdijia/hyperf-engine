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

namespace HyperfTest\Cases;

use Hyperf\Engine\Http\Http;

/**
 * @internal
 * @coversNothing
 */
class HttpTest extends AbstractTestCase
{
    public function testHttpPackRequest()
    {
        $data = Http::packRequest('GET', 'https://www.baidu.com', ['Content-Type' => 'application/json'], 'Hello World');

        $this->assertSame("GET https://www.baidu.com HTTP/1.1\r
Content-Type: application/json\r
\r
Hello World", $data);
    }

    public function testHttpPackResponse()
    {
        $data = Http::packResponse(200, 'OK', ['Content-Type' => 'application/json'], 'Hello World');

        $this->assertSame("HTTP/1.1 200 OK\r
Content-Type: application/json\r
\r
Hello World", $data);
    }
}
