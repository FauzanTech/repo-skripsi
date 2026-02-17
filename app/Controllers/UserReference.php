<?php

namespace App\Controllers;
use Ramsey\Uuid\Uuid;
use Config\Services;
use Config\MyConstants;

use App\Models\ProdiModel;
use App\Models\ReferenceTypeModel;
use App\Models\ReferenceModel;
use App\Models\DisciplinesModel;
use App\Models\SubjectsModel;
use App\Models\UserModel;
use App\Models\AuthorModel;
use App\Models\ReportProgramModel;
use App\Models\ReportTypeModel;
use CodeIgniter\Session\Session;


class UserReference extends BaseController
{
  protected $session;
  protected $userModel;
  protected $referenceModel;
  protected $authorModel;
  protected $subjectModel;

  public function __construct() {
    $this->session = \Config\Services::session();
    $this->userModel = new UserModel();
    $this->session = session();
    if ($this->session->get('role') != MyConstants::DOSEN && $this->session->get('role') != MyConstants::MAHASISWA) {
      header("Location: " . base_url('/auth'));
      exit();
    } else {
      $this->referenceModel = new ReferenceModel();
      $this->authorModel = new AuthorModel();
      $this->subjectModel = new SubjectsModel();
      $this->db = db_connect();
    }
  }
  public function index(): string
  {
    $referenceModel = new ReferenceTypeModel();
    $disciplinesModel = new DisciplinesModel();
    $reportProgramModel = new ReportProgramModel();
    $reportTypeModel = new ReportTypeModel();
    // $prodiModel = new ProdiModel();
    // $userModel = new userModel();
    // $prodiType = $prodiModel->findAll();
    $disciplines = $disciplinesModel->findAll();
    $subjects = $this->subjectModel->findAll();

    $referenceType = $referenceModel->findAll();
    $reportType = $reportTypeModel->findAll();
    $reportProgram = $reportProgramModel->findAllProgram();
    $validation = \Config\Services::validation();

    $data = array(
      'referenceType' => $referenceType,
      'disciplines' => $disciplines,
      // 'prodiType' => $prodiType,
      // 'members'   => $userModel->whereIn('role', ['Dosen', 'Mahasiswa'])->find(),
      // 'supervisors'=> $userModel->where('role', 'Dosen')->find(),
      'subjects'=> $subjects,
      'reportType' => $reportType,
      'reportProgram' => $reportProgram,
      'first_supervisor' => '',
      'second_supervisor' => '',
      'hideSideMenu' => true,
      'method'=> 'new'
    );
    if (isset($_GET['refId'])) {
      $refId = $_GET['refId'];
      $reference = $this->referenceModel->find($refId);
      $authors = $this->authorModel->builder()
        ->select('authors.*, user.fullname, user.identity_number')
        ->join('user', 'user.user_id = authors.user_id', 'left')
        ->where('authors.reference_id', $refId)
        ->get()
        ->getResultArray();

      $data['reference'] = $reference;
      $data['authors'] = $authors;
      $data['method'] = 'edit';
    }
    $data['validation'] = $validation;
    // return view('pages/reference_type_form', $data);
    return view('/pages/new_reference', $data);
  }
  public function createReferenceForm() {
    $referenceType = $_POST['reference_type'];
    $disciplines = $_POST['disciplines'];
    $referenceTitle = $_POST['reference_title'];
    $published = $_POST['published'];
    $authors = $_POST['authors'];
    $affiliations = $_POST['affiliations'];
    $refId = $_POST['reference_id'];
    $method = $_POST['method']; // value: new or edit
    $firstSupervisor = $_POST['first_supervisor'];
    $secondSupervisor = $_POST['second_supervisor'];
    
    $this->session->set('referenceData',[
      'reference_type' => $referenceType,
      'disciplines' => $disciplines,
      'reference_title' => $referenceTitle,
      'published' => $published == 1,
      'authors' => $authors,
      'affiliations' => $affiliations,
      'reference_id' => $refId,
      'method' => $method,
      'first_supervisor' => $firstSupervisor,
      'second_supervisor' => $secondSupervisor
    ]);

    echo json_encode(['status' => 'success']);
  }

