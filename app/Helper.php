<?php

namespace App;

class Helper
{

    /**
     * Get dirname of path.
     *
     * E.g.,
     *
     * 'example.com/index.html' => ''
     * 'example.com/images/logo.png' => 'images'
     * 'example.com/media/images/logo.png' => 'media/images'
     *
     * @param string $path
     * @return string
     */
    public static function dirname(string $path): string
    {
        $start = strpos($path, '/');
        $end = strrpos($path, '/');
        if ($start === $end) return '';
        return substr($path, $start + 1, $end - $start - 1);
    }
}
