<?php

namespace Opu\Core;

use Opu\Core\Database\Connect;

class Model extends Connect
{
    public $db;

    const NO_PERMISSION = 'no_permission';

    public function __construct()
    {
        $this->db = $this->connect();
    }

    public static function is_authorized()
    {
        $headers = getallheaders();
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

        if (empty($headers['Authorization'])) {
            return false;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $authtoken = str_replace('Bearer ', '', $headers['Authorization']);
        list($token, $id) = explode('@', $authtoken);

        $db = (new Connect())->connect();

        // Fetch user
        $stmt = $db->prepare('SELECT * FROM users WHERE id = :id AND deleted_at IS NULL LIMIT 1');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();

        if (!$user) {
            return false;
        }

        unset($user['password']);

        // Fetch session
        $stmt = $db->prepare('SELECT * FROM sessions WHERE token = :token AND user_id = :user_id LIMIT 1');
        $stmt->execute([':token' => $token, ':user_id' => $id]);
        $session = $stmt->fetch();

        if (!$session || $session['user_agent'] != $user_agent) {
            return false;
        }

        $endpoint = explode('?', $_SERVER['REQUEST_URI'])[0];
        $endpoint = explode('/', $endpoint)[1] ?? '';

        if ($method === 'DELETE' && !$user['can_delete'] && $endpoint !== 'auth') {
            return self::NO_PERMISSION;
        }

        if (!$user['can_login']) {
            return self::NO_PERMISSION;
        }



        if (in_array($user['role'], ['admin', 'superadmin', 'user'])) {
            $user['can_manage'] = '*';
            define('XUSER', $user);

            // Update last activity on session
            $stmt = $db->prepare('UPDATE sessions SET updated_at = NOW() WHERE token = :token AND user_id = :user_id');
            $stmt->execute([':token' => $token, ':user_id' => $id]);

            return true;
        }

        if (empty($user['can_manage'])) {
            return self::NO_PERMISSION;
        }

        $user['can_manage'] = is_string($user['can_manage']) ? json_decode(
            $user['can_manage'] ? $user['can_manage'] : '[]',
            true
        ) : $user['can_manage'];
        $allowed_endpoints = array_map(fn($item) => $item['endpoint'], $user['can_manage']);
        $allowed_endpoints[] = 'auth';

        if (!in_array($endpoint, $allowed_endpoints)) {
            return self::NO_PERMISSION;
        }

        define('XUSER', $user);

        // Update last activity on session
        $stmt = $db->prepare('UPDATE sessions SET updated_at = NOW() WHERE token = :token AND user_id = :user_id');
        $stmt->execute([':token' => $token, ':user_id' => $id]);

        return true;
    }
}