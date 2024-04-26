<?php

/*
 * Configuration for the BugAtlas package.
 * This file contains configuration options used by the BugAtlas package.
 * These options can be customized by the user according to their needs.
 */
return [
    'api_key' => env('BUGATLAS_API_KEY'),
    'secret_key' => env('BUGATLAS_SECRET_KEY'),
    'tag' => env('BUGATLAS_TAG'),
];