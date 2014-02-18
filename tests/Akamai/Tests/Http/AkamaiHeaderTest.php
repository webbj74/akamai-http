<?php

namespace Akamai\Tests\Http;

use \Akamai\Http\AkamaiHeader;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

class AkamaiHeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Akamai\Http\AkamaiHeader::getAkamaiTrueCacheKey
     */
    public function testMethodsMakeHttpGETRequests()
    {
        $mock = new MockPlugin(array(new Response(200)));
        AkamaiHeader::getAkamaiTrueCacheKey('http://localhost', array('plugins' => array($mock)));
        $requests = $mock->getReceivedRequests();
        $this->assertCount(1, $requests);
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('Akamai-X-Get-True-Cache-Key', (string) $requests[0]->getHeader('Pragma'));
    }
}