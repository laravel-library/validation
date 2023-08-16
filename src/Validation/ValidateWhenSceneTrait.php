<?php

namespace Elephant\Validation\Validation;

use Elephant\Validation\Contacts\Validation\ValidateWhenScene;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator as AbstractValidator;

trait ValidateWhenSceneTrait
{

    final public function withRule(string $rule): ValidateWhenScene
    {
        $this->scene->withRule($rule);

        return $this;
    }

    final public function withScene(string $scene): ValidateWhenScene
    {
        $this->scene->withScene($scene);

        return $this;
    }

    final public function hasRule(string $attribute): bool
    {
        return array_key_exists($attribute, $this->rules());
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
            ? $this->scene->replaceRules($this)
            : $rules;
    }

}