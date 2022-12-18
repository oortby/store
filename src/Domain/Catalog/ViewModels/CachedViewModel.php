<?php

declare(strict_types=1);

namespace App\View\ViewModels;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;
use ReflectionMethod;

class CachedViewModel
{
    protected function cacheKey(string $view): string
    {
        return "view_model_$view";
    }

    public function view(string $view): Factory|View|Application
    {
        $data = Cache::rememberForever($this->cacheKey($view), function () {
            return $this->data();
        });

        return view($view, $data);
    }

    protected function data(): array
    {
        $reflection = new ReflectionClass($this);
        $data = [];
        $ignoredMethods = [
            'view',
            'data',
            'cacheKey'
        ];

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if(!str($method->getName())->contains('__') && !in_array($method->getName(), $ignoredMethods)) {
                $data[$method->getName()] = $this->{$method->getName()}();
            }
        }

        foreach ($reflection->getProperties(ReflectionMethod::IS_PUBLIC) as $property) {
            $data[$property->getName()] = $this->{$property->getName()};
        }

        return $data;
    }
}