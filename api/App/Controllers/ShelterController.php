<?php

namespace Opu\App\Controllers;

use Opu\Core\Controller;
use Opu\Core\Helpers\CRUD;

class ShelterController extends Controller
{
    public function index()
    {
        $shelters = CRUD::index('shelters', '*');

        $this->response(200, $shelters);
    }

    public function single($id)
    {
        $shelter = CRUD::single('shelters', $id[0], '*');

        if (!$shelter) {
            $this->response(404, null, 'We couldn\'t find the shelter you are looking for.');
        }

        $this->response(200, $shelter, 'shelter');
    }

    public function create()
    {
        $data = $this->request(['name']);

        $shelter = CRUD::create('shelters', $data);

        if (!$shelter) {
            $this->response(400, null, 'Something went wrong, shelter might already exist, or you have entered something invalid.');
        }

        $this->response(
            201,
            CRUD::single('shelters', $shelter, '*'),
            'Created successfully!'
        );
    }

    public function update($id)
    {
        $data = $this->request();

        $shelter = CRUD::update('shelters', $id[0], $data);

        if (!$shelter) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(
            200,
            CRUD::single('shelters', $id[0], '*'),
            'Updated successfully!'
        );
    }

    public function delete($id)
    {
        $shelter = CRUD::delete('shelters', $id[0]);

        if (!$shelter) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(200, $shelter, 'Deleted successfully!');
    }
}
