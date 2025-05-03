<?php

namespace Opu\App\Models;

use Opu\Core\Helpers\Opuu\Maximal;
use Opu\Core\Model;

class AuthModel extends Model
{
    public function signin($id, $pass)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id OR email = :id OR phone = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        if (!$user) {
            return [
                'user' => null,
                'token' => false,
                'error' => 'not_found'
            ];
        }

        if ($user['can_login'] != 1) {
            return [
                'user' => $user,
                'token' => false,
                'error' => 'not_allowed'
            ];
        }

        // delete sessions if no activity for last 1 day
        $stmt = $this->db->prepare('DELETE FROM sessions WHERE updated_at < NOW() - INTERVAL 1 DAY');
        $stmt->execute();

        // check how many devices are logged in with this user id and if last modified is less than 5 minutes
        $stmt = $this->db->prepare('SELECT COUNT(*) AS count FROM sessions WHERE user_id = :user_id AND updated_at > NOW() - INTERVAL 5 MINUTE');
        $stmt->execute(['user_id' => $user['id']]);
        $count = $stmt->fetch()['count'];

        // if more than 3 devices are logged in with this user id and if last modified is less than 5 minutes
        if ($count > 3) {
            return [
                'user' => $user,
                'token' => false,
                'error' => 'too_many_devices'
            ];
        }

        // if more than 4 failed login attempts in last 5 minutes
        if ($user['failed_login_attempts'] > 4 && strtotime($user['last_login_attempt_at']) > strtotime('-5 minutes')) {
            return [
                'user' => $user,
                'token' => false,
                'error' => 'too_many_attempts'
            ];
        }

        if (!password_verify($pass, $user['password'])) {
            $stmt = $this->db->prepare('UPDATE users SET failed_login_attempts = failed_login_attempts + 1, last_login_attempt_at = NOW() WHERE id = :id');
            $stmt->execute(['id' => $user['id']]);
            return [
                'user' => $user,
                'token' => false,
                'error' => 'invalid_password'
            ];
        } else {
            // even if login is successful, but last failed login attempt is more than 4 and less than 5 minutes ago, don't allow login
            if ($user['failed_login_attempts'] > 4 && strtotime($user['last_login_attempt_at']) > strtotime('-5 minutes')) {
                return [
                    'user' => $user,
                    'token' => false,
                    'error' => 'too_many_attempts'
                ];
            } else {
                $stmt = $this->db->prepare('UPDATE users SET failed_login_attempts = 0, last_login_attempt_at = NULL WHERE id = :id');
                $stmt->execute(['id' => $user['id']]);
            }
        }

        unset($user['password']);
        unset($user['failed_login_attempts']);
        unset($user['last_login_attempt_at']);
        unset($user['deleted_at']);

        if ($user['role'] == 'admin' || $user['role'] == 'superadmin') {
            if (!$user['can_manage']) {
                $user['can_manage'] = '*';
            } else {
                $user['can_manage'] = json_decode($user['can_manage']);
            }
        } else {
            $user['can_manage'] = json_decode($user['can_manage']);
        }

        return [
            'user' => $user,
            'token' => $this->create_session($user['id']) . '@' . $user['id']
        ];
    }

    public function signup($name, $email, $phone, $pass)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email OR phone = :phone');
        $stmt->execute(['email' => $email, 'phone' => $phone]);
        $user = $stmt->fetch();

        if ($user) {
            return false;
        }

        $stmt = $this->db->prepare('INSERT INTO users (name, email, phone, password) VALUES (:name, :email, :phone, :password)');
        $stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'password' => password_hash($pass, PASSWORD_BCRYPT)]);

        return true;
    }

    public function create_session($id)
    {
        // id	token	user_id	notification_token  user_agent   ip	created_at	updated_at
        $maximal = new Maximal('SESSION');
        $token = $maximal->GUID1();
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

        $stmt = $this->db->prepare('INSERT INTO sessions (token, user_id, user_agent, ip) VALUES (:token, :user_id, :user_agent, :ip)');
        $stmt->execute(['token' => $token, 'user_id' => $id, 'user_agent' => $user_agent, 'ip' => $ip]);

        return $token;
    }

    public function signout()
    {
        $headers = getallheaders();
        $authtoken = $headers['Authorization'];
        $authtoken = str_replace('Bearer ', '', $authtoken);
        $authtoken = explode('@', $authtoken);
        $id = $authtoken[1];

        $stmt = $this->db->prepare('DELETE FROM sessions WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $id]);

        return true;
    }
}