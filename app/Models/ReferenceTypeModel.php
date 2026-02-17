<?php

namespace App\Models;

use CodeIgniter\Model;

class ReferenceTypeModel extends Model
{
  protected $table      = 'reference_type';
  protected $primaryKey = 'reference_type_id';

  protected $useAutoIncrement = true;

  protected $returnType     = 'array';

  protected $allowedFields = ['reference_type_name'];

  protected bool $allowEmptyInserts = false;
  protected bool $updateOnlyChanged = true;

  // Dates
  protected $useTimestamps = false;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';

  // Validation
  protected $validationRules      = [];
  protected $validationMessages   = [];
  protected $skipValidation       = false;
  protected $cleanValidationRules = true;

  // Callbacks
  protected $allowCallbacks = true;
  protected $beforeInsert   = [];
  protected $afterInsert    = [];
  protected $beforeUpdate   = [];
  protected $afterUpdate    = [];
  protected $beforeFind     = [];
  protected $afterFind      = [];
  protected $beforeDelete   = [];
  protected $afterDelete    = [];
}