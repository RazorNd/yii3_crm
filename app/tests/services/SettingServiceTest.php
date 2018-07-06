<?php

namespace app\tests\services;

use app\repositories\SettingsRepository;
use app\services\SettingService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use yii\caching\Cache;
use yii\caching\CacheInterface;

class SettingServiceTest extends TestCase
{
    const KEY = 'test-case';

    public function testGet()
    {
        /** @var CacheInterface|MockObject $cache */
        $cache = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'set'])
            ->getMock();
        /** @var SettingsRepository|MockObject $repository */
        $repository = $this->createMock(SettingsRepository::class);

        $service = new SettingService($repository, $cache);

        $cache->expects($this->once())
            ->method('get');


        $expected = 42;

        $repository->expects($this->once())
            ->method('fetchSetting')
            ->with(self::KEY)
            ->willReturn($expected);

        static::assertSame($expected, $service->get(self::KEY));
        static::assertSame($expected, $service->get(self::KEY));
        static::assertSame($expected, $service->get(self::KEY));
        static::assertSame($expected, $service->get(self::KEY));
    }

    public function testGet_Cached()
    {
        /** @var CacheInterface|MockObject $cache */
        $cache = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        /** @var SettingsRepository|MockObject $repository */
        $repository = $this->createMock(SettingsRepository::class);

        $expected = 'foo.bar.baz';

        $cache->expects($this->once())
            ->method('get')
            ->willReturn($expected);

        $repository->expects($this->never())
            ->method('fetchSetting');


        $service = new SettingService($repository, $cache);

        static::assertSame($expected, $service->get(self::KEY));
        static::assertSame($expected, $service->get(self::KEY));
        static::assertSame($expected, $service->get(self::KEY));
    }


    public function testSet()
    {
        /** @var CacheInterface|MockObject $cache */
        $cache = $this->createMock(Cache::class);

        /** @var SettingsRepository|MockObject $repository */
        $repository = $this->createMock(SettingsRepository::class);

        $service = new SettingService($repository, $cache);

        $expected = [
            'foo' => 'bar',
            'baz' => 42
        ];

        $cache->method('set')
            ->with(self::KEY, $expected);

        $repository->expects($this->once())
            ->method('saveSetting')
            ->with(self::KEY, $expected);

        $service->set(self::KEY, $expected);
    }
}
