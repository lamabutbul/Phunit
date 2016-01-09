<?php

namespace Phunit;


class Assert {

    static public function areEqual($actual, $expected) {
        if ($actual !== $expected) {
            throw new AssertException("Expected '{$actual}' to equal '{$expected}'.");
        }
    }

    static public function isInstanceOf($actual, $expected) {
        if (!is_string($expected) || !class_exists($expected)) {
            throw new AssertException("The expected type is invalid.");
        }

        if (!is_object($actual) || strtolower(get_class($actual)) !== strtolower($expected)) {
            throw new AssertException("The parameter is not an instance of '$expected'.");
        }
    }

    static public function areSame($actual, $expected) {
        if (is_array($actual) && is_array($expected)) {
            if (count($actual) !== count($expected)) {
                throw new AssertException("The arrays do not match.");
            }
            foreach ($actual as $key => $val) {
                if (!isset($expected[$key])) {
                    throw new AssertException("The arrays do not match.");
                }
                self::areSame($val, $expected[$key]);
            }
        }
    }

}
