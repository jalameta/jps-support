<?php

return [
    'errors' => [
        'unauthenticated' => [
            'code' => 403,
            'message' => 'Server failed to authenticate the request. Make sure the value of the Authorization header is formed correctly including the signature.',
            'type' => 'AUTHENTICATION_FAILED '
        ],

        'account_disabled' => [
            'code' => 403,
            'message' => 'The specified account is disabled',
            'type' => 'ACCOUNT_DISABLED'
        ],

        'account_banned' => [
            'code' => 403,
            'message' => 'The specified account is banned',
            'type' => 'ACCOUNT_BANNED'
        ],

        'forbidden' => [
            'code' => 403,
            'message' => 'The account being accessed does not have sufficient permissions to execute this operation.',
            'type' => 'INSUFFICIENT_ACCOUNT_PERMISSIONS',
        ],

        'timeout' => [
            'code' => 500,
            'message' => 'The operation could not be completed within the permitted time',
            'type' => 'OPERATION_TIME_OUT'
        ],

        'internal_error' => [
            'code' => 500,
            'message' => 'The server encountered an internal error. Please retry the request.',
            'type' => 'INTERNAL_ERROR'
        ],

        'invalid_input' => [
            'code' => 400,
            'message' => 'One or more of the request inputs is not valid.',
            'type' => 'INVALID_INPUT'
        ],

        'resource_not_found' => [
            'code' => 404,
            'message' => 'The specified resource does not exists.',
            'type' => 'RESOURCE_NOT_FOUND'
        ],

        'resource_already_exists' => [
            'code' => 409,
            'message' => 'The specified resource already exists',
            'type' => 'RESOURCE_ALREADY_EXISTS'
        ],

        'maintenance' => [
            'code' => 403,
            'message' => 'Server currently undergoing maintenance.',
            'type' => 'SERVER_MAINTENANCE'
        ],

        'server_busy' => [
            'code' => 503,
            'message' => 'The server is currently unable to receive requests. please retry your request',
            'type' => 'SERVER_BUSY'
        ]
    ]
];
