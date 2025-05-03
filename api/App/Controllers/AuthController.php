<?php

namespace Opu\App\Controllers;

use Opu\App\Models\AuthModel;
use Opu\Core\Controller;


class AuthController extends Controller
{
    public $model = null;

    public function __construct()
    {
        $this->model = new AuthModel();
    }

    public function signin()
    {
        $data = $this->request(['user', 'password']);

        $data = $this->model->signin($data['user'], $data['password']);

        if ($data['token']) {
            $this->response(200, $data, 'Login successful.');
        } else {
            switch ($data['error']) {
                case 'not_found':
                    $this->response(404, null, 'User not found with this email or phone number.');
                    break;
                case 'not_allowed':
                    $this->response(401, null, 'This user is not allowed to login.');
                    break;
                case 'too_many_attempts':
                    $this->response(401, null, 'Too many failed login attempts, please try again later.');
                    break;
                case 'invalid_password':
                    $this->response(401, null, 'You have entered an invalid password.');
                    break;
                case 'too_many_devices':
                    $this->response(401, null, 'You can use maximum three devices at a time, please logout from one of your devices and try again.');
                    break;
                default:
                    $this->response(500, null, 'Something went wrong.');
                    break;
            }
        }
    }

    public function signup()
    {
        $data = $this->request(['name', 'email', 'phone', 'password']);

        $data = $this->model->signup($data['name'], $data['email'], $data['phone'], $data['password']);

        if ($data) {
            $this->response(200, null, 'User created successfully.');
        } else {
            $this->response(400, null, 'User already exists with this email or phone number.');
        }
    }

    public function signout()
    {
        $log = $this->model->signout();

        if (!$log) {
            $this->response(401, null, 'You are not logged in.');
        } else {
            $this->response(200, null, 'You have been logged out.');
        }
    }
}
