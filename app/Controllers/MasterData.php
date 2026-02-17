<?php

namespace App\Controllers;
// libraries
use CodeIgniter\I18n\Time;
use Config\MyConstants;

// models
use App\Models\ProdiModel;
use App\Models\ReferenceTypeModel;
use App\Models\ReferenceModel;
use App\Models\DisciplinesModel;
// use App\Models\MemberModel;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Ramsey\Uuid\Uuid;

class MasterData extends BaseController
{
    // protected $memberModel;
    protected $prodiModel;
    protected $referenceModel;
    protected $referenceTypeModel;
    protected $disciplinesModel;
    protected $userModel;
    public function __construct() 
    {
      $session = session();
      if ($session->get('role') != MyConstants::SUPERADMIN && $session->get('role') != MyConstants::ADMIN) {
        header("Location: " . base_url('/auth'));
        exit();
      } else {
        $this->prodiModel = new ProdiModel();
        $this->referenceModel = new ReferenceModel();
        $this->referenceTypeModel = new ReferenceTypeModel();
        $this->disciplinesModel = new DisciplinesModel();
        $this->userModel = new UserModel();
        $this->db = db_connect();
      }
    }

    public function prodi(): string
    {
      $limit = MyConstants::NUMBER_ROW;  
      $page = isset($_GET['page']) ? $_GET['page'] : 1;

      $offset = ($page-1) * $limit;
      $totalCount = $this->prodiModel->countAll();
      $prodi = $this->prodiModel->limit($limit, $offset)->find();
      $data = array(
        'prodi' => $prodi,
        'totalCount'=> $totalCount,
        'pageUrl' => '/prodi',
        'page' => $page,
        'rowStart' => $offset = ($page-1) * $limit + 1
      );
      return view('admin/prodi', $data);
    }

    public function referenceType(): string
    {
      $referenceType = $this->referenceTypeModel->findAll();
      $data = array(
        'referenceType' => $referenceType,
      );
      return view('admin/reference', $data);
    }
     public function disciplines(): string
    {
      $disciplines = $this->disciplinesModel->findAll();
      $data = array(
        'disciplines' => $disciplines,
      );
      return view('admin/disciplines', $data);
    }

    public function insertDisciplines() {
      $name = $_POST['name'];
      $mode = $_POST['mode'];
      $data = array('disciplines_name'=> $name);
      if ($mode == MyConstants::MODE_INSERT) {
        
        $save= $this->disciplinesModel->save($data);
        if ($save){
          echo json_encode(['status' => 'success', 'message' => 'Bidang ilmu baru berhasil ditambahkan']);
        } else {
          echo json_encode(['status' => 'failed', 'message' => 'Terjadi kesalahan! Silahkan hubungi administrator']);
        }
      } else {
        $id = $_POST['id'];
        $this->disciplinesModel->update($id, $data);
        echo json_encode(['status' => 'success', 'message' => 'Disiplin ilmu berhasil diubah']);
      }
    }
    public function dosen(): string
    {
      $limit = MyConstants::NUMBER_ROW;  
      $page = isset($_GET['page']) ? $_GET['page'] : 1;

      $offset = ($page-1) * $limit;
      $allMember = $this->userModel->where(['role' => MyConstants::DOSEN, 'deleted_at' => null])->find();
      // $member = $this->userModel->whereIn('role', [MyConstants::MAHASISWA, MyConstants::DOSEN])->limit($limit, $offset)->find();
      $query = "SELECT a.*, b.prodi_name FROM user as a join prodi as b on a.prodi_id = b.prodi_id WHERE a.role = 'Dosen' and a.deleted_at IS NULL";
      $query = $this->db->query($query);
      $member = $query->getResultArray();
      $prodi = $this->prodiModel->findAll();
      $data = array(
        'member' => $member,
        'prodi' => $prodi,
        'memberType' => MyConstants::DOSEN,
        'totalCount' => count($allMember),
        'page' => $page,
        'pageUrl' => '/dosen'
      );
      return view('admin/member', $data);
    }
     public function mahasiswa(): string
    {
      $limit = MyConstants::NUMBER_ROW;  
      $page = isset($_GET['page']) ? $_GET['page'] : 1;

      $offset = ($page-1) * $limit;
      $allMember = $this->userModel->where(['role' => MyConstants::MAHASISWA, 'deleted_at' => null])->find();
      // $member = $this->userModel->whereIn('role', [MyConstants::MAHASISWA, MyConstants::DOSEN])->limit($limit, $offset)->find();
      $query = "SELECT a.*, b.prodi_name FROM user as a join prodi as b on a.prodi_id = b.prodi_id WHERE a.role='Mahasiswa' and a.deleted_at IS NULL";
      $query = $this->db->query($query);
      $member = $query->getResultArray();
      $prodi = $this->prodiModel->findAll();
      $data = array(
        'member' => $member,
        'memberType' => MyConstants::MAHASISWA,
        'prodi' => $prodi,
        'totalCount' => count($allMember),
        'page' => $page,
        'pageUrl' => '/mahasiswa'
      );
      return view('admin/member', $data);
    }

