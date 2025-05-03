<?php

namespace Opu\App\Controllers;

use Opu\Core\Controller;
use Opu\Core\Helpers\CRUD;

class UserController extends Controller
{
    public function index()
    {
        $users = CRUD::index('users', '*');

        foreach ($users['list'] as $key => $user) {
            if (isset($user['password'])) {
                unset($users['list'][$key]['password']);
            }

            if (isset($user['failed_login_attempts'])) {
                unset($users['list'][$key]['failed_login_attempts']);
            }

            if (isset($user['last_login_attempt_at'])) {
                unset($users['list'][$key]['last_login_attempt_at']);
            }

            if (isset($user['can_login'])) {
                $users['list'][$key]['can_login'] = $user['can_login'] ? true : false;
            }

            if (isset($user['can_delete'])) {
                $users['list'][$key]['can_delete'] = $user['can_delete'] ? true : false;
            }

            if (isset($user['can_manage']) && !empty($user['can_manage'])) {
                $users['list'][$key]['can_manage'] = json_decode($user['can_manage']);
            } else {
                $users['list'][$key]['can_manage'] = [];
            }
        }

        $this->response(200, $users);
    }

    public function single($id, $return = false)
    {
        $user = CRUD::single('users', $id[0], '*');

        if (!$user) {
            $this->response(404, null, 'We couldn\'t find the user you are looking for.');
        }

        if (isset($user['password'])) {
            unset($user['password']);
        }

        if (isset($user['failed_login_attempts'])) {
            unset($user['failed_login_attempts']);
        }

        if (isset($user['last_login_attempt_at'])) {
            unset($user['last_login_attempt_at']);
        }

        if (isset($user['can_login'])) {
            $user['can_login'] = $user['can_login'] ? true : false;
        }

        if (isset($user['can_delete'])) {
            $user['can_delete'] = $user['can_delete'] ? true : false;
        }

        if (isset($user['can_manage']) && !empty($user['can_manage'])) {
            $user['can_manage'] = json_decode($user['can_manage']);
        } else {
            $user['can_manage'] = [];
        }

        if ($return) {
            return $user;
        }

        $this->response(200, $user, 'User');
    }

    public function create()
    {
        $data = $this->request(['name', 'email', 'role']);

        if (!isset($data['password'])) {
            $data['password'] = 'Str0ngP@ssw0rd';
        }

        if (!isset($data['role'])) {
            $data['role'] = 'cashier';
        } else {
            if (!in_array($data['role'], ['admin', 'manager', 'cashier'])) {
                $this->response(400, null, 'Invalid user role.');
            }
        }

        // if manager is creating a user, it must be a cashier
        if (XUSER['role'] == 'manager') {
            if ($data['role'] != 'cashier') {
                $this->response(400, null, 'You are not allowed to create this user role.');
            }
        }

        // if cashier is creating a user, don't allow
        if (XUSER['role'] == 'cashier') {
            $this->response(400, null, 'You are not allowed to create a user.');
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        if (isset($data['can_manage'])) {
            $data['can_manage'] = json_encode($data['can_manage']);
        }

        if (isset($data['can_login']) && $data['can_login'] == true) {
            $data['can_login'] = 1;
        } else {
            unset($data['can_login']);
        }

        if (isset($data['can_delete']) && $data['can_delete'] == true) {
            $data['can_delete'] = 1;
        } else {
            unset($data['can_delete']);
        }

        $user = CRUD::create('users', $data);

        if (!$user) {
            $this->response(400, null, 'Something went wrong, user might already exist, or you have entered something invalid.');
        }

        $result = $this->single([$user], true);

        $this->response(
            201,
            $result,
            'Created successfully!'
        );
    }

    public function update($id)
    {
        $data = $this->request();

        if (isset($data['password'])) {
            unset($data['password']);
        }

        if (isset($data['role'])) {
            if (!in_array($data['role'], ['admin', 'manager', 'user'])) {
                $this->response(400, null, 'Invalid user role.');
            }
        }

        // if manager is updating a user, it must be a cashier
        if (XUSER['role'] == 'manager' && XUSER['id'] != $id[0]) {
            if ($data['role'] != 'cashier') {
                $this->response(400, null, 'You are not allowed to update this user.');
            }
        }

        // if cashier is updating a user, don't allow
        if (XUSER['role'] == 'cashier') {
            $this->response(400, null, 'You are not allowed to update a user.');
        }

        if (isset($data['can_manage'])) {
            $data['can_manage'] = json_encode($data['can_manage']);
        }

        if (isset($data['can_login']) && $data['can_login'] == true) {
            $data['can_login'] = 1;
        } else {
            unset($data['can_login']);
        }

        if (isset($data['can_delete']) && $data['can_delete'] == true) {
            $data['can_delete'] = 1;
        } else {
            unset($data['can_delete']);
        }

        // if manager is editing own account, don't allow changing role, can_login, can_delete, can_manage
        if (XUSER['role'] == 'manager') {
            if (XUSER['id'] == $data['id']) {
                unset($data['role']);
                unset($data['can_login']);
                unset($data['can_delete']);
                unset($data['can_manage']);
            }
        }

        $user = CRUD::update('users', $id[0], $data);

        if (!$user) {
            $this->response(400, null, 'Something went wrong, you might be trying to update email or phone to an existing one.');
        }

        $result = $this->single($id, true);

        $this->response(
            200,
            $result,
            'Updated successfully!'
        );
    }

    public function delete($id)
    {
        $user = CRUD::delete('users', $id[0]);

        if (!$user) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(200, $user, 'Deleted successfully!');
    }
}