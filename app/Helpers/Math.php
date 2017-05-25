<?php

namespace App\Helpers;

class Math
{
    private $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function toBase($value, $base = 62)
    {
        $r = $value % $base;
        $result = $this->base[$r];
        $q = floor($value / $base);

        while ($q) {
            $r = $q % $base;
            $q = floor($q / $base);
            $result = $this->base[$r] . $result;
        }

        return $result;
    }
}