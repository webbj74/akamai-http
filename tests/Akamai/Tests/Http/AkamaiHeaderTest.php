<?php

namespace Akamai\Tests\Http;

use \Akamai\Http\AkamaiHeader;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;

class AkamaiHeaderTest extends TestCase
{
    /**
     * @covers \Akamai\Http\AkamaiHeader::getAkamaiTrueCacheKey
     */
    public function testCallingGetAkamaiTrueCacheKeyWithAnAkamaiHostReturnsACacheKeyString()
    {
        $trueCacheKey = '/L/localhost/';
        $mock = new MockHandler([
            new Response(200, ['X-True-Cache-Key' => $trueCacheKey])
        ]);
        $handler = HandlerStack::create($mock);
        $result = AkamaiHeader::getAkamaiTrueCacheKey('http://localhost', ['handler' => $handler]);
        $request = $mock->getLastRequest();

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('Akamai-X-Get-True-Cache-Key', $request->getHeaderLine('Pragma'));
        $this->assertEquals($trueCacheKey, $result);
    }

    /**
     * @covers \Akamai\Http\AkamaiHeader::getAkamaiTrueCacheKey
     */
    public function testCallingGetAkamaiTrueCacheKeyWithANonAkamaiHostReturnsAnEmptyString()
    {
        $mock = new MockHandler([
            new Response(200)
        ]);
        $handler = HandlerStack::create($mock);
        $result = AkamaiHeader::getAkamaiTrueCacheKey('http://localhost', ['handler' => $handler]);
        $request = $mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('Akamai-X-Get-True-Cache-Key', $request->getHeaderLine('Pragma'));
        $this->assertEquals('', $result);
    }

    /**
     * @covers \Akamai\Http\AkamaiHeader::getAkamaiTrueCacheKey
     * @expectedException \GuzzleHttp\Exception\RequestException
     * @expectedExceptionMessage [curl] 6: Could not resolve host: localhost [url] http://localhost
     */
    public function testCallingGetAkamaiTrueCacheKeyWithANonexistentDomainThrowsAnException()
    {
        $exception = "[curl] 6: Could not resolve host: localhost [url] http://localhost";
        $mock = new MockHandler([
            new RequestException($exception, new Request('GET', 'http://localhost'))
        ]);
        $handler = HandlerStack::create($mock);
        AkamaiHeader::getAkamaiTrueCacheKey('http://localhost', ['handler' => $handler]);
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
        $mock = new MockHandler([
            new Response(200, ['X-True-Cache-Key' => $trueCacheKey])
        ]);
        $handler = HandlerStack::create($mock);
        $result = AkamaiHeader::getAkamaiHeader('http://localhost', 'True-Cache-Key', ['handler' => $handler]);
        $request = $mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('Akamai-X-Get-True-Cache-Key', $request->getHeaderLine('Pragma'));
        $this->assertEquals($trueCacheKey, $result);
    }
}