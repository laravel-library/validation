<?php

namespace Elephant\Validation\Validation;

use Elephant\Validation\Contacts\Validation\ValidateWhenScene;

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

}