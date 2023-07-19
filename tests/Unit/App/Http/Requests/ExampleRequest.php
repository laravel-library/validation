<?php

namespace App\Http\Requests;

use Dingo\Validation\Validation\Contacts\ValidatesWhenScene;
use Dingo\Validation\Validation\SceneValidator;

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