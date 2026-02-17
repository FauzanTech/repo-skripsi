<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;
use Config\MyConstants;
class UserModel extends Model
{
  protected $table      = 'user';
  protected $primaryKey = 'user_id';

  protected $useAutoIncrement = false;

  protected $returnType     = 'array';

  protected $allowedFields = ['user_id', 'identity_number', 'fullname', 'prefix_title', 'suffix_title', 'username', 'password', 'email', 'account_start_date', 'account_expired_date', 'status', 'role', 'prodi_id', 'deleted_at'];

  protected bool $allowEmptyInserts = false;
  protected bool $updateOnlyChanged = true;

  // Dates
  protected $useTimestamps = false;
  protected $useSoftDeletes = true;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'account_start_date';

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

    if ($data['role'] == MyConstants::MAHASISWA) {
      date_date_set($date,$year + 5,$mount,$day);
      $expiredDate = date_format($date,"Y-m-d");
    }

    // create user account
    $userModel = new UserModel();
    $user = array(
      'user_id' => $userId,
      'fullname' => $data['fullname'],
      'identity_number' => $data['identity_number'],
      'username' => $data['email'],
      'password' => password_hash($data['identity_number'], PASSWORD_DEFAULT),
      'role' => $data['role'],
      'account_start_date' => $startDate,
      'account_expired_expired' => $expiredDate,
      'status' => true,
      'prodi_id' => $data['prodi_id'],
      'email' => $data['email'],
    );

    if (isset($data['prefix_title'])) {
      $user['prefix_title'] = $data['prefix_title'];
    }
    if (isset($data['suffix_title'])) {
      $user['suffix_title'] = $data['suffix_title'];
    } 
    $this->insert($user);
    return array('status' => 'success', 'result' => $user);
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
        'identity_number' =>$data['identity_number'],
        'fullname' => $data['fullname'],
        'prefix_title' => $data['prefix_title'],
        'suffix_title' => $data['suffix_title'],
        'password' => password_hash($data['identity_number'], PASSWORD_DEFAULT),
        'role' => $data['role'],
        'account_start_date' => $startDate,
        'account_expired_expired' => $expiredDate,
        'prodi_id' => $data['prodi_id'],
        'email' => $data['email'],
        'status' => true
      );
    }
    $this->insertBatch($user);
    return array('status' => 'success', 'result' => $user);
  }
}