<?php

namespace Opu\App\Controllers;

use Opu\Core\Controller;
use Opu\Core\Helpers\CRUD;

class AdoptionController extends Controller
{
    public function index()
    {
        $adoption_requests = CRUD::index('adoption_requests', '*');

        $this->response(200, $adoption_requests);
    }

    public function single($id)
    {
        $adoption = CRUD::single('adoption_requests', $id[0], '*');

        if (!$adoption) {
            $this->response(404, null, 'We couldn\'t find the adoption you are looking for.');
        }

        $this->response(200, $adoption, 'Adoption');
    }

    public function create()
    {
        $data = $this->request(['name']);

        $data['user_id'] = XUSER['id'];

        $adoption = CRUD::create('adoption_requests', $data);

        if (!$adoption) {
            $this->response(400, null, 'Something went wrong, adoption might already exist, or you have entered something invalid.');
        }

        $this->response(
            201,
            CRUD::single('adoption_requests', $adoption, '*'),
            'Created successfully!'
        );
    }

    public function update($id)
    {
        $data = $this->request();

        $adoption = CRUD::update('adoption_requests', $id[0], $data);

        if (!$adoption) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(
            200,
            CRUD::single('adoption_requests', $id[0], '*'),
            'Updated successfully!'
        );
    }

    public function delete($id)
    {
        $adoption = CRUD::delete('adoption_requests', $id[0]);

        if (!$adoption) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(200, $adoption, 'Deleted successfully!');
    }
}
