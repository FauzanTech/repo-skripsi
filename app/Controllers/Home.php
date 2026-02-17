<?php

namespace App\Controllers;

use Config\MyConstants;

use App\Models\ReferenceModel;
use App\Models\ProdiModel;
use App\Models\ReferenceTypeModel;
use App\Models\DisciplinesModel;
use App\Models\ValidationMessageModel;

class Home extends BaseController
{
  protected $referenceModel;
  protected $referenceTypeModel;
  protected $disciplinesModel;
  protected $prodiModel;
  protected $validationMessageModel;

  public function __construct() {
    $this->referenceModel = new ReferenceModel;
    $this->referenceTypeModel = new ReferenceTypeModel();
    $this->disciplinesModel = new DisciplinesModel();
    $this->prodiModel = new ProdiModel();
    $this->validationMessageModel = new ValidationMessageModel();
    $this->db = db_connect();
  }
  public function index(): string
  {
    $data = array(
      'hideSideMenu' => true
    );
    return view('pages/index', $data);
  }

  public function referenceList() {
    $data = [];
    if (isset($_GET['search'])) {
      $data['search'] = $_GET['search'];
    }

    $page = 1;
    if (isset($_GET['page'])) {
      $page = $_GET['page'];
    }
    $data['referenceType'] = $this->referenceTypeModel->findAll();
    $data['disciplines'] = $this->disciplinesModel->findAll();
    $data['reference'] = $this->referenceModel->getReferenceList(['page'=>$page, 'year'=>2019]);
    $data['prodi'] = $this->prodiModel->findAll();
    $data['page'] = $page;
    return view('pages/referenceList', $data);
    
  }

  public function getListItem() {
    $data = [];
    $page = 1;
    if (isset($_GET['page'])) {
      $page = $_GET['page'];
    }
    $result = $this->referenceModel->getReferenceList($_GET);
    $data['reference'] = $result['data'];
    $data['page'] = $page;
    $data['count'] = $result['count'];

    return view('components/referenceList', $data);
  }

