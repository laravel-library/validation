<?php

declare(strict_types=1);

namespace Dingo\Validation\Scenes;

use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Validation\Contacts\Validatable;
use Dingo\Validation\Validation\ValidatesWhenScene;
use Illuminate\Support\Str;

final class SceneManager implements Scene
{
    protected readonly Validatable|ValidatesWhenScene $validatable;

    protected ?string $scene = null;

    protected array $rules = [];

    public function __construct(Factory $factory, string $controller)
    {
        $this->validatable = $factory->make($controller);
    }

    public function hasRule(): bool
    {
        return !empty($this->scene);
    }

    public function hasScene(): bool
    {
        return !empty($this->rules);
    }

    public function withScene(string $scene): Validatable
    {
        $this->scene = $scene;

        return $this->validatable;
    }

    public function withRule(array|string $rule): Validatable
    {
        if (is_string($rule)) {
            $this->extendRule($rule);
        }

        if (is_array($rule)) {
            $this->extendRules($rule);
        }

        return $this->validatable;
    }

    protected function extendRule(string $rule): void
    {
        $this->rules[] = Str::camel($rule);
    }

    protected function extendRules(array $rules): void
    {
        $this->rules = array_merge($this->rules, $this->toCamel($rules));
    }

    private function toCamel(array $rules): array
    {
        return array_map(fn(string $method) => Str::camel($method), $rules);
    }

    public function replaceRules(): array
    {
        $attributes = $this->resolveSceneRuleAttributes($this->validatable->scenes());

        return array_reduce($attributes, function (array $rules, string $field): array {

            if ($this->validatable->hasRule($field)) {
                $rules[$field] = $this->validatable->rules()[$field];
            }

            return $rules;
        }, []);
    }

    protected function resolveSceneRuleAttributes(array $scenes): array
    {
        $attributes = $scenes[$this->scene];

        return is_string($attributes)
            ? explode(',', $attributes)
            : $attributes;
    }

    public function merge(): array
    {
        return array_merge($this->validatable->rules(), $this->getRules());
    }

    protected function getRules(): array
    {
        return array_reduce($this->rules, function (array $extendRules, string $method): array {

            $ruleMethod = "{$method}Rules";

            if (method_exists($this->validatable, $ruleMethod)) {
                $extendRules = array_merge($extendRules, $this->validatable->{$ruleMethod}());
            }

            return $extendRules;
        }, []);
    }
}