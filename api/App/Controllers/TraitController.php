<?php

namespace Opu\App\Controllers;

use Opu\Core\Controller;
use Opu\Core\Helpers\CRUD;

class TraitController extends Controller
{
    public function index()
    {
        $traits = CRUD::index('traits', '*');

        $this->response(200, $traits);
    }

    public function single($id)
    {
        $trait = CRUD::single('traits', $id[0], '*');

        if (!$trait) {
            $this->response(404, null, 'We couldn\'t find the trait you are looking for.');
        }

        $this->response(200, $trait, 'trait');
    }

    public function create()
    {
        $data = $this->request(['name']);

        $trait = CRUD::create('traits', $data);

        if (!$trait) {
            $this->response(400, null, 'Something went wrong, trait might already exist, or you have entered something invalid.');
        }

        $this->response(
            201,
            CRUD::single('traits', $trait, '*'),
            'Created successfully!'
        );
    }

    public function update($id)
    {
        $data = $this->request();

        $trait = CRUD::update('traits', $id[0], $data);

        if (!$trait) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(
            200,
            CRUD::single('traits', $id[0], '*'),
            'Updated successfully!'
        );
    }

    public function delete($id)
    {
        $trait = CRUD::delete('traits', $id[0]);

        if (!$trait) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(200, $trait, 'Deleted successfully!');
    }
}