  public function embedSearch() {
    $q = isset($_GET['search']) ? $_GET['search'] : $this->request->getVar('q');
    if (!$q) {
      return $this->response->setStatusCode(400)->setJSON(['error' => 'Query empty']);
    }

    // optional filters from UI
    $year = isset($_GET['year']) ? $_GET['year'] : null;
    $reference_type = isset($_GET['reference_type']) ? $_GET['reference_type'] : null;

    $searchUrl = env('SEARCH_API_URL') ?: 'http://localhost:8000';
    $client = \Config\Services::curlrequest();
    try {
      $res = $client->request('POST', rtrim($searchUrl, '/') . '/search', [
        'json' => ['q' => $q, 'top_k_docs' => 10, 'top_k_chunks' => 60],
        'http_errors' => false,
      ]);
    } catch (\Exception $e) {
      return $this->response->setStatusCode(502)->setJSON(['error' => 'Upstream service error', 'detail' => $e->getMessage()]);
    }
    $status = $res->getStatusCode();
    $body = $res->getBody();

    // try to enrich search results by mapping doc_id -> reference in our DB
    $json = json_decode($body, true);
    if (is_array($json) && isset($json['results']) && is_array($json['results'])) {
      foreach ($json['results'] as $k => $r) {
        if (!empty($r['doc_id'])) {
          // build SQL to find matching reference (apply optional filters)
          $params = [];
          $sql = 'SELECT t1.*, t2.reference_type_name FROM reference as t1 JOIN reference_type as t2 on t1.reference_type_id = t2.reference_type_id WHERE t1.reference_id = ? AND t1.status = ?';
          $params[] = $r['doc_id'];
          $params[] = MyConstants::ACCEPTED;

          if (!empty($year)) {
            $sql .= ' AND YEAR(t1.publication_date) >= ?';
            $params[] = (int)$year;
          }

          if (!empty($reference_type)) {
            // reference_type may be comma-separated list like "1,2"; ensure ints
            $types = array_map('intval', explode(',', $reference_type));
            if (count($types) > 0) {
              $sql .= ' AND t1.reference_type_id IN (' . implode(',', $types) . ')';
            }
          }

          $query = $this->db->query($sql, $params);
          $row = $query->getRowArray();
          if ($row) {
            // enrich the result with DB fields so frontend can link to detail page
            $json['results'][$k]['reference_id'] = $row['reference_id'];
            $json['results'][$k]['title'] = $row['title'];
            $json['results'][$k]['year'] = $row['publication_date'] ? date('Y', strtotime($row['publication_date'])) : $json['results'][$k]['year'] ?? null;
            $json['results'][$k]['abstract'] = $row['abstract_in'] ?: ($row['abstract_en'] ?: ($json['results'][$k]['abstract'] ?? ''));
            $json['results'][$k]['reference_type_id'] = $row['reference_type_id'];
            $json['results'][$k]['reference_type_name'] = $row['reference_type_name'];
            // add journal, keywords
            $json['results'][$k]['journal'] = $row['journal_name'] ?: $row['publisher'] ?: ($json['results'][$k]['journal'] ?? '');
            $json['results'][$k]['keywords'] = $row['keywords'] ?: ($json['results'][$k]['keywords'] ?? '');

            // fetch authors for this reference and add concatenated author names
            try {
              $authQuery = $this->db->query('select t1.*, t2.fullname from authors as t1 left join user as t2 on t1.user_id = t2.user_id where t1.reference_id = "'.$row['reference_id'].'"');
              $authRows = $authQuery->getResultArray();
              $authorNames = [];
              if (count($authRows) > 0) {
                foreach ($authRows as $a) {
                  if (!empty($a['external_author_name'])) {
                    $authorNames[] = $a['external_author_name'];
                  } else if (!empty($a['fullname'])) {
                    $authorNames[] = $a['fullname'];
                  }
                }
              }
              if (count($authorNames) > 0) {
                $json['results'][$k]['authors'] = implode(', ', $authorNames);
              }
            } catch (\Exception $e) {
              // ignore author lookup failures
            }

            $json['results'][$k]['_db_match'] = true;
          } else {
            $json['results'][$k]['_db_match'] = false;
          }
        } else {
          $json['results'][$k]['_db_match'] = false;
        }
      }

      $body = json_encode($json);
      return $this->response->setStatusCode(200)->setHeader('Content-Type', 'application/json')->setBody($body);
    }

    return $this->response->setStatusCode($status)->setHeader('Content-Type', 'application/json')->setBody($body);
  }
  public function referenceDetail() {
    $referenceId = $_GET['id'];

    $query = $this->db->query('select t1.*, t2.reference_type_name from reference as t1 join reference_type as t2
      on t1.reference_type_id = t2.reference_type_id
      where t1.reference_id="'.$referenceId.'"');
    $referenceDetail = $query->getResultArray();

    $queryAuthors = $this->db->query('select t1.*, t2.fullname from authors as t1 left join user as t2 
      on t1.user_id = t2.user_id where t1.reference_id = "'.$referenceId.'"');
    $authors = $queryAuthors->getResultArray();
    $firstSupervisor = '';
    $secondSupervisor = '';
    $authorName = [];
    if (count($authors) > 0) {
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
    }

    $reference_type = $referenceDetail[0]['reference_type_id'];
    if ($reference_type == 3 || $reference_type == 4 || $reference_type == 5) {
      $first_author = $authors[0]['user_id'];

      $userQuery = $this->db->query('select t1.prodi_name from prodi as t1 join user as t2 on t1.prodi_id = t2.prodi_id where t2.user_id="'.$first_author.'"');
      $user = $userQuery->getResultArray();
      $referenceDetail[0]['prodi_name'] = $user[0]['prodi_name'];
    }

    // check if there is comment about the reference
    $referenceDetail[0]['message'] = [];
      $session = session();
    if ($session->get('user_id') !== null && $session->get('user_id') == $referenceDetail[0]['created_by']) {
      
      if ($referenceDetail[0]['status'] == MyConstants::UNDER_REVIEW || $referenceDetail[0]['status'] == MyConstants::REJECTED) {
        
        $messagesQuery = $this->validationMessageModel
                            ->where('reference_id', $referenceId)
                            ->where('status', MyConstants::STATUS_OPEN)
                            ->findAll();
        if(count($messagesQuery) >0) {
          $referenceDetail[0]['message'] = $messagesQuery;
        }
      }
    }
    $data = array(
      'hideSideMenu' => true,
      'referenceDetail' => $referenceDetail[0],
      'authors' => $authorName,
      'firstSupervisor' => $firstSupervisor,
      'secondSupervisor' => $secondSupervisor
    );
    return view('pages/referenceDetail', $data);
  }
}
