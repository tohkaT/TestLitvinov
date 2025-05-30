<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WeatherControllerTest extends WebTestCase
{

	public function testGetWeather() {
		$client = static::createClient();

		$crawler = $client->request('GET', '/weather/bycity/London');

		$this->assertResponseIsSuccessful();
		$this->assertSelectorTextContains('h1', 'Погода');
	}
}
