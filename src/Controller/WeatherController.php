<?php
namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WeatherController extends AbstractController
{
	#[Route('/weather/bycity', name: 'weather_city')]
	#[Route('/weather/bycity/{city}', name: 'weather_city')]
	public function getWeather(WeatherService $weather, string $city = "London"): Response
	{
		$weatherData = $weather->getWeather($city);
		return $this->render('Weather/get_weather.html.twig', [
			'weather_data' => $weatherData
		]);
	}
}