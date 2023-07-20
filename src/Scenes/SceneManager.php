<?php

declare(strict_types=1);

namespace Dingo\Validation\Scenes;

use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Store\Contacts\DataAccess;
use Dingo\Validation\Validation\Contacts\Validatable;
use Dingo\Validation\Validation\Contacts\ValidatesWhenScene;

final class SceneManager implements Scene
{

    protected ?string $scene = null;

    protected readonly DataAccess                     $dataAccess;
    protected readonly Validatable|ValidatesWhenScene $validatable;

    public function __construct(Validatable|ValidatesWhenScene $validatable, DataAccess $dataAccess)
    {
        $this->validatable = $validatable;

        $this->dataAccess = $dataAccess;
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
        $this->dataAccess->store($rule);

        return $this->validatable;
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
        return array_reduce($this->dataAccess->raw(), function (array $extendRules, string $method): array {

            $ruleMethod = "{$method}Rules";

            if (method_exists($this->validatable, $ruleMethod)) {
                $extendRules = array_merge($extendRules, $this->validatable->{$ruleMethod}());
            }

            return $extendRules;
        }, []);
    }
}