<?php

// if installed with composer

use flight\net\Router;
use Latte\Engine;

require __DIR__. '/../vendor/autoload.php';
// or if installed manually by zip file
// require 'flight/Flight.php';

$Latte = new Engine;
$Latte->setTempDirectory(__DIR__ . '/../temp');
Flight::map('render', function(string $template_path, array $data = []) use ($Latte) {
  $Latte->render(__DIR__ . '/../views/' . $template_path, $data);
});

$Curl = new Zebra_cURL();
$Curl->cache(__DIR__ . '/../temp');
Flight::set('Curl', $Curl);

Flight::route('/', function() {
  echo 'hello world!';
});

Flight::group('/pokemon', function(Router $router) {
	$router->get('/', function() {
		$types_response = json_decode(Flight::get('Curl')->scrap('https://pokeapi.co/api/v2/type/', true));
		$results = [];
		while($types_response->next) {
		$results = array_merge($results, $types_response->results);
		$types_response = json_decode(Flight::get('Curl')->scrap($types_response->next, true));
		}
		$results = array_merge($results, $types_response->results);
		Flight::render('home.latte', [ 'types' => $results ]);
	});

	$router->get('/type/@type', function(string $type) {
		$Curl = Flight::get('Curl');
		$type_response = json_decode($Curl->scrap('https://pokeapi.co/api/v2/type/' . $type, true));
		$pokemon_urls = [];
		foreach($type_response->pokemon as $pokemon_data) {
			$pokemon_urls[] = $pokemon_data->pokemon->url;
		}
		$pokemon_data = [];
		$Curl->get($pokemon_urls, function(stdClass $result) use (&$pokemon_data) {
			$pokemon_data[] = json_decode($result->body);
		});

		Flight::render('type.latte', [ 
			'type' => $type_response->name,
			'pokemons' => $pokemon_data
		]);
	});

	$router->get('/@id', function(int $id) {
		$pokemon_data = json_decode(Flight::get('Curl')->scrap('https://pokeapi.co/api/v2/pokemon/' . $id, true));
		Flight::render('pokemon.latte', [ 'pokemon' => $pokemon_data ]);
	});
});

Flight::start();