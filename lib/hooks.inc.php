<?php
require_once(dirname(__FILE__) . '/Hooks.php');

/**
 * Token thin wrapper around \Hooks\Hooks just incase someone feels the need to
 * swap it for a different class/implementation.
 *
 * <code>
 * $hook = Hooks::getInstance()->addFilter('some.tag', function($var) { return strtoupper($var); });
 * $hook = Hooks::getInstance()->addFilter('some.tag', function($var) { return substr($var,1)); });
 * ....
 * $filtered = $hook->applyFilters('some.tag', 'something');
 * 
 * var_dump($filtered); // OMETHING
 * </code>
 */
class Hooks {

    private static $instance = null;

    /**
     * nuke it.
     */
    public static function shutdown() {
        self::$instance = null;
    }

    /**
     * @return self
     */
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * it's a singleton
     */
    private function __construct() {
        $this->hooks = new \Hooks\Hooks();
    } 


    /**
     * Register a filter to be run on $tag.
     * @param string $tag
     * @param callable $callback 
     * @param int $priority
     * @return boolean true
     */
    public function addFilter($tag, $callback, $priority = 50) {
        return $this->hooks->add_filter($tag, $callback, $priority);
    }

    /**
     * Register an action to run on $tag
     * @param string $tag
     * @param callable $callback
     * @param int $priority (50)
     * @return boolean true
     */
    public function addAction($tag, $callback, $priority = 50) {
        return $this->hooks->add_action($tag, $callback, $priority);
    }

    /**
     * @param string $tag
     * @param array $args (All args supplied to the function)
     */
    public function doAction($tag, $args) {
        $args = func_get_args();
        return call_user_func_array([$this->hooks, 'do_action'], $args);
    }

    /**
     * Run all registered filters on $tag with $value.
     * @param string $tag
     * @param mixed $value
     * @param mixed $args ... any additional args passed in
     * @return mixed $value after filters are applied.
     */
    public function applyFilters($tag, $value) {
        $args = func_get_args();
        return call_user_func_array([$this->hooks, 'apply_filters'], $args);
    }
}