    public function insertMember() {
      $mode = $_POST['mode'];
      $email = trim($_POST['email']);
      $data = array(
        'role' => trim($_POST['role']),
        'email' => $email,
        'identity_number' => trim($_POST['identity_number']),
        'prodi_id' => trim($_POST['prodi_id']),
        'fullname' => trim($_POST['fullname']),
      );
      if (isset($_POST['prefix_title'])) {
        $data['prefix_title'] = trim($_POST['prefix_title']);
      }
      if (isset($_POST['suffix_title'])) {
        $data['suffix_title'] = trim($_POST['suffix_title']);
      }
      if ($mode == MyConstants::MODE_INSERT) {
        // check if usert exists
        $memberCheck = $this->userModel->where(['email'=>$email, 'deleted_at' => NULL])->find();
        if (count($memberCheck) > 0) {
          echo json_encode(['status' => 'failed', 'message' => 'Email pengguna telah terdaftar!']);
        } else { 
          // only new member's status can automatically be set true
          $data['status'] = true;
          $this->userModel->insertNewMember($data);
          echo json_encode(['status' => 'success', 'message' => 'Anggota baru berhasil ditambahkan']);
        }
      } else {
        $data['status'] = $_POST['status'];
        $this->userModel->where('email', $email)->set($data)->update();
        echo json_encode(['status' => 'success', 'message' => 'Anggota berhasil diubah']);
      }
    }

    public function deleteMember() {
      $userId = $_POST['user_id'];
      $this->userModel->delete($userId);
      echo json_encode(['status' => 'success', 'message' => 'Anggota berhasil dihapus']);

    }

