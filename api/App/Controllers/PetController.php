<?php

namespace Opu\App\Controllers;

use Opu\Core\Controller;
use Opu\Core\Helpers\CRUD;

class PetController extends Controller
{
    public function index()
    {
        $pets = CRUD::index('pets', '*');

        $this->response(200, $pets);
    }

    public function single($id)
    {
        $pet = CRUD::single('pets', $id[0], '*');

        if (!$pet) {
            $this->response(404, null, 'We couldn\'t find the pet you are looking for.');
        }

        $this->response(200, $pet, 'Pet');
    }

    public function create()
    {
        $data = $this->request(['name']);

        $data['user_id'] = XUSER['id'];

        $pet = CRUD::create('pets', $data);

        if (!$pet) {
            $this->response(400, null, 'Something went wrong, pet might already exist, or you have entered something invalid.');
        }

        $this->response(
            201,
            CRUD::single('pets', $pet, '*'),
            'Created successfully!'
        );
    }

    public function update($id)
    {
        $data = $this->request();

        $pet = CRUD::update('pets', $id[0], $data);

        if (!$pet) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(
            200,
            CRUD::single('pets', $id[0], '*'),
            'Updated successfully!'
        );
    }

    public function delete($id)
    {
        $pet = CRUD::delete('pets', $id[0]);

        if (!$pet) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(200, $pet, 'Deleted successfully!');
    }
}