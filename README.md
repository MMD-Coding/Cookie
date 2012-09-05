Cookie
=====

This script works as simple cookie interface with compress option

You can store different data type into cookies.

Usage
--------
    <?php
    include (__DIR__.'/libs/ANS/Cookie/Cookie.php');

    # Use language cookie
    $Cookie = new \ANS\Cookie\Cookie();

    $Cookie->setSettings(array(
        'name' => 'language'
    ));

    $language = $Cookie->get();

    if (!$language) {
        $Cookie->set('en');
    }

    # Change to cookie data and set a new global expire
    $Cookie->setSettings(array(
        'name' => 'data',
        'expire' => 3600
    ));

    $data = $Cookie->get();

    if (!$data) {
        $Cookie->set(array(
            'user' => $user,
            'email' => $mail,
            'last_login' => $last_login
        ));
    }
