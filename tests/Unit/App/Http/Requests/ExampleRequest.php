<?php

namespace App\Http\Requests;

use Dingo\Validation\Validation\SceneValidator;
use Dingo\Validation\Validation\ValidatesWhenScene;

class ExampleRequest extends SceneValidator implements ValidatesWhenScene
{
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }

    public function scenes(): array
    {
        return [];
    }
}