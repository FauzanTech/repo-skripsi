<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportProgramModel extends Model
{
  protected $table      = 'report_program';
  protected $primaryKey = 'program_id';

  protected $useAutoIncrement = true;

  protected $returnType     = 'array';

  protected $allowedFields = ['report_program_type', 'status'];

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

  public function findAllProgram() {
    return $this->builder()
        ->select('report_program.*, report_type.report_type_name')
        ->join('report_type', 'report_type.report_type_id=report_program.report_type_id')
        ->get()
        ->getResultArray();
  }
  public function findProgramById($id) {
    return $this->builder()
        ->select('report_program.*, report_type.report_type_name')
        ->join('report_type', 'report_type.report_type_id=report_program.report_type_id')
        ->where('report_program.report_type_id', $id)
        ->get()
        ->getResultArray();
  }

}