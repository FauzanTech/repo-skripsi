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
use App\Models\ValidationMessageModel;

class ReferenceManagment extends BaseController
{
    protected $referenceModel;
    protected $referenceTypeModel;

    // constants
    public $valid = 'VALID';
    public $invalid = 'INVALID';
    public $docLetter = 'statement_letter';
    public $docRef = 'reference';
    public function __construct() 
    {
      $session = session();
      if ($session->get('role') != MyConstants::SUPERADMIN && $session->get('role') != MyConstants::ADMIN) {
        header("Location: " . base_url('/auth'));
        exit();
      } else {
        $this->referenceModel = new ReferenceModel();
        $this->referenceTypeModel = new ReferenceTypeModel();
        $this->validationMessageModel = new ValidationMessageModel();
        $this->db = db_connect();
      }
      // $this->memberModel = new MemberModel();
    }

    public function index(): string
    {
      $limit = MyConstants::NUMBER_ROW;;  
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $offset = ($page-1) * $limit;
      $total = $this->referenceModel->where('status', MyConstants::ACCEPTED)->findAll();
      $total = count($total);
      $data['reference'] = $this->referenceModel->where('status', MyConstants::ACCEPTED)->limit($limit, $offset)->find();
      // $data['reference'] = $this->referenceModel->getReferenceByStatus(MyConstants::ACCEPTED);
      $data['page'] = $page;
      $data['nextPage'] = $total > ($page * $limit);
      $data['previousPage'] = $page > 1;
      $data['totalCount'] = $total;
      $data['currentCount'] = $total  <= ($page * $limit) ? $page * $limit : $total;
      $data['statusClass'] = "badge badge-success";
      $data['pageUrl'] = "/published-reference";
      $data['status'] = 'Dipublikasikan';
      return view('admin/index', $data);
    }

    public function unpublishedReference() // string
    {
      $limit = MyConstants::NUMBER_ROW;  
      $page = isset($_GET['page']) ? $_GET['page'] : 1;

      $offset = ($page-1) * $limit;
      $total = $this->referenceModel->where('status', MyConstants::UNDER_REVIEW)->findAll();
      $total = count($total);
      $data['reference'] = $this->referenceModel->where('status', MyConstants::UNDER_REVIEW)->limit($limit, $offset)->find();
      // echo count($data['reference']);
      // $data['reference'] = $this->referenceModel->getReferenceByStatus(MyConstants::UNDER_REVIEW);
      $data['page'] = $page;
      $data['nextPage'] = $total > ($page * $limit);
      $data['previousPage'] = $page > 1;
      $data['totalCount'] = $total;
      $data['currentCount'] = $total  <= ($page * $limit) ? $page * $limit : $total;
      $data['statusClass'] = "badge badge-warning";
      $data['status'] = "Perlu Diperiksa";
      $data['pageUrl'] = "/unpublished-reference";
      return view('admin/index', $data);
    }

    public function rejectedReference(): string
    {
      $limit = MyConstants::NUMBER_ROW;  
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $offset = ($page-1) * $limit;
      $total = $this->referenceModel->where('status', MyConstants::REJECTED)->findAll();
      $total = count($total);
      $data['reference'] = $this->referenceModel->where('status', MyConstants::REJECTED)->limit($limit, $offset)->find();

      // $data['reference'] = $this->referenceModel->getReferenceByStatus(MyConstants::REJECTED);
      $data['page'] = $page;
      $data['nextPage'] = $total > ($page * $limit);
      $data['previousPage'] = $page > 1;
      $data['totalCount'] = $total;
      $data['currentCount'] = $total  <= ($page * $limit) ? $page * $limit : $total;
      $data['statusClass'] = "badge badge-danger";
      $data['status'] = "Ditolak";
      $data['pageUrl'] = "/rejected-reference";
      return view('admin/index', $data);
    }

    public function dashboard()
    {
      return view('admin/dashboard');
    }
    
