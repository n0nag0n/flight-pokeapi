# Flight Poke API

This is just an experiment to show you how to use Flight and PokeAPI to create a simple API.

## Installation

1. Clone this repository

```bash
git clone https://github.com/n0nag0n/flight-pokeapi
```

2. Install dependencies

```bash
composer install
```

3. Run the server

```bash
php -S localhost:8080 -t public
```

## Usage

You can use the following endpoints:

- `/`: Hello World
- `/pokemon`: List of all Pokemon Types
- `/pokemon/type/{type}`: List of all Pokemon of a specific type
- `/pokemon/{id}`: Information about a specific Pokemon

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
