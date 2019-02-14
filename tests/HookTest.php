<?php

require_once('common.php');

class HookTest extends \PHPUnit\Framework\TestCase {
    public function setUp() {
        Hooks::shutdown();
        parent::setUp();
    }

    public function testBasicFilters() {
        $hook = Hooks::getInstance();

        $hook->addFilter('some.tag', function ($var) {
            return strtoupper($var);
        });
        $hook->addFilter('some.tag', function ($var) {
            return substr($var,1);
        });
        $hook->addFilter('some.tag', function ($var) {
            return substr($var,1);
        });
        $filtered = $hook->applyFilters('some.tag', 'something');

        $this->assertEquals('METHING', $filtered);
    }

    /**
     * Each action gets the same args.
     * Nothing of any use is returned.
     *
     */
    public function testBasicActions() {
        $hook = Hooks::getInstance();

        // actions shouldn't
        $hook->addAction('some.tag', function ($var) {
            $this->assertEquals('test', $var);
        });
        $hook->addAction('some.tag', function ($var) {
            $this->assertEquals('test', $var);
        });

        $foo = $hook->doAction('some.tag', 'test');

        $this->assertEmpty($foo);
    }
}
