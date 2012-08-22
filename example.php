<?php
include (__DIR__.'/libs/ANS/Cookie/Cookie.php');

# Use language cookie
$Cookie = new \ANS\Cookie\Cookie('language');

$language = $Cookie->get();

if (!$language) {
    $Cookie->set('en');
}

# Change to cookie data and set a new global expire
$Cookie->setName('data');
$Cookie->setExpire(3600);

$data = $Cookie->get();

if (!$data) {
    $Cookie->set(array(
        'user' => $user,
        'email' => $mail,
        'last_login' => $last_login
    ));
}
