<?php

namespace App\Enums\Concerns;

trait HasBasicMethods
{
    public function getName(): string
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        return str_replace('_', ' ', ucfirst(strtolower($this->value)));
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'value' => $this->value,
        ];
    }
}