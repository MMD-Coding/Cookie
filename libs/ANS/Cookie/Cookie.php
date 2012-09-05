<?php
namespace ANS\Cookie;

class Cookie {
    private $settings = array();

    public function __construct ()
    {
        $this->setSettings(array(
            'name' => getenv('SERVER_NAME'),
            'expire' => 2592000,
            'compress' => true,
            'path' => '/',
            'domain' => getenv('SERVER_NAME'),
            'secure' => false,
            'httponly' => false
        ));
    }

    public function setSettings ($settings)
    {
        if (isset($settings['expire'])) {
            $settings['expire'] = preg_match('/^[0-9]+$/', $settings['expire']) ? intval($settings['expire']) : strtotime($settings['expire']);
        }

        if (isset($settings['domain'])) {
            $settings['domain'] = (strpos($settings['domain'], '.') !== false) ? $settings['domain'] : null;
        }

        foreach ($settings as $setting => $value) {
            $this->settings[$setting] = $value;
        }
    }

    public function set ($values, $expire = null)
    {
        $cookie = $this->get();

        if (is_array($values) && is_array($cookie)) {
            foreach ($values as $key => $value) {
                if ($value) {
                    $cookie[$key] = $value;
                } else {
                    unset($cookie[$key]);
                }
            }
        } else {
            $cookie = $values;
        }

        if ($cookie) {
            $cookie = serialize($cookie);

            if ($this->settings['compress']) {
                $cookie = gzdeflate($cookie);
            }

            $expire = time() + ($expire ?: $this->settings['expire']);
        } else {
            $cookie = '';
            $expire = time() - 3600;
        }

        return setCookie(
            $this->settings['name'],
            $cookie,
            $expire,
            $this->settings['path'],
            $this->settings['domain'],
            $this->settings['secure'],
            $this->settings['httponly']
        );
    }

    public function get ()
    {
        if (isset($_COOKIE[$this->settings['name']])) {
            $cookie = $_COOKIE[$this->settings['name']];

            if ($this->settings['compress']) {
                $cookie = gzinflate($cookie);
            }

            return unserialize($cookie);
        } else {
            return array();
        }
    }
}
