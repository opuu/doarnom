<?php

namespace Opu\App\Controllers;

use Opu\Core\Controller;
use Opu\Core\Helpers\CRUD;

class BreedController extends Controller
{
    public function index()
    {
        $breeds = CRUD::index('breeds', '*');

        $this->response(200, $breeds);
    }

    public function single($id)
    {
        $breed = CRUD::single('breeds', $id[0], '*');

        if (!$breed) {
            $this->response(404, null, 'We couldn\'t find the breed you are looking for.');
        }

        $this->response(200, $breed, 'breed');
    }

    public function create()
    {
        $data = $this->request(['name']);

        $breed = CRUD::create('breeds', $data);

        if (!$breed) {
            $this->response(400, null, 'Something went wrong, breed might already exist, or you have entered something invalid.');
        }

        $this->response(
            201,
            CRUD::single('breeds', $breed, '*'),
            'Created successfully!'
        );
    }

    public function update($id)
    {
        $data = $this->request();

        $breed = CRUD::update('breeds', $id[0], $data);

        if (!$breed) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(
            200,
            CRUD::single('breeds', $id[0], '*'),
            'Updated successfully!'
        );
    }

    public function delete($id)
    {
        $breed = CRUD::delete('breeds', $id[0]);

        if (!$breed) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(200, $breed, 'Deleted successfully!');
    }
}
