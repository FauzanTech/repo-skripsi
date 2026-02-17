<?php

namespace App\Controllers;

// use App\Models\ProdiModel;
// use App\Models\ReferenceTypeModel;
// use App\Models\ReferenceModel;

class User extends BaseController
{  
  public function index(): string
  {
    $data = array(
      'hideSideMenu' => true
    );
    return view('pages/myArticle', $data);
  }
}