<?php

namespace FedexRest\Traits;

trait Conditionable
{
    public function when($value = null, callable $callback = null, callable $default = null)
    {
        $value = $value instanceof \Closure ? $value($this) : $value;

        if ($value) {
            return $callback($this, $value) ?? $this;
        } elseif ($default) {
            return $default($this, $value) ?? $this;
        }

        return $this;
    }
}
