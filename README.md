# Stockfighter PHP API Wrapper

I use this library inside my solutions repository, and thought I'd share it with the world.
My solutions repository can be found [here](https://github.com/sammarks/stockfighter-solution-php)
(*spoiler alert*).

## Installation

Just install this library using Composer.

```
composer install sammarks/stockfighter
```

Or you can require it into your project using:

```
composer require sammarks/stockfighter
```

## Usage

This library is setup so that it follows the URL structure of the Stockfighter API documentation
very closely. With that said, here's an example of the library usage:

```php
use \Marks\Stockfighter\Stockfighter;

// Set the API key.
Stockfighter::setApiKey('apikey');

// Create an instance of the API.
$stockfighter = new Stockfighter();

// Check if the API is working.
$api_working = $stockfighter->heartbeat();

// Check if a venue exists and is working.
$test_working = $stockfighter->venue('test')->heartbeat();

// Get all stocks in a venue.
$stocks = $stockfighter->venue('test')->stocks();

// Get information about a stock.
$stock_info = $stockfighter->venue('test')->stock('ABCD')->info();

// Order some ABCD stock.
$order = $stockfighter->venue('test')->stock('ABCD')->order($account, $price, $quantity, $direction, $order_type);
// Direction and Order Type have constants in the Order class, like Order::DIRECTION_BUY,
// Order::DIRECTION_SELL, Order::TYPE_MARKET, etc.
```

### Web Sockets

You can also connect and listen for quotes using WebSockets. Here's an example of that:

```php
// Create a websocket instance.
$websocket = $this->stockfighter->getWebSocketCommunicator()->quotes($account, $venue, $stock);

// Open the connection.
$websocket->connect();

// Receive quotes.
while (true) {
	
	try {
		$quote = $websocket->receive();
		// Do stuff with the quote...
	} catch (ConnectionException $ex) {
		echo "Connection lost.";
		$websocket->connect();
		continue;
	}
	
}
```

## Contributing

If you find an error in my Stockfighter library, or would like to improve it because you're using
it in your own Stockfighter solutions, just send me a pull request! I promise I'll be very open
to suggestions.
