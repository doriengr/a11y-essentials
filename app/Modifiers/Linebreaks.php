<?php

namespace App\Modifiers;

use Statamic\Modifiers\Modifier;

class Linebreaks extends Modifier
{
    /**
     * Allow editors to control linebreaks in title/headline fields.
     *
     * @param  mixed  $value  The value to be modified
     * @param  array  $params  Any parameters used in the modifier
     * @param  array  $context  Contextual values
     * @return mixed
     */
    public function index($value, $params, $context)
    {
        $replacements = [
            '[BR]' => '<br>',
            '[SHY]' => '&shy;',
            '[NBSP]' => '&nbsp;',
        ];

        $hide = false;

        if (collect($params)->contains('hide')) {
            $hide = true;
        }

        foreach ($replacements as $keyword => $replacement) {
            $value = str_replace($keyword, $hide ? '' : $replacement, $value);
        }

        return $value;
    }
}