    public function importMember() 
    {
      $file = $this->request->getFile('fileExcel');
      if ($file->isValid() && !$file->hasMoved()) {
          $ext = $file->getClientExtension();
          if ($ext == 'xls' || $ext == 'xlsx') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($file->getTempName());

            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, true, true, true);
            return view('admin/preview_member', ['data' => $data]);
          } else {
            return 'Invalid file format';
          }
      } else {
        return 'File error or has been moved';
      }
    } 

    public function insertBatchMember() {
      $count = count($_POST['name']);
      $prodi_list = $this->prodiModel->findAll();

      for ($i = 0; $i < $count; $i++) {
        $prodi_id = null;
        foreach($prodi_list as $prodi) {
          if (strtolower(trim($_POST['prodi'][$i])) == strtolower(trim($prodi['prodi_name']))) {
            $prodi_id = $prodi['prodi_id'];
          }
        }
        $batchData[] = array(
          'email' => trim($_POST['email'][$i]),
          'identity_number' => trim($_POST['identity_number'][$i]),
          'prodi_id' => $prodi_id,
          'fullname' => trim($_POST['name'][$i]),
          'role' => trim($_POST['role'][$i]),
        );
        
      }
      $result = $this->userModel->insertBatchMember($batchData);
      echo json_encode($result);
    }

    public function admin() {
      $session = session();
      if ($session->get('role') == MyConstants::SUPERADMIN) {
        $data['admin'] = $this->userModel->where('role', 'Admin')->find();
        return view('admin/admin', $data);
      } else {
        return redirect()->to('/dashboard');
      }
      
    }

    public function insertAdmin() {
      $validation = \Config\Services::validation();
      // $data = array(
      //   'role' => 
      //   'email' => trim($_POST['email']),
      //   'identity_number' => trim($_POST['identity_number']),
      //   'fullname' => trim($_POST['fullname']),
      //   'password' => trim($_POST['password']),
      //   'confirmed_password' => trim($_POST['confirmed_password'])
      // );
      $rules = [
        'email' => [
          'rules'=> 'required|valid_email',
          'errors' => [
            'required' => 'Email wajib diisi',
            'valid_email' => 'Email tidak valid'
          ]
        ],
        'identity_number' => [
          'rules'=> 'required',
          'errors' => [
            'required' => 'NIP wajib diisi',
          ]
        ],
        'fullname' => [
          'rules' => 'required',
          'errors' => [
            'required' => 'Nama lengkap wajib diisi',
          ]
        ],
        'user_id' => 'permit_empty',
        'status' => 'permit_empty',
        'password' => [
          'rules'=> 'required',
          'errors' => [
            'required' => 'Kata sandi wajib diisi',
          ]
        ],
        'confirmed_password' => [
          'rules'=> 'required|matches[password]',
          'errors' => [
            'required' => 'Konfirmasi kata sandi wajib diisi',
            'matches[password]' => 'Konfirmasi kata sandi tidak sesuai'
          ]
        ]
      ];

      $data = $this->request->getPost(array_keys($rules));
      
      $validation->setRules($rules);
      if ($validation->run($data)) {
        $data['role'] = MyConstants::ADMIN;
        if (!empty($data['user_id'])) {
          $userId = $data['user_id'];
          unset($data['user_id']);
          if ($_POST['status'] == 0) {
            $data['status'] = false;
          } else { 
            $data['status'] = true;
          }
          $data['username'] = $data['email'];
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
          $this->userModel->update($userId, $data);
          echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
        } else {
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
          $data['status'] = true;
          $data['username'] = $data['email'];
          $myuuid = Uuid::uuid4();
          $userId = $myuuid->toString();
          $data['user_id'] = $userId;
          $success = $this->userModel->save($data);
          if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Admin baru berhasil ditambahkan']);
          } else {
            echo json_encode(['status' => 'failed', 'message' => 'Terjadi kesalahan! Silahkan hubungi administrator']);
          }
        }
      } else {
        echo json_encode(['status' => 'failed', 'errors' => $validation->getErrors()]);
      }
    }

    // insert new prodi
    public function insertNewProdi() {
      $mode = $_POST['mode'];
      $data = array('prodi_code'=> $_POST['code'], 'prodi_name'=>$_POST['name']);
      if ($mode == MyConstants::MODE_INSERT) {
        $this->prodiModel->insert($data);
        if ($this->prodiModel->getInsertID()) {
          echo json_encode(array('status'=>'success', 'message' => 'Prodi baru berhasil ditambahkan'));
          exit();
        } else {
          echo json_encode(array('status'=>'failed', 'message' => 'Terjadi kegagalan sistem!'));
          exit();
        }
      } else {
        $id = $_POST['prodi_id'];
        $this->prodiModel->update($id, $data);
        echo json_encode(array('status'=>'success', 'message' => 'Prodi berhasil diperbarui'));
      }
    }

 
}
