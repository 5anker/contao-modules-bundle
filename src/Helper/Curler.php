<?php

namespace Anker\ModulesBundle\Helper;

use GuzzleHttp\Client;

class Curler
{
	private $client;

	public function __construct($token)
	{
		$this->client = new Client([
			'base_uri' => 'https://connect.5-anker.com/rest/',
			'http_errors' => false,
			'headers' => [
				'Authorization' => 'Bearer ' . $token,
				'Accept' => 'application/json'
			],
			'verify' => false
		]);
	}

	public function get($uri, $data = [])
	{
		$res = $this->client->request('GET', $uri, [
			'query' => $data
		]);

		$data = json_decode($res->getBody()->getContents());

		return $data;
	}

	public function post($uri, $data = [])
	{
		$res = $this->client->request('POST', $uri, [
			'json' => $data
		]);

		$data = json_decode($res->getBody()->getContents());

		return $data;
	}

	public function put($uri, $data = [])
	{
		$res = $this->client->request('PUT', $uri, [
			'json' => $data
		]);

		$data = json_decode($res->getBody()->getContents());

		return $data;
	}

	public function delete($uri)
	{
		$res = $this->client->request('DELETE', $uri);

		$data = json_decode($res->getBody()->getContents());

		return $data;
	}
}
