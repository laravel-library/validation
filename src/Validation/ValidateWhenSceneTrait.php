<?php

namespace Elephant\Validation\Validation;

use Elephant\Validation\Contacts\Validation\Scene;
use Elephant\Validation\Contacts\Validation\Validatable;
use Elephant\Validation\Contacts\Validation\ValidateWhenScene;
use Elephant\Validation\Exception\ValidationInheritanceException;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator as AbstractValidator;

trait ValidateWhenSceneTrait
{

    final public function withRule(string $rule): Scene|Validatable
    {
        $this->scene->withRule($rule);

        return $this;
    }

    final public function withScene(string $scene): Scene|Validatable
    {
        $this->scene->withScene($scene);

        return $this;
    }

    final public function hasRule(string $attribute): bool
    {
        return array_key_exists($attribute, $this->rules());
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
        $rules = $this->scene->hasRule()
            ? $this->scene->merge($this)
            : $this->rules();

        return $this->scene->hasScene()
            ? $this->replaceRules()
            : $rules;
    }

    private function replaceRules(): array
    {
        if (!is_subclass_of($this, ValidateWhenScene::class)) {
            throw new ValidationInheritanceException('class [' . get_called_class() . '] must be inheritance ' . ValidationInheritanceException::class);
        }

        return $this->scene->replaceRules($this);
    }

}