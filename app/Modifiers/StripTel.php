<?php

namespace App\Modifiers;

use Statamic\Modifiers\Modifier;

class StripTel extends Modifier
{
    protected static $handle = 'strip_tel';
    protected static $aliases = ['sanitize_tel', 'clean_tel'];

    /**
     * Removes all unwanted characters from a telephone number
     * and allows to use the number in a tel: link.
     */
    public function index($value, $params, $context)
    {
        return preg_replace('/[^0-9+]/', '', $value);
    }
}
