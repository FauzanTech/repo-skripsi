<?php

namespace App\Controllers;

class Reference extends BaseController
{
    public function index(): string
    {
        return view('admin/index');
    }
}