<?php

namespace App\Tests\Service;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WeatherServiceTest extends KernelTestCase
{

	public function testGetWeather() {
		self::bootKernel();
		$container = static::getContainer();
		$WeatherService = $container->get(WeatherService::class);

		$result = $WeatherService->getWeather("London");
		$this->assertTrue($result['success']);
		$this->assertEquals('London', $result['city']);
		$this->assertTrue((new \Symfony\Component\Filesystem\Filesystem())->exists('../var/log/weather_log.txt'));
		$this->assertTrue(is_writable('../var/log/weather_log.txt'));
	}
}
