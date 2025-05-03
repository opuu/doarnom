<?php

namespace Opu\Core;

use Opu\App\Configs\Config;

class Controller
{
    public static function send($code, $data, $message)
    {
        $payload = [
            'code' => $code,
            'status' => $code <= 299 ? 'success' : 'error',
            'payload' => $data,
            'message' => $message
        ];

        global $events;
        $events->dispatch('app.before_response', $payload);

        header('Content-Type: application/json; charset=utf-8');
        http_response_code($code);
        echo json_encode($payload);
        exit;
    }

    public function response($code = 200, $data = null, $message = null)
    {
        $payload = [
            'code' => $code,
            'status' => $code <= 299 ? 'success' : 'error',
            'payload' => $data,
            'timestamp' => time(),
            'message' => $message
        ];

        global $events;
        $events->dispatch('app.before_response', $payload);

        header('Content-Type: application/json; charset=utf-8');
        http_response_code($code);
        echo json_encode($payload);
        exit;
    }

    public function request($required = [], $optional = [])
    {
        $request = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() != JSON_ERROR_NONE) {
            $this->response(400, null, 'Invalid request body, please provide a valid JSON object.');
        }

        // if all required fields are not present, send which fields are missing
        $missing = [];

        foreach ($request as $key => $value) {
            if (trim($key) != $key) {
                // check type of value and trim it
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        // if $v is string, trim it
                        if (is_string($v)) {
                            $request[trim($key)][trim($k)] = trim($v);
                            unset($request[$key][$k]);
                        }
                    }
                } else {
                    $request[$key] = trim($value);
                }
                unset($request[$key]);
            } else {
                // check type of value and trim it
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        // if $v is string, trim it
                        if (is_string($v)) {
                            $request[$key][$k] = trim($v);
                        }
                    }
                } else if (is_string($value)) {
                    $request[$key] = trim($value);
                } else {
                    $request[$key] = $value;
                }
            }

            // if required and empty, add to missing and unset
            if (in_array($key, $required) && (empty($value) || $value == null)) {
                $missing[] = $key;
                unset($request[$key]);
            }
        }

        if (count($missing) > 0) {
            // if debug mode is enabled, send the missing fields
            if (Config::$app_debug) {
                $this->response(400, null, 'Missing required fields: ' . implode(', ', $missing));
            } else {
                $this->response(400, null, 'Fill all required fields marked with red border.');
            }
        }

        return $request;
    }
}