<?php

declare(strict_types=1);

namespace Elephant\Validation\Scenes;

use Elephant\Validation\Contacts\Resources\Resourceable;
use Elephant\Validation\Contacts\Validation\Scene\Scene;
use Elephant\Validation\Contacts\Validation\Validatable;
use Elephant\Validation\Contacts\Validation\ValidateWhenScene;

final class SceneManager implements Scene
{

    protected ?string $scene = null;

    protected readonly Resourceable $resource;

    public function __construct( Resourceable $resource)
    {

        $this->resource = $resource;
    }

    public function hasRule(string $attribute): bool
    {
        return !empty($this->scene);
    }

    public function hasScene(): bool
    {
        return !empty($this->rules);
    }

    public function withScene(string $scene): ValidateWhenScene
    {
        $this->scene = $scene;

        return $this;
    }

    public function withRule(array|string $rule): ValidateWhenScene
    {
        $this->resource->extra($rule);

        return $this;
    }

    public function replaceRules(Validatable|ValidateWhenScene $validatable): array
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