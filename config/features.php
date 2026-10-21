<?php
return [
    // Never allow self-registration on managed deployments unless explicitly opted in.
    'public_registration' => env('ALLOW_PUBLIC_REGISTRATION', false),
];
