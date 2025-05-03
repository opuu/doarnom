<?php

namespace Opu\Core;

/**
 * Events class
 *
 * This class is responsible for managing events and listeners.
 *
 * @since 1.0.0
 * @package app\events
 */
class Events
{
    /**
     * @var array $listeners Array of listeners
     */
    public array $listeners = [];

    /**
     * Add a listener to an event
     *
     * @param string $event Event name
     * @param callable $callback Callback function
     * @param int $priority Priority
     * @return void
     */
    public function addListener(string $event, callable $callback, int $priority = 10): void
    {
        $this->listeners[$event][$priority][] = $callback;
    }

    /**
     * Dispatch an event
     *
     * @param string $event Event name
     * @param mixed $data Data
     * @return mixed
     */
    public function dispatch(string $event, mixed $data = null): mixed
    {
        if (!isset($this->listeners[$event])) {
            return $data;
        }

        // Sort listeners by priority
        foreach ($this->listeners[$event] as &$callbacks) {
            ksort($callbacks);
        }

        // Trigger actions and filters
        foreach ($this->listeners[$event] as $callbacks) {
            foreach ($callbacks as $callback) {
                if (is_null($data)) {
                    call_user_func($callback); // Action
                } else {
                    $data = call_user_func($callback, $data); // Filter
                }
            }
        }

        return $data;
    }

    /**
     * Remove a listener from an event
     *
     * @param string $event Event name
     * @param callable $callback Callback function
     * @return void
     */
    public function removeListener(string $event, callable $callback): void
    {
        if (!isset($this->listeners[$event])) {
            return;
        }

        foreach ($this->listeners[$event] as $priority => &$callbacks) {
            foreach ($callbacks as $key => $value) {
                if ($value === $callback) {
                    unset($callbacks[$key]);
                }
            }
        }
    }

    /**
     * Remove all listeners from an event
     *
     * @param string $event Event name
     * @return void
     */
    public function removeAllListeners(string $event): void
    {
        unset($this->listeners[$event]);
    }
}
