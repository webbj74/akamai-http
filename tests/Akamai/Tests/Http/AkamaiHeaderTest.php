<?php

namespace Akamai\Tests\Http;

use \Akamai\Http\AkamaiHeader;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

class AkamaiHeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Akamai\Http\AkamaiHeader::getAkamaiTrueCacheKey
     */
    public function testCallingGetAkamaiTrueCacheKeyWithAnAkamaiHostReturnsACacheKeyString()
    {
        $trueCacheKey = '/L/localhost/';
        $mock = new MockPlugin(array(new Response(200, array('X-True-Cache-Key' => $trueCacheKey))));
        $result = AkamaiHeader::getAkamaiTrueCacheKey('http://localhost', array('plugins' => array($mock)));
        $requests = $mock->getReceivedRequests();
        $this->assertCount(1, $requests);
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('Akamai-X-Get-True-Cache-Key', (string) $requests[0]->getHeader('Pragma'));
        $this->assertEquals($trueCacheKey, $result);
    }

    /**
     * @covers \Akamai\Http\AkamaiHeader::getAkamaiTrueCacheKey
     */
    public function testCallingGetAkamaiTrueCacheKeyWithANonAkamaiHostReturnsAnEmptyString()
    {
        $mock = new MockPlugin(array(new Response(200)));
        $result = AkamaiHeader::getAkamaiTrueCacheKey('http://localhost', array('plugins' => array($mock)));
        $requests = $mock->getReceivedRequests();
        $this->assertCount(1, $requests);
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('Akamai-X-Get-True-Cache-Key', (string) $requests[0]->getHeader('Pragma'));
        $this->assertEquals('', $result);
    }

    /**
     * @covers \Akamai\Http\AkamaiHeader::getAkamaiTrueCacheKey
     * @expectedException \Guzzle\Http\Exception\CurlException
     * @expectedExceptionMessage [curl] 6: Could not resolve host: localhost [url] http://localhost
     */
    public function testCallingGetAkamaiTrueCacheKeyWithANonexistentDomainThrowsAnException()
    {
        $exception = "[curl] 6: Could not resolve host: localhost [url] http://localhost";
        $mock = new MockPlugin(array(new CurlException($exception)));
        AkamaiHeader::getAkamaiTrueCacheKey('http://localhost', array('plugins' => array($mock)));
    }

    /**
     * @covers \Akamai\Http\AkamaiHeader::getAkamaiHeader
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Akamai\Http\AkamaiHeader::getAkamaiHeader: Expected $url to be a string.
     */
    public function testCallingGetAkamaiHeaderWithAnInvalidUrlThrowsAnException()
    {
        AkamaiHeader::getAkamaiHeader(null, 'True-Cache-key');
    }

    /**
     * @covers \Akamai\Http\AkamaiHeader::getAkamaiHeader
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Akamai\Http\AkamaiHeader::getAkamaiHeader: Expected $parameter to be a string.
     */
    public function testCallingGetAkamaiHeaderWithAnInvalidParameterThrowsAnException()
    {
        AkamaiHeader::getAkamaiHeader('http://localhost', null);
    }

    /**
     * @covers \Akamai\Http\AkamaiHeader::getAkamaiHeader
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Akamai\Http\AkamaiHeader::getAkamaiHeader: Expected $options to be an array.
     */
    public function testCallingGetAkamaiHeaderWithInvalidOptionsThrowsAnException()
    {
        AkamaiHeader::getAkamaiHeader('http://localhost', 'True-Cache-Key', null);
    }

    /**
     * @covers \Akamai\Http\AkamaiHeader::getAkamaiHeader
     */
    public function testCallingGetAkamaiHeaderWithValidParmeters()
    {
        $trueCacheKey = '/L/localhost/';
        $mock = new MockPlugin(array(new Response(200, array('X-True-Cache-Key' => $trueCacheKey))));
        $result = AkamaiHeader::getAkamaiHeader('http://localhost', 'True-Cache-Key', array('plugins' => array($mock)));
        $requests = $mock->getReceivedRequests();
        $this->assertCount(1, $requests);
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('Akamai-X-Get-True-Cache-Key', (string) $requests[0]->getHeader('Pragma'));
        $this->assertEquals($trueCacheKey, $result);
    }
}