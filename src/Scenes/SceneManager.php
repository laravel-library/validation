<?php

declare(strict_types=1);

namespace Elephant\Validation\Scenes;

use Elephant\Validation\Contacts\Resources\DataTransfer;
use Elephant\Validation\Contacts\Validation\Scene\SceneValidatable;
use Elephant\Validation\Contacts\Validation\Validatable;
use Elephant\Validation\Contacts\Validation\ValidateWhenScene;
use Elephant\Validation\Contacts\Validation\Scene;

final class SceneManager implements SceneValidatable
{

    protected ?string $scene = null;

    protected readonly DataTransfer $resource;

    public function __construct(DataTransfer $resource)
    {
        $this->resource = $resource;
    }

    public function hasRule(): bool
    {
        return $this->resource->isNotEmpty();
    }

    public function hasScene(): bool
    {
        return !empty($this->scene);
    }

    public function withScene(string $scene): ValidateWhenScene
    {
        $this->scene = $scene;

        return $this;
    }

    public function withRule(array|string $rule): ValidateWhenScene
    {
        if (is_string($rule)) {
            $rule = [$rule];
        }

        $this->resource->extra($rule);

        return $this;
    }

    public function refreshRules(Validatable|ValidateWhenScene|Scene $validatable): array
    {
        $resolvedRules = $this->getSceneValidateFields($validatable->scenes());

        $attributes = $this->hasScene()
            ? $this->mergeRules($validatable)
            : $validatable->rules();

        return array_reduce($attributes, function (array $rules, string $field) use ($validatable): array {

            if ($validatable->hasRule($field)) {
                $rules[$field] = $validatable->rules()[$field];
            }

            return $rules;
        }, []);
    }

    protected function getSceneValidateFields(Scene $whenScene): array
    {
        $attributes = $whenScene->scenes()[$this->scene] ?? [];

        return is_string($attributes)
            ? explode(',', $attributes)
            : $attributes;
    }

    public function mergeRules(Validatable|Scene|ValidateWhenScene $validatable): array
    {
        return array_merge($validatable->rules(), $this->getRules($validatable));
    }

    protected function getRules(Validatable|Scene|ValidateWhenScene $validatable): array
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