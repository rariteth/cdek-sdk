<?php
/*
 * This code is licensed under the MIT License.
 *
 * Copyright (c) 2018 Appwilio (http://appwilio.com), greabock (https://github.com/greabock), JhaoDa (https://github.com/jhaoda)
 * Copyright (c) 2018 Alexey Kopytko <alexey@kopytko.com> and contributors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Tests\CdekSDK;

use CdekSDK\Contracts\ShouldAuthorize;
use CdekSDK\Requests\CalculationRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CdekSDK\Requests\CalculationRequest
 * @covers \CdekSDK\Requests\CalculationAuthorizedRequest
 */
class CalculationRequestTest extends TestCase
{
    public function test_example()
    {
        $request = new CalculationRequest();

        $request->setSenderCityPostCode('295000')
            ->setReceiverCityPostCode('652632')
            ->setTariffId(1)
            ->addPackage([
                'weight' => 0.2,
                'length' => 25,
                'width' => 15,
                'height' => 10,
            ]);

        $this->assertSame([
            'version' => '1.0',
            'goods' => [
                [
                    'weight' => 0.2,
                    'length' => 25,
                    'width' => 15,
                    'height' => 10,
                ],
            ],
            'tariffId' => 1,
            'senderCityPostCode' => '295000',
            'receiverCityPostCode' => '652632',
        ], $request->getBody());
    }

    public function test_with_authorization()
    {
        $request = CalculationRequest::withAuthorization();
        $this->assertInstanceOf(ShouldAuthorize::class, $request);

        if ($request instanceof ShouldAuthorize) {
            $request->date(new \DateTimeImmutable('2018-01-01'));
            $request->credentials('foo', 'bar');
        }

        $request->setSenderCityPostCode('295000')
        ->setReceiverCityPostCode('652632')
        ->setTariffId(1)
        ->addPackage([
            'weight' => 0.2,
            'length' => 25,
            'width' => 15,
            'height' => 10,
        ]);

        $this->assertSame([
            'version' => '1.0',
            'goods' => [
                [
                    'weight' => 0.2,
                    'length' => 25,
                    'width' => 15,
                    'height' => 10,
                ],
            ],
            'tariffId' => 1,
            'senderCityPostCode' => '295000',
            'receiverCityPostCode' => '652632',
            'secure' => 'bar',
            'authLogin' => 'foo',
            'dateExecute' => '2018-01-01',
        ], $request->getBody());
    }
}