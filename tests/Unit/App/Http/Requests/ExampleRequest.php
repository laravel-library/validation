<?php

namespace App\Http\Requests;

use Koala\Validation\Validation\Contacts\ValidatesWhenScene;
use Koala\Validation\Validation\SceneValidator;

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