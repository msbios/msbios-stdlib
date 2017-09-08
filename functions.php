<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

if (! function_exists('print_w')) {
    /**
     * @param $data
     */
    function print_w(&$data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

if (! function_exists('exception_as_string')) {
    /**
     * @param $exception
     * @return string
     */
    function exception_as_string(&$exception)
    {
        $rtn = "";
        $count = 0;
        foreach ($exception->getTrace() as $frame) {
            $args = "";
            if (isset($frame['args'])) {
                $args = [];
                foreach ($frame['args'] as $arg) {
                    if (is_string($arg)) {
                        $args[] = "'" . $arg . "'";
                    } elseif (is_array($arg)) {
                        $args[] = "Array";
                    } elseif (is_null($arg)) {
                        $args[] = 'NULL';
                    } elseif (is_bool($arg)) {
                        $args[] = ($arg) ? "true" : "false";
                    } elseif (is_object($arg)) {
                        $args[] = get_class($arg);
                    } elseif (is_resource($arg)) {
                        $args[] = get_resource_type($arg);
                    } else {
                        $args[] = $arg;
                    }
                }
                $args = join(", ", $args);
            }
            $rtn .= sprintf(
                "#%s %s(%s): %s(%s)\n",
                $count,
                $frame['file'],
                $frame['line'],
                $frame['function'],
                $args
            );
            $count++;
        }
        return get_class($exception) . ": " . $exception->getMessage() . "\nStacktrace:\n" . $rtn;
    }
}

if (! function_exists('starts_with')) {
    /**
     * @param $haystack
     * @param $needle
     * @param bool $case
     * @return bool
     */
    function starts_with($haystack, $needle, $case = true)
    {
        return $case ? strncmp($haystack, $needle, strlen($needle)) == 0 : strncasecmp($haystack, $needle, strlen($needle)) == 0;
    }
}

if (! function_exists('ends_with')) {
    /**
     * @param $haystack
     * @param $needle
     * @param bool $case
     * @return bool
     */
    function ends_with($haystack, $needle, $case = true)
    {
        return starts_with(strrev($haystack), strrev($needle), $case);
    }
}

if (! function_exists('is_true')) {
    /**
     * @param $mixed
     * @return mixed
     */
    function is_true($mixed)
    {
        return filter_var($mixed, FILTER_VALIDATE_BOOLEAN);
    }
}

if (! function_exists('is_false')) {

    /**
     * @param $mixed
     * @return bool
     */
    function is_false($mixed)
    {
        return ! is_true($mixed);
    }
}

if (! function_exists('is_assoc')) {

    /**
     * @param $array
     * @return bool
     */
    function is_assoc($array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}

if (! function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (! function_exists('random_key')) {
    /**
     * @param int $size
     * @return string
     */
    function random_key($size = 128)
    {
        $cstrong = true;
        if (function_exists('openssl_random_pseudo_bytes')) {
            $rnd = openssl_random_pseudo_bytes($size, $cstrong);
        } else {
            $sha = '';
            $rnd = '';
            for ($i = 0; $i < $size; $i++) {
                $sha = hash('sha256', $sha . mt_rand());
                $char = mt_rand(0, 62);
                $rnd .= chr(hexdec($sha[$char] . $sha[$char + 1]));
            }
        }
        return $rnd;
    }
}

if (! function_exists('json_escape')) {
    /**
     * @param $input
     * @param bool $esc_html
     * @return string
     */
    function json_escape($input, $esc_html = true)
    {
        $result = '';
        if (! is_string($input)) {
            $input = (string)$input;
        }

        $conv = ["\x08" => '\\b', "\t" => '\\t', "\n" => '\\n', "\f" => '\\f', "\r" => '\\r', '"' => '\\"', '\\' => '\\\\'];
        if ($esc_html) {
            $conv['<'] = '\\u003C';
            $conv['>'] = '\\u003E';
        }

        for ($i = 0, $len = strlen($input); $i < $len; $i++) {
            if (isset($conv[$input[$i]])) {
                $result .= $conv[$input[$i]];
            } elseif ($input[$i] < ' ') {
                $result .= sprintf('\\u%04x', ord($input[$i]));
            } else {
                $result .= $input[$i];
            }
        }

        return $result;
    }
}

if (! function_exists('array_clone')) {

    /**
     * @param $array
     * @return array
     */
    function array_clone($array)
    {
        if (empty($array)) {
            return [];
        } else {
            /** @var ArrayObject $arrayObj */
            $arrayObj = new \Zend\Stdlib\ArrayObject($array);
            return $arrayObj->getArrayCopy();
        }
    }
}

if (! function_exists('after')) {
    /**
     * @param $needle
     * @param $haystack
     * @return bool|string
     */
    function after($needle, $haystack)
    {
        if (! is_bool(strpos($haystack, $needle))) {
            return substr($haystack, strpos($haystack, $needle) + strlen($needle));
        }
    }
}

if (! function_exists('after_last')) {
    /**
     * @param $needle
     * @param $haystack
     * @return bool|string
     */
    function after_last($needle, $haystack)
    {
        if (! is_bool(strrevpos($haystack, $needle))) {
            return substr($haystack, strrevpos($haystack, $needle) + strlen($needle));
        }
    }
}

if (! function_exists('before')) {
    /**
     * @param $needle
     * @param $haystack
     * @return bool|string
     */
    function before($needle, $haystack)
    {
        return substr($haystack, 0, strpos($haystack, $needle));
    }
}

if (! function_exists('before_last')) {
    /**
     * @param $needle
     * @param $haystack
     * @return bool|string
     */
    function before_last($needle, $haystack)
    {
        return substr($haystack, 0, strrevpos($haystack, $needle));
    }
}

if (! function_exists('between')) {
    /**
     * @param $thisNeddle
     * @param $thatNeedle
     * @param $haystack
     * @return bool|string
     */
    function between($thisNeddle, $thatNeedle, $haystack)
    {
        return before($thatNeedle, after($thisNeddle, $haystack));
    }
}

if (! function_exists('beetween_last')) {
    /**
     * @param $thisNeedle
     * @param $thatNeedle
     * @param $haystack
     * @return bool|string
     */
    function between_last($thisNeedle, $thatNeedle, $haystack)
    {
        return after_last($thisNeedle, before_last($thatNeedle, $haystack));
    }
}

if (! function_exists('strrevpos')) {
    /**
     * @param $instr
     * @param $needle
     * @return bool|int
     */
    function strrevpos($instr, $needle)
    {
        $rev_pos = strpos(strrev($instr), strrev($needle));
        if ($rev_pos === false) {
            return false;
        } else {
            return strlen($instr) - $rev_pos - strlen($needle);
        }
    }
}
