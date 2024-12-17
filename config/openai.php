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
    'api_key' => env('OPENAI_API_KEY', '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'),

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
