<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reset Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    'reset' => 'Your password has been reset!',
    'sent' => 'We have emailed your password reset link!',
    'throttled' => 'Please wait before retrying.',
    'token' => 'This password reset token is invalid.',
    'user' => 'We can\'t find a user with that email address.',

    'change' => [
        'title' => 'Change Password',
        'current' => 'Current Password',
        'new' => 'New Password',
        'confirm' => 'Confirm New Password',
        'submit' => 'Change Password',
        'success' => 'Password changed successfully!',
        'error' => 'Current password is incorrect.',
    ],

    'requirements' => [
        'min' => 'Password must be at least :min characters.',
        'mixed' => 'Password must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'Password must contain at least one number.',
        'symbols' => 'Password must contain at least one symbol.',
        'uncompromised' => 'The given password has appeared in a data leak. Please choose a different password.',
    ],

];
