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
        if ($validatable->hasScene($this->scene)) {

            $sceneRules = $this->getSceneRules($validatable->getScene($this->scene), $validatable->rules());

            return array_merge($sceneRules, $this->getRules($validatable));
        }

        return $validatable->rules();
    }

    public function hasRule(): bool
    {
        return $this->resource->isNotEmpty();
    }

    public function mergeRules(Validatable|ValidateWhenScene $validatable): array
    {
        return array_merge($validatable->rules(), $this->getRules($validatable));
    }

    protected function getRules(Validatable|ValidateWhenScene $validatable): array
    {
        return array_reduce($this->resource->values(), function (array $extendRules, string $method) use ($validatable): array {

            $ruleMethod = "{$method}Rules";

            if ($validatable->hasRuleMethod($ruleMethod)) {
                $extendRules = array_merge($extendRules, $validatable->{$ruleMethod}());
            }

            return $extendRules;
        }, []);
    }

    public function hasScene(): bool
    {
        return !empty($this->scene);
    }

    protected function getSceneRules(array $attributes, array $rules): array
    {
        return array_reduce($attributes, function (array $carry, string $attribute) use ($rules): array {

            if (array_key_exists($attribute, $rules)) {
                $carry[$attribute] = $rules[$attribute];
            }

            return $carry;
        }, []);
    }
}