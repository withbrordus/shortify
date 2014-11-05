<?php

namespace shortify;

/**
 * Simple url shorter based on loolu
 * https://code.google.com/p/loolu/source/browse/trunk/common/lib/url/hash.py
 */
class Shortify {

    private $codeSet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    private $base;

    public function __construct($codeSet = null) {
        if($codeSet != null) {
            $this->codeSet = $codeSet;
        }

        $this->base = strlen($this->codeSet);
    }

    public function changeCodeSet($codeSet) {
        $this->codeSet = $codeSet;

        return $this;
    }

    public function addCode($code) {
        $codeSet = array_merge($this->codeSet, $code);

        $this->codeSet = array_unique($codeSet);

        return $this;
    }

    public function removeSet(array $codes) {
        $inverted = array_flip($this->codeSet);

        foreach($codes as $code) {
            unset($inverted[$code]);
        }

        $this->codeSet = array_flip($inverted);

        return $this;
    }

    public function getLength() {
        return sizeof($this->codeSet);
    }

    /**
     * Shorten long url by its id
     *
     * @param $id
     * @param int $padAfter used to create a minimum character
     * @return string
     *
     * Notice:
     * Pad after can be converted into original form by giving the same pad
     */
    public function encode($id, $padAfter = 4) {
        $hash = "";
        $id = intval(str_pad($id, strlen($id) + $padAfter, '0', STR_PAD_RIGHT));

        while($id > 0) {
            $hash = $this->codeSet[$id % $this->base] . $hash;
            $id = floor($id / $this->base);
        }

        return $hash;
    }

    /**
     * Show original url by encoded character
     *
     * @param $encoded
     * @param int $padAfter
     * @return int
     *
     * Notice:
     * Pad after can be converted into original form by giving the same pad
     */
    public function decode($encoded, $padAfter = 4) {
        $id = 0;
        $explode = array_reverse(str_split($encoded));
        foreach($explode as $index => $char) {
            $n = strpos($this->codeSet, $char);

            if($n === -1) return 0;

            $id += $n * pow($this->base, $index);
        }

        $id = substr($id, 0, strlen($id) - $padAfter);

        return intval($id);
    }

} 