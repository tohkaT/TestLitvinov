<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
	private string $api = "";
	public function __construct(string $weatherApi, private HttpClientInterface $client) {
		$this->api = $weatherApi;
	}

	public function getWeather(string $city): array {
		if ($city == "") {
			return ['success' => false, 'error' => "no city"];
		}
		$url = sprintf("https://api.weatherapi.com/v1/current.json?key=%s&q=%s", $this->api, $city);
		try {
			$response = $this->client->request('GET', $url, ['timeout' => 30]);
		} catch (TransportExceptionInterface $e) {
			$error = 'Curl error: ' . $e->getMessage();

			return ['success' => false, 'error' => $error];
		}

		if ($response->getStatusCode() == 200) {
			$data = $response->toArray(false);

			if (isset($data['error'])) {
				return ['success' => false, 'error' => $data['error']['message']];
			}

			$result = [
				'success'      => true,
				'city'         => $data['location']['name'],
				'country'      => $data['location']['country'],
				'temperature'  => $data['current']['temp_c'],
				'condition'    => $data['current']['condition']['text'],
				'humidity'     => $data['current']['humidity'],
				'wind_speed'   => $data['current']['wind_kph'],
				'last_updated' => $data['current']['last_updated'],
			];

			$filesystem = new Filesystem();
			//можливо даті потрібен часовий пояс
			$filesystem->appendToFile('../var/log/weather_log.txt', date('Y-m-d H:i:s') . " - Погода в {$result['city']}: {$result['temperature']}°C, {$result['condition']}\n");

			return $result;
		} else {
			$error = 'Curl error: ' . $response->getContent(false);

			return ['success' => false, 'error' => $error];
		}
	}
}