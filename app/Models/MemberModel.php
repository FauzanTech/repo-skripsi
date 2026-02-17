<?php

namespace App\Models;
// library
use Ramsey\Uuid\Uuid;
use CodeIgniter\I18n\Time;

use CodeIgniter\Model;
use App\Models\UserModel;

class MemberModel extends Model
{
  protected $table      = 'member';
  protected $primaryKey = 'member_id';

  protected $useAutoIncrement = false;

  protected $returnType     = 'array';

  protected $allowedFields = ['fullname', 'prodi_id', 'email', 'identity_number', 'status'];

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

  public function insertNewMember($data) {
    $myuuid = Uuid::uuid4();
    $userId = $myuuid->toString();
    $date = date_create();
    $startDate = date_format($date,"Y-m-d");
    $year = date('Y'); 
    $mount = date('m'); 
    $day = date('d'); 
    $expiredDate = '';

    if ($data['role'] == 'dosen') {
      $username = $data['email'];
    } else {
      $username = $data['identity_number'];
      date_date_set($date,$year + 5,$mount,$day);
      $expiredDate = date_format($date,"Y-m-d");
    }

    // create user account
    $userModel = new UserModel();
    $user = array(
      'user_id' => $userId,
      'username' => $data['email'],
      'password' => md5($data['email']),
      'role' => $data['role'],
      'account_start_date' => $startDate,
      'account_expired_expired' => $expiredDate,
      'status' => true
    );
    $userModel->insert($user);
  
    $myuuid2 = Uuid::uuid4();
    $memberId = $myuuid2->toString();
    $member = array(
      'member_id' => $memberId,
      'user_id' => $userId,
      'prodi_id' => $data['prodi_id'],
      'fullname' => $data['fullname'],
      'email' => $data['email'],
      'role' => $data['role'], 
      'status' => true
    );
    $this->insert($member);
    return array('status' => 'success', 'result' => $member);
  }

  public function insertBatchMember($batchData) {
    $date = date_create();
    $startDate = date_format($date,"Y-m-d");
    $year = date('Y'); 
    $mount = date('m'); 
    $day = date('d'); 
    $expiredDate = '';
    
    
    foreach ($batchData as $data) {
      $myuuid = Uuid::uuid4();
      $userId = $myuuid->toString();
      if ($data['role'] == 'dosen') {
        $username = $data['email'];
      } else {
        $username = $data['identity_number'];
        date_date_set($date,$year + 5,$mount,$day);
        $expiredDate = date_format($date,"Y-m-d");
      }

      $userModel = new UserModel();
      $user[] = array(
        'user_id' => $userId,
        'username' => $data['email'],
        'password' => password_hash($data['email'], PASSWORD_DEFAULT),
        'role' => $data['role'],
        'account_start_date' => $startDate,
        'account_expired_expired' => $expiredDate,
        'status' => true
      );
      
    
      $myuuid2 = Uuid::uuid4();
      $memberId = $myuuid2->toString();
      $member[] = array(
        'member_id' => $memberId,
        'user_id' => $userId,
        'prodi_id' => $data['prodi_id'],
        'fullname' => $data['fullname'],
        'email' => $data['email'],
        'role' => $data['role'], 
        'identity_number' =>$data['identity_number'],
        'status' => true
      );
    }
    $userModel->insertBatch($user);
    $this->insertBatch($member);
    return array('status' => 'success', 'result' => $member);
  }
}