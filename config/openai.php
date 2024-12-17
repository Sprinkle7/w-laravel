<?php
return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key
    |--------------------------------------------------------------------------
    |
    | The OpenAI API key to use for requests. You can set this in your .env file.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Model
    |--------------------------------------------------------------------------
    |
    | The default OpenAI model to use. This can be overridden on individual requests.
    |
    */
    'model' => env('OPENAI_MODEL', 'gpt-4'),
];
