<?php

namespace onPHP\test\main;

use onPHP\core\Exceptions\NetworkException;
use onPHP\main\Flow\HttpRequest;
use onPHP\main\Net\Http\CurlHttpClient;
use onPHP\main\Net\Http\HttpMethod;
use onPHP\main\Net\Http\HttpStatus;
use onPHP\main\Net\HttpUrl;
use onPHP\test\misc\TestCase;

/***************************************************************************
 *   Copyright (C) 2007 by Anton E. Lebedevich                             *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
final class HttpUtilsTest extends TestCase
{
    public function testCurlGet()
    {
        $request = HttpRequest::create()->setUrl(HttpUrl::create()->parse('https://github.com/'))
                              ->setMethod(HttpMethod::get());
        try {
            $response = CurlHttpClient::create()->setTimeout(3)->send($request);
        } catch (NetworkException $e) {
            return $this->markTestSkipped('no network available');
        }
        $this->assertEquals($response->getStatus()->getId(), HttpStatus::CODE_200);
        $this->assertContains('github', $response->getBody());
        try {
            $badResponse = CurlHttpClient::create()->setTimeout(3)->setMaxFileSize(100)->send($request);
            $this->fail();
        } catch (NetworkException $e) {
        }
    }

    public function testCurlException()
    {
        $request = HttpRequest::create()->setUrl(HttpUrl::create()->parse('http://nonexistentdomain.xyz'))
                              ->setMethod(HttpMethod::get());
        try {
            $response = CurlHttpClient::create()->setTimeout(3)->send($request);
            $this->fail();
        } catch (NetworkException $e) {
            $this->assertContains('curl error', $e->getMessage());
        }
    }
}