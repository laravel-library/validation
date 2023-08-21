<?php

declare(strict_types=1);

namespace Elephant\Validation\Validation;

use Elephant\Validation\Contacts\Validation\ValidateWhenScene;
use Elephant\Validation\Contacts\Validation\Validatable;
use Elephant\Validation\Contacts\Validation\Scene;
use Elephant\Validation\Exception\ValidationInheritanceException;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator as AbstractValidator;

trait ValidateWhenSceneTrait
{

    final public function withRule(string $rule): ValidateWhenScene|Validatable
    {
        $this->scene->withRule($rule);

        return $this;
    }

    final public function withScene(string $scene): ValidateWhenScene|Validatable
    {
        $this->scene->withScene($scene);

        return $this;
    }

    final public function hasRuleMethod(string $name): bool
    {
        return method_exists($this, $name);
    }

    final public function validator(Factory $factory): AbstractValidator
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
        return $this->scene->hasScene() ? $this->replaceRules() : $this->rules();
    }

    private function replaceRules(): array
    {
        if (!is_subclass_of($this, Scene::class)) {
            throw new ValidationInheritanceException('class [' . get_called_class() . '] must be inheritance ' . Scene::class);
        }

        return $this->scene->refreshRules($this);
    }

}