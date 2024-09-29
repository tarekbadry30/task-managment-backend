<?php
namespace App\Http\Traits;

use Illuminate\Contracts\Validation\Validator;

use Illuminate\Http\Exceptions\HttpResponseException;


trait ValidationRequestTrait
{

    protected function failedValidation(Validator $validator)
    {
        // Customize the JSON response for failed validation
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Validation failed',
            'errors' => $errors,
        ], 422);

        throw new HttpResponseException($response);
    }


}
