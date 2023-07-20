<?php

declare(strict_types=1);

namespace Dingo\Validation\Scenes;

use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Store\Contacts\DataAccess;
use Dingo\Validation\Validation\Contacts\Validatable;
use Dingo\Validation\Validation\Contacts\ValidatesWhenScene;

final class SceneManager implements Scene
{

    protected ?string $scene = null;

    protected readonly DataAccess $dataAccess;
    protected readonly Factory    $factory;

    public function __construct(Factory $factory, DataAccess $dataAccess)
    {
        $this->factory = $factory;

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

        return $this->factory->make($this);
    }

    public function withRule(array|string $rule): Validatable
    {
        $this->dataAccess->store($rule);

        return $this->factory->make($this);
    }

    public function replaceRules(Validatable|ValidatesWhenScene $validatable): array
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
        return array_reduce($this->dataAccess->raw(), function (array $extendRules, string $method) use ($validatable): array {

            $ruleMethod = "{$method}Rules";

            if (method_exists($validatable, $ruleMethod)) {
                $extendRules = array_merge($extendRules, $validatable->{$ruleMethod}());
            }

            return $extendRules;
        }, []);
    }
}