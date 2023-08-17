<?php

declare(strict_types=1);

namespace Elephant\Validation\Scenes;

use Elephant\Validation\Contacts\Resources\Resourceable;
use Elephant\Validation\Contacts\Validation\Scene\SceneValidatable;
use Elephant\Validation\Contacts\Validation\Validatable;
use Elephant\Validation\Contacts\Validation\Scene;
use Elephant\Validation\Contacts\Validation\ValidateWhenScene;

final class SceneManager implements SceneValidatable
{

    protected ?string $scene = null;

    protected readonly Resourceable $resource;

    public function __construct(Resourceable $resource)
    {

        $this->resource = $resource;
    }

    public function hasRule(): bool
    {
        return !empty($this->rules);
    }

    public function hasScene(): bool
    {
        return !empty($this->scene);
    }

    public function withScene(string $scene): Scene
    {
        $this->scene = $scene;

        return $this;
    }

    public function withRule(array|string $rule): Scene
    {
        $this->resource->extra($rule);

        return $this;
    }

    public function replaceRules(Validatable|Scene|ValidateWhenScene $validatable): array
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
        return array_reduce($this->resource->values(), function (array $extendRules, string $method) use ($validatable): array {

            $ruleMethod = "{$method}Rules";

            if ($validatable->hasRuleMethod($ruleMethod)) {
                $extendRules = array_merge($extendRules, $validatable->{$ruleMethod}());
            }

            return $extendRules;
        }, []);
    }
}