    public function referenceDetail()
    {
      $referenceId = $_GET['id'];
      $query = $this->db->query('select t1.*, t2.reference_type_name from reference as t1 join reference_type as t2
        on t1.reference_type_id = t2.reference_type_id
        where t1.reference_id="'.$referenceId.'"');
      $referenceDetail = $query->getResultArray();
      $referenceDetail = $referenceDetail[0];
      $status = $referenceDetail['status'];
       $referenceDetail['message_reference'] = '';
       $referenceDetail['message_statement_letter'] = '';

      $queryAuthors = $this->db->query('select t1.*, t2.fullname from authors as t1 left join user as t2 
        on t1.user_id = t2.user_id where t1.reference_id = "'.$referenceId.'"');
      $authors = $queryAuthors->getResultArray();
      $firstSupervisor = '';
      $secondSupervisor = '';

      foreach ($authors as $author) {
        if ($author['description'] == MyConstants::FIRST_SUPERVISOR) {
          $firstSupervisor = $author['fullname'];
        } else if ($author['description'] == MyConstants::SECOND_SUPERVISOR) {
          $secondSupervisor = $author['fullname'];
        } else {
          if ($author['external_author_name']) {
              $authorName[] = $author['external_author_name'];
          } else {
            $authorName[] = $author['fullname'];
          }
        }
      }

      if ($status == MyConstants::UNDER_REVIEW || $status == MyConstants::REJECTED) {
        $validationMessages = $this->validationMessageModel->where('reference_id', $referenceId)->findAll();
        if (count($validationMessages) > 0) {
          foreach($validationMessages as $row) {
            if ($row['doc_name'] == MyConstants::REFERENCE_FILE) {
              $referenceDetail['message_reference'] = $row['message'];
            } else if ($row['doc_name'] == MyConstants::STATEMENT_LETTER) {
              $referenceDetail['message_statement_letter'] = $row['message'];
            }
          }
        }
      }
      $data = array(
        'referenceDetail' => $referenceDetail,
        'authors' => $authors,
        'firstSupervisor' => $firstSupervisor,
        'secondSupervisor' => $secondSupervisor
      );
        return view('admin/referenceDetail', $data);
    }

    public function checkStatementLetter() {
      $docName = $_GET['docName'];
      $refId = $_GET['refId'];
      $filepath = APPPATH . 'DocumentUploads/StatementLetters/'.$docName;
      $filename = "contoh.pdf";
      return $this->response
        ->setHeader('Content-Type', 'application/pdf')
        ->setHeader('Content-Disposition', 'inline; filename="' . $docName . '"')
        ->setBody(file_get_contents($filepath));
    }

    public function validateDocument() {
      $refId = $_POST['refId'];
      $docName = $_POST['docName'];
      $docValidation = $_POST['docvalidation'];
      if($docName == $this->docRef) {
        $data = [
          'reference_validation' => $docValidation
        ];
      } else {
        $data = [
          'statement_letter_validation' => $docValidation
        ];
      }
      $this->referenceModel->update($refId, $data);
      if ($docValidation == MyConstants::INVALID) {
        $session = session();
        $createdBy = $session->get('user_id');
        $createdByRole = $session->get('role');
        $rejectionMessage = $_POST['rejectionMessage'];
        $this->validationMessageModel->insert([
          'reference_id'=> $refId,
          'doc_name' => $docName,
          'message' => $rejectionMessage,
          'created_by' =>  $createdBy,
          'status' => MyConstants::STATUS_OPEN
        ]);
      }
      echo json_encode([ 'status' => 'success']);
    }

    public function checkDocumentValidation() {
      $refId = $_POST['refId'];
      $referenceData = $this->referenceModel->find($refId);
      $published = $referenceData['published_external'] ? true : false;
      $data = array(
        'document_status' => $referenceData['status'],
        'statement_letter_validation' => $referenceData['statement_letter_validation'],
        'reference_validation' => $referenceData['reference_validation'],
        'published_external' => $published
      );
      echo json_encode($data);
    }

    public function updateReferenceStatus() {
      $refId = $_POST['refId'];
      $status = $_POST['status'];
      $data = ['status' => $_POST['status']];
      if ($status == MyConstants::ACCEPTED) {
        $data['publication_date'] = date('Y-m-d');
      }
      $this->referenceModel->update($refId, $data);
      $this->sendingEmail('rafrinmardhiyyah@ith.ac.id', 'konfirmasi', '<h2>Selamat! Artikel anda telah dimuat!</h2>');
      echo json_encode(['status' => 'success']);
    }

    public function insertNewDiciplines() {
      $this->disciplinesModel->insert(array('disciplines_id'=> $_POST['code'], 'disciplines_name'=>$_POST['name']));
      if ($this->disciplinesModel->getInsertID()) {
        echo json_encode(array('status'=>'success'));
        exit();
      } else {
        echo json_encode(array('status'=>'failed'));
        exit();
      }
    }


    public function showStatementLetter($statementLetter)
    {
      $filePath = APPPATH . 'DokumentUploads/StatementLetters/'.$statementLetter;

      $file = new \CodeIgniter\Files\File($filePath, true);
      $binary = readfile($filePath);
      return $this->response
              ->setHeader('Content-Type', $file->getMimeType())
              ->setHeader('Content-disposition', 'inline; filename="' . $file->getBasename() . '"')
              ->setStatusCode(200)
              ->setBody($binary);

    }

    public function showReferenceFile($referenceFile)
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