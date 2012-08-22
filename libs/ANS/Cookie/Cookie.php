<?php
namespace ANS\Cookie;

class Cookie {
    private $name;
    private $expire = 2592000; // 30 days
    private $compress = true;

    public function __construct ($name = '')
    {
        $this->name = $name ?: getenv('SERVER_NAME');
    }

    public function setName ($name)
    {
        return $this->name = $name;
    }

    public function setExpire ($time)
    {
        return $this->expire = preg_match('/^[0-9]+$/', $time) ? intval($time) : strtotime($time);
    }

    public function set ($values, $time = null)
    {
        $cookie = $this->get();

        if (is_array($values)) {
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

            if ($this->compress) {
                $cookie = gzdeflate($cookie);
            }

            $time = time() + ($time ?: $this->expire);
        } else {
            $cookie = '';
            $time = time() - 3600;
        }

        return setCookie($this->name, $cookie, $time);
    }

    public function get ()
    {
        if (isset($_COOKIE[$this->name])) {
            $cookie = $_COOKIE[$this->name];

            if ($this->compress) {
                $cookie = gzinflate($cookie);
            }

            return unserialize($cookie);
        } else {
            return array();
        }
    }
}
