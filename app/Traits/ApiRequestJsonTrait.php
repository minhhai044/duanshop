<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiRequestJsonTrait
{
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();
        $errorMessages = [];

        foreach ($errors->messages() as $field => $message) {
            $errorMessages[$field] = array_shift($message);
        }

        $response = response()->json([
            'status' => false,
            'errors' => $errorMessages
        ], Response::HTTP_BAD_REQUEST);


        throw  new HttpResponseException($response);
    }
}
