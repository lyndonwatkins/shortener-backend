<?php

namespace App\Traits\Eloquent;

trait TouchesTimestamps
{
    public function touchTimestamp($column)
    {
        $this->{$column} = $this->freshTimestamp();
        $this->save();
    }

}