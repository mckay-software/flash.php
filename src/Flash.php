<?

namespace McKay;

class Flash {
	public static function debug  ($message = NULL) { return static::message('debug',   $message); }
	public static function error  ($message = NULL) { return static::message('error',   $message); }
	public static function info   ($message = NULL) { return static::message('info',    $message); }
	public static function success($message = NULL) { return static::message('success', $message); }
	public static function warning($message = NULL) { return static::message('warning', $message); }

	public static function message($type, $message = NULL) {
		if (static::has($type)) {
			$f = json_decode(static::raw($type), true);
			if ($message !== NULL) {
				$time = microtime(true);
				error_log("Flash::$type [$time]: $message");
				$f[] = ['timestamp' => $time, 'message' => $message];
				static::raw($type, json_encode($f));
			} else {
				return $f;
			}
		} else {
			static::raw($type, json_encode([]));
			static::message($type, $message);
		}
	}

	public static function has($type) {
		return isset($_SESSION['flash.' . $type]);
	}

	public static function raw($type, $content = NULL) {
		if ($content !== NULL) {
			$_SESSION['flash.' . $type] = $content;
		} else {
			return $_SESSION['flash.' . $type];
		}
	}

	public static function clear($type = null) {
		if ($type == null) {
			$types = static::types();
			return array_walk($types, function($type) {
				static::clear($type);
			});
		}

		unset($_SESSION['flash.' . $type]);
	}

	public static function types() {
		$res = array_filter(array_map(function ($key) {
			return explode('.', $key, 2)[1];
		}, array_filter(array_keys($_SESSION), function ($key) {
			return substr($key, 0, strlen('flash.')) === 'flash.';
		})));
		return $res;
	}

	public static function all() {
		$messages = [];
		foreach(static::types() as $type) {
			foreach(static::message($type) as $message) {
				$message['type'] = $type;
				$messages[] = $message;
			}
		}

		usort($messages, function($a, $b) {
			$a = $a['timestamp'];
			$b = $b['timestamp'];
			return $a < $b ? -1 : (
				$a > $b ? +1 : (
				0)); // I miss the spaceship operator
		});
		return $messages;
	}
}

?>
