<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportTypeModel extends Model
{
  protected $table      = 'report_type';
  protected $primaryKey = 'report_type_id';

  protected $useAutoIncrement = true;

  protected $returnType     = 'array';

  protected $allowedFields = ['report_type_name'];

  protected bool $allowEmptyInserts = false;
  protected bool $updateOnlyChanged = true;

  // Dates
  protected $useTimestamps = false;

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