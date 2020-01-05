<?php

namespace App\Controller;

use Framework\Controller\Controller;

class Srl extends Controller
{
    public function index()
    {
        return $this->render(array('planet' => 'Wocxxrld'));
    }
}
