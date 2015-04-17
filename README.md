# Flash.php

_Tiny flash message library optimised for Bootstrap._

## Install

Add it to your composer.json:

```json
"mckay/flash": "^1.0.3",
```

then run `$ composer update`.

## Usage

```php
use \McKay\Flash;

Flash::info('Welcome home!');

if ($user->isNearACliff()) {
	Flash::warning('Careful, there');
}

$user->takeARandomStep();
Flash::debug('Uh oh?');

if ($user->heartbeat > 0) {
	Flash::success('Ok');
} else {
	Flash::error('Call an ambulance');
}
```

## In your view

```php
<? foreach(Flash::all() as $flash) { ?>
	<div class="alert alert-<?= $flash['type'] == 'error' ? 'danger' : $flash['type'] ?>">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?= $flash['message'] ?>
	</div>
<? } Flash::clear(); ?>
```

## License

Copyright © McKay Software  
MIT License  
http://mckay.mit-license.org
