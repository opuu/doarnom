<?php

namespace Opu\Core;

use Opu\Core\Database\Connect;
use Opu\Core\Router\Router;

class Start
{
    public $router;
    public $db;

    public function __construct()
    {
        $this->router = new Router();
        $this->db = new Connect();
    }

    public function view(string $name)
    {
        include __DIR__ . "/Views/$name.php";
    }
}
