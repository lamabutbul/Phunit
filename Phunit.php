<?php

namespace Phunit;


class Phunit {

    const THROWS_PATTERN = '/@throws .?([a-zA-Z]+)/';

    static public function run() {
        // $colors = new Colors();
        $total = 0;
        $passed = 0;
        $ran = 0;

        $classes = self::getTestsClasses();
        foreach ($classes as $class) {
            $instance = new $class();
            $reflection = new \ReflectionClass($instance);
            $methods = get_class_methods($instance);
            foreach ($methods as $method) {
                $total++;
                print 'Running ' . $method . '... ';
                $result = self::runTestMethod($reflection, $instance, $method);
                $ran++;
                if ($result) {
                    $passed++;
                    // print $colors->getColoredString('passed', 'green');
                    print 'passed';
                }
                else {
                    // print $colors->getColoredString('failed', 'red');
                    print 'failed';
                }
                print PHP_EOL;
            }
        }

        if ($passed !== $ran) {
            // print sprintf('%d out of %d tests passed, ', $passed, $ran) . $colors->getColoredString(sprintf('%d failed', $ran - $passed), 'red');
            printf('%d out of %d tests passed, %d failed', $passed, $ran, $ran - $passed);
        }
        else {
            // print $colors->getColoredString(sprintf('%d out of %d tests passed.', $passed, $ran), 'green');
            printf('%d out of %d tests passed.', $passed, $ran);
        }
    }

    static private function runTestMethod(\ReflectionClass $reflection, $instance, $method) {
        $expectedException = null;
        $comment = $reflection->getMethod($method)->getdoccomment();
        if (preg_match_all(self::THROWS_PATTERN, $comment, $matches, PREG_PATTERN_ORDER)) {
            $expectedException = $matches[1][0];
        }

        try {
            $instance->$method();
            return true;
        }
        catch (\Exception $e) {
            if ($expectedException && $e instanceof $expectedException) {
                return true;
            }
            return false;
        }
    }

    static private function getTestsClasses() {
        $self = 'Punit\Tests';
        $classes = get_declared_classes();
        $testsClasses = array();
        foreach ($classes as $class) {
            if (is_subclass_of($class, $self)) {
                $testsClasses[] = $class;
            }
        }
        return $testsClasses;
    }

}
