<?php

return [

    'custom' => [
        'name' => [
            'required' => 'Bitte gib deinen Namen ein.',
            'min' => 'Der Name muss mindestens :min Zeichen haben.',
            'max' => 'Der Name darf maximal :max Zeichen lang sein.',
        ],
        'email' => [
            'required' => 'Bitte gib eine E-Mail-Adresse ein.',
            'email' => 'Bitte gib eine gültige E-Mail-Adresse mit eine @-Zeichen ein.',
            'unique' => 'Diese E-Mail-Adresse ist bereits registriert.',
        ],
        'password' => [
            'required' => 'Bitte gib ein Passwort ein.',
            'confirmed' => 'Die Passwörter stimmen nicht überein.',
            'min' => 'Das Passwort muss mindestens :min Zeichen lang sein.',
            'regex' => 'Das Passwort muss mindestens einen Großbuchstaben und eine Zahl enthalten.',
        ],
    ],

];