  public function newReferenceForm()
  {
    session();
    $dataSession = $this->session->get('referenceData');
    $validation = \Config\Services::validation();
    $data = [
      'referenceType' => $dataSession['reference_type'],
      'published' => $published,
      'hideSideMenu' => true,
      'validation' => $validation,
    ];
    return view('pages/reference_form', $data);
  }

  public function insertNewReference() {
    session();
    $request = \Config\Services::request();
    $validation = \Config\Services::validation();
    
    $dataSession = $this->session->get('referenceData');
    $method = $_POST['method']; // value: new or edit

    $authorModel = new AuthorModel;
    $referenceModel = new ReferenceModel();

    $referenceType = $_POST['reference_type'];
    $referenceTitle = $_POST['title'];
    $published = $_POST['published'];
    $authors = $_POST['authors'];
    $affiliations = $_POST['affiliations'];
    $refId = $_POST['reference_id'];
    $method = $_POST['method']; // value: new or edit
    $firstSupervisor = $_POST['first_supervisor'];
    $secondSupervisor = $_POST['second_supervisor'];

    $myuuid = Uuid::uuid4();
    $referenceId = $myuuid->toString();
    $setRules = [
      'title' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'Judul wajib diisi',
        ]
      ],
      'reference_type_id' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'Jenis koleksi wajib diisi',
        ]
      ],
      'subject_ids' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'Subjek ilmu wajib diisi',
        ]
      ],
      // 'abstract_in' => [
      //   'rules' => (in_array($referenceType, [1, 2, 3, 4, 5])) ? 'required': 'permit_empty',
      //   'errors' => [
      //     'required' => 'Abstrak wajib diisi',
      //   ]
      // ],
      // 'abstract_en' => [
      //   'rules' => (in_array($referenceType, [1, 2, 3, 4, 5])) ? 'required': 'permit_empty',
      //   'errors' => [
      //     'required' => 'Abstrak wajib diisi',
      //   ]
      // ],
      // 'keywords' => [
      //   'rules' => 'required',
      //   'errors' => [
      //     'required' => 'Kata kunci wajib diisi'
      //   ]
      // ],
      'journal_name' => [
        'rules'=> ($referenceType == 1 && $published === 0) ? 'required': 'permit_empty',
        'errors' => [
          'required' => 'Nama jurnal wajib diisi'
        ],
      ],
     'volume' => [
        'rules' => ($referenceType == 1 && $published === 0) ? 'required' : 'permit_empty',
        'errors' => [
          'required' => 'Volume jurnal wajib diisi'
        ],
      ],
      'doi' => [
        'rules' => ($referenceType == 1 && $published === 0) ? 'required|valid_url' : 'permit_empty',
        'errors' => [
          'required' => 'DOI jurnal wajib diisi',
          'valid_url' => 'DOI harus dalam bentuk URL atau tautan',
        ],
      ],
      'publication_date' => [
        'rules'=> ($published === 0) ? 'required' : 'permit_empty',
        'errors' => [
          'required' => 'Tanggal publikasi wajib diisi'
        ]
      ],
      // 'reference_link' => [
      //   'rules' => ($published === 0) ? 'required' : 'permit_empty',
      //   'errors' => [
      //     'required' => 'Tautan publikasi wajib diisi'
      //   ]
      // ],
      'proceedings_title' => [
        'rules' => ($referenceType == 2 && $published === 0) ? 'required' : 'permit_empty',
        'errors' => [
          'required' => 'Nama prosiding wajib diisi'
        ]
      ]
    ];
    if ($method == 'new') {
      $setRules['reference_file'] = [
        'rules' => (!$published) ? 'uploaded[reference_file]' : 'permit_empty',
        'errors' => [
          'uploaded' => 'Dokumen artikel wajib diunggah',
        ]
      ];
    }
    $validation->setRules($setRules);
    $referenceFile = $request->getFile('reference_file') && $request->getFile('reference_file')->isValid() ? $request->getFile('reference_file') : null; 

    $data = [
        'reference_id' => $referenceId,
        'reference_type_id' => $referenceType,
        'title' => $referenceTitle,
        'abstract_in' => $request->getPost('abstract_in'),
        'abstract_en' => $request->getPost('abstract_en'),
        'keywords' => $request->getPost('keywords'),
        'journal_name' => $request->getPost('journal_name') ? $request->getPost('journal_name') : null,
        'publication_date' => $request->getPost('publication_date') ? $request->getPost('publication_date') : null,
        'start_page' => $request->getPost('start_page') ? $request->getPost('start_page') : null,
        'end_page' => $request->getPost('end_page') ? $request->getPost('end_page') : null,
        'volume' => $request->getPost('volume') ? $request->getPost('volume') : null,
        'publisher' => $request->getPost('publisher') ? $request->getPost('publisher') : null,
        'proceedings_city' => $request->getPost('city') ? $request->getPost('city') : null,
        'proceedings_title' => $request->getPost('proceedings_title') ? $request->getPost('proceedings_title') : null,
        'isbn' => $request->getPost('isbn') ? $request->getPost('isbn') : null,
        'doi' => $request->getPost('doi') ? $request->getPost('doi') : null,
        'reference_link' => $request->getPost('reference_link') ? $request->getPost('reference_link') : null,
        'reference_file' => $referenceFile,
        'published_external' => $published === '1',
        'subject_ids' => json_encode($request->getPost('subject_ids')),
        'created_by' => $this->session->get('user_id'),
        'status' => MyConstants::UNDER_REVIEW,
        
      ];
    if (!$validation->run($data)) {
      return $this->response->setJSON([ 'status' => 'failed', 'errors' => $validation->getErrors()]);
    } else {
      $data['language'] = $request->getPost('language');
      $data['visible_to'] = $request->getPost('visible_to');
      $data['peer_reviewed'] = $request->getPost('peer_reviewed');
      $data['status'] = $request->getPost('status');
      $data['report_type'] = $request->getPost('report_type');
      $data['book_publisher'] = $request->getPost('book_publisher');
      $data['city_book_publisher'] = $request->getPost('city_book_publisher');
      $data['summary'] = $request->getPost('summary');
      $data['isbn'] = $request->getPost('isbn');

      // upload new files
      if ($_POST['method'] == 'new') {
        if ($request->getFile('reference_file') && $request->getFile('reference_file')->isValid()) {
          $referenceFile = $request->getFile('reference_file');
          $referenceFileName = $referenceFile->getName();
          $referenceFile->move(APPPATH . 'DokumentUploads/References', $referenceFileName);
          $data['reference_file'] = $referenceFileName;
        }

        if ($referenceModel->insert($data)) {
          $batchAuthors = [];
          if (count($authors) > 0) {
            for ($i=0; $i<count($authors); $i++) {
              $batchAuthors[$i] = [
                'reference_id' => $referenceId,
                'user_id' => (int) $affiliations[$i] == 1 ? $authors[$i] : null,
                'external_author_name' => (int) $affiliations[$i] == 2 ? $authors[$i] : null,
                'external_author_affiliation' => (int) $affiliations[$i] == 2,
                'description' => $_POST['description'][$i],
              ];
            }
            if ($referenceType == '3' || $referenceType == '4' || $referenceType == '5'){
              $batchAuthors[] =
                [
                  'reference_id' => $referenceId,
                  'user_id' => $_POST['first_supervisor'],
                  'external_author_name' => null,
                  'external_author_affiliation' => false,
                  'description' => MyConstants::FIRST_SUPERVISOR
                ];
                $batchAuthors[] = [
                  'reference_id' => $referenceId,
                  'user_id' => $_POST['second_supervisor'],
                  'external_author_name' => null,
                  'external_author_affiliation' => false,
                  'description' => MyConstants::SECOND_SUPERVISOR
                ];
            }
            $authorModel->insertBatch($batchAuthors);
            return $this->response->setJSON(['status' => 'success', 'datasession' => $dataSession, 'authors' => $batchAuthors]);
          }
          
        }
      } else {
        // update data
        $refId = $_POST['reference_id'];
        // check jika statement letter diunggah ulang
        $dataReference = $this->referenceModel->find($refId);

        // check jika reference diunggah ulang
        if ($request->getFile('reference_file') && $request->getFile('reference_file')->isValid()) {
          // delete file reference yang lama
          $oldReferenceFile = $dataReference['reference_file']; 
          $filePath = 'DokumentUploads/References/'.$oldReferenceFile;
          if (file_exists($filePath)) {
            unlink($filePath);
          }
          $referenceFile = $request->getFile('reference_file');
          $referenceFileName = $referenceFile->getName();
          $referenceFile->move(APPPATH . 'DokumentUploads/References', $referenceFileName);
          $data['reference_file'] = $referenceFileName;
        } else {
          unset($data['reference_file']);
        }
        $result = $referenceModel->update($refId, $data);
       
        // delete all authors and supervisors and then re-insert them
        if ($authorModel->where('reference_id', $refId)->delete()) {
          $batchAuthors = [];
          if (count($authors) > 0) {
            for ($i=0; $i<count($authors); $i++) {
              $batchAuthors[$i] = [
                'reference_id' => $refId,
                'user_id' => (int) $affiliations[$i] == 1 ? $authors[$i] : null,
                'external_author_name' => (int) $affiliations[$i] == 2 ? $authors[$i] : null,
                'external_author_affiliation' => (int) $affiliations[$i] == 2,
                'description' => $_POST['description'][$i]
              ];
            }
            if ($_POST['reference_type'] == 3 || $_POST['reference_type'] == 4 || $_POST['reference_type'] == 1){
              $batchAuthors[] = [
                  'reference_id' => $refId,
                  'user_id' => $_POST['first_supervisor'],
                  'external_author_name' => null,
                  'external_author_affiliation' => false,
                  'description' => MyConstants::FIRST_SUPERVISOR
              ];
              $batchAuthors[] = [
                  'reference_id' => $refId,
                  'user_id' => $_POST['second_supervisor'],
                  'external_author_name' => null,
                  'external_author_affiliation' => false,
                  'description' => MyConstants::SECOND_SUPERVISOR
              ];
            }
            $authorModel->insertBatch($batchAuthors);
          }
          
        }
      }
    
      // return redirect()->to('/my-reference');
      
      return $this->response->setJSON(['status' => 'success']);
    }
  }
  
  public function downloadFile()
  {
    $filePath = WRITEPATH . 'uploads/statement/StatementLetter.docx';
    
    if (file_exists($filePath)) {
      return $this->response->download($filePath, null, true)->setFileName('StatementLetter.docx');
    } else {
      return redirect()->back()->with('error', 'File tidak tersedia.');
    }
  }

  public function getMember() {
    $member = $this->userModel->findAll();
    echo json_encode(['status'=> 'success', 'data' =>$member]);
    exit();
  }

  public function myReference()
  {
    $userId = $this->session->get('user_id');
    $reference = $this->db->query('select t2.*, t1.* from authors as t1 join reference as t2 on t1.reference_id = t2.reference_id
      where t1.user_id = "'.$userId.'"');
    $data = array(
      'hideSideMenu' => true,
      'reference' => $reference->getResultArray()
    );
    return view('pages/myReference', $data);
  }

  public function findAuthorName() {
    $id = $_GET['identity_number'];
    $author = $this->userModel->where('identity_number', $id)->find();
    if (count($author) > 0) {
      echo json_encode(['status' => 'success', 'author_name' => $author[0]['fullname'], 'author_id' => $author[0]['user_id']]);
    } else {
      echo json_encode(['status' => 'failed', 'message' => $id.' tidak ditemukan. Periksa kembali data tersebut']);
    }
  }

  public function downloadReferenceFile($referenceFile)
  {
    $filePath = APPPATH . 'DokumentUploads/References/'.$referenceFile;

    $file = new \CodeIgniter\Files\File($filePath, true);
    $binary = readfile($filePath);
    return $this->response
            ->setHeader('Content-Type', $file->getMimeType())
            ->setHeader('Content-disposition', 'inline; filename="' . $file->getBasename() . '"')
            ->setStatusCode(200)
            ->setBody($binary);

  }
}