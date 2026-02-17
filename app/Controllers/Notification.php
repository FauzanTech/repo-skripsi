<?php
namespace App\Controllers;

class Notification extends BaseController
{
    public function index(): string
    {
        return view('components/notification');
    }
}
?>
