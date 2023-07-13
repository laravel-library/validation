<?php

namespace Dingo\Validation\Validation;

use Dingo\Validation\Validation\Contacts\Scene;
use Illuminate\Support\Str;
use Illuminate\Validation\Factory;

trait SceneTrait
{

    protected ?string $scene = null;

    protected array $extends = [];

    final public function validator(Factory $factory): \Illuminate\Validation\Validator
    {
        return $factory->make(
            $this->validationData(),
            $this->prepareValidateRules(),
            $this->messages(),
            $this->attributes()
        );
    }

    private function prepareValidateRules(): array
    {
        $rules = $this->rules();

        if ($this->hasRules()) {
            $rules = array_merge($rules, $this->getExtendRules());
        }

        if (!$this->hasScene()) {
            return $rules;
        }

        if ($this->sceneExists()) {
            return $this->getNewRules();
        }

        return $rules;
    }

    private function getExtendRules(): array
    {
        return array_reduce($this->extends, function (array $extends, string $extend) {

            if (method_exists($this, "{$extend}Rules")) {
                $extends = array_merge($extends, $this->{"{$extend}Rules"}());
            }

            return $extends;
        }, []);
    }

    private function sceneExists(): bool
    {
        return isset($this->scenes()[$this->scene]);
    }

    protected function getNewRules(): array
    {
        return array_reduce($this->resolveAttributes(), $this->combine(), []);
    }

    private function combine(): \Closure
    {
        return function (array $newRules, string $attribute): array {
            if (array_key_exists($attribute, $this->rules())) {
                $newRules[$attribute] = $this->rules()[$attribute];
            }

            return $newRules;
        };
    }

    private function resolveAttributes(): array
    {
        $scene = $this->scenes()[$this->scene];

        return match (true) {
            is_string($scene) => explode(',', $scene),
            default           => $scene
        };
    }

    public function withScene(string $name): Scene
    {
        $this->scene = $name;

        return $this;
    }

    public function extend(array|string $rule): Scene
    {
        if (is_array($rule)) {
            $this->extends = $this->filter($rule);
        }

        if (is_string($rule)) {
            $this->extends[] = Str::camel($rule);
        }

        return $this;
    }

    protected function filter(array $rules): array
    {
        return array_merge(
            $this->extends,
            array_map(fn(string $rule) => Str::camel($rule), $rules),
        );
    }

    public function hasRules(): bool
    {
        return !empty($this->extends);
    }

    public function hasScene(): bool
    {
        return !is_null($this->scene);
    }
}