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

**Note:** If you run into problems with "Your requirements could not be resolved to an installable
set of packages," just require the `devristo/phpws` dependency into your project. For some reason,
composer complains about it but when added to your parent project it works fine:

```
composer require devristo/phpws "dev-master"
```

Additionally, you'll need to add this repository to your `composer.json`:

```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/rb-cohen/phpws"
    }
],
```

If anyone can find a solution for why this isn't working, feel free to open a PR :)

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

// Set the receive callback.
$websocket->receive(function (Quote $quote) {
	// Do stuff with the quote...
	// Once you're done with the websocket connection, return true
	// from this method and the connection will be closed.
});

// Open the connection.
$websocket->connect();
```

### Asynchronous Calling

This library uses [Guzzle](https://github.com/guzzle/guzzle) for its HTTP requests, which uses PSR-7
promises. Naturally, I have included support for promises in this library. Here's an example of how
to place an order using asynchronous calls:

```php
// Assuming you already have a stockfighter instance...
// Here's an example that places an order asynchronously.
$stockfighter->venue('test')->stock('ABCD')->orderAsync($account, $price, $quantity, $direction,
	$order_type)->then(function (Order $order) {
		echo "Oh boy, the order finished! " . $order->totalFulfilled;	
	}, function (StockfighterRequestException $e) {
		echo "Oh no, there was an error with the order! " . $e->getMessage();	
	});
```

### Event Loop

**Important Note:** If you're using either the websockets or the asynchronous calling, you'll need
to initialize the [ReactPHP](https://github.com/reactphp) event loop. Usually this is done as the
last call of your application (as it is a blocking method). Do all of your initialization and
processing logic, and then call the following right before the end of your application:

```php
// Start the Event Loop.
$stockfighter->run();
```

## Contributing

If you find an error in my Stockfighter library, or would like to improve it because you're using
it in your own Stockfighter solutions, just send me a pull request! I promise I'll be very open
to suggestions.
