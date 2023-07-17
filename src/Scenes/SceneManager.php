<?php

declare(strict_types=1);

namespace Dingo\Validation\Scenes;

use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Validation\Contacts\Validatable;
use Illuminate\Support\Str;

final class SceneManager implements Scene
{

    protected Factory $factory;

    protected ?string $scene = null;

    protected array $rules = [];

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
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

        return $this->factory->make();
    }

    public function withRule(array|string $rule): Validatable
    {
        if (is_string($rule)) {
            $this->extendRule($rule);
        }

        if (is_array($rule)) {
            $this->extendRules($rule);
        }

        return $this->factory->make();
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

    public function replaceRules(Validatable $validatable): array
    {
        $attributes = $this->resolveSceneRuleAttributes($validatable->scenes());

        return array_reduce($attributes, function (array $rules, string $field) use ($validatable): array {

            if ($validatable->hasRule($field)) {
                $rules[$field] = $validatable->rules()[$field];
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

    public function merge(Validatable $validatable): array
    {
        return array_merge($validatable->rules(), $this->getRules($validatable));
    }

    protected function getRules(Validatable $validatable): array
    {
        return array_reduce($this->rules, function (array $extendRules, string $method) use ($validatable): array {

            $ruleMethod = "{$method}Rules";

            if (method_exists($validatable, $ruleMethod)) {
                $extendRules = array_merge($extendRules, $validatable->{$ruleMethod}());
            }

            return $extendRules;
        }, []);
    }
}