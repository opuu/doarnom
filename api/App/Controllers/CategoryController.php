<?php

namespace Opu\App\Controllers;

use Opu\Core\Controller;
use Opu\Core\Helpers\CRUD;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = CRUD::index('categories', '*');

        $this->response(200, $categories);
    }

    public function single($id)
    {
        $category = CRUD::single('categories', $id[0], '*');

        if (!$category) {
            $this->response(404, null, 'We couldn\'t find the category you are looking for.');
        }

        $this->response(200, $category, 'Category');
    }

    public function create()
    {
        $data = $this->request(['name']);

        $category = CRUD::create('categories', $data);

        if (!$category) {
            $this->response(400, null, 'Something went wrong, category might already exist, or you have entered something invalid.');
        }

        $this->response(
            201,
            CRUD::single('categories', $category, '*'),
            'Created successfully!'
        );
    }

    public function update($id)
    {
        $data = $this->request();

        $category = CRUD::update('categories', $id[0], $data);

        if (!$category) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(
            200,
            CRUD::single('categories', $id[0], '*'),
            'Updated successfully!'
        );
    }

    public function delete($id)
    {
        $category = CRUD::delete('categories', $id[0]);

        if (!$category) {
            $this->response(400, null, 'Something went wrong.');
        }

        $this->response(200, $category, 'Deleted successfully!');
    }
}
