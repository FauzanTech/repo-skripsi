<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\MyConstants;

class ReferenceModel extends Model
{
  protected $table      = 'reference';
  protected $primaryKey = 'reference_id';

  protected $useAutoIncrement = false;

  protected $returnType     = 'array';

  protected $allowedFields = [
    'title', 'reference_type_id', 'abstract_in', 'keywords', 'publication_date', 'journal_name',
    'start_page', 'end_page', 'volume', 'proceedings_title', 'publisher', 'proceedings_city',
    'isbn', 'doi', 'reference_link', 'reference_file', 'statement_letter', 'disciplines_id', 'status',
    'reference_validation', 'statement_letter_validation', 'created_by', 'published_external',
    'visible_to', 'file_type', 'abstract_en', 'embargo_expiry_date', 'language', 'summary', 'isbn','license',
    'peer_reviewed', 'subject_ids', 'report_year', 'report_program_id', 'book_cover', 'license', 'book_publisher', 'city_book_publisher'
  ];

  protected bool $allowEmptyInserts = false;
  protected bool $updateOnlyChanged = true;

  // Dates
  protected $useTimestamps = false;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';

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

  public function getReferenceList($data) {
    if (count($data) == 0) {
      $query = $this->db->query('select t1.*, t2.reference_type_name from reference as t1 join reference_type as t2
      on t1.reference_type_id = t2.reference_type_id');
      $query = $this->db->query($query);
      return $query->getResultArray();
    } else {
      $page = $data['page'];
      $row = MyConstants::NUMBER_ROW;
      $startRow = ($page - 1) * $row;
      $whereClause = "";
      $referenceIds = [];
      $where = ["status = '".MyConstants::ACCEPTED."'"];
       if (isset($data['search']) && !empty($data['search'])) {
        $where[] = "MATCH(t1.title, t1.abstract_in, t1.abstract_en, t1.keywords) AGAINST('".$data['search']."')";
      }
      if (isset($data['reference_type']) && !empty($data['reference_type'])) {
        $where[] = "t1.reference_type_id in (".$data['reference_type'].")";
      }
      if (isset($data['disciplines']) && !empty($data['disciplines'])) {
        $where[] = "t1.disciplines_id in (".$data['disciplines'].")";
      }
      if (isset($data['year']) && !empty($data['year'])) {
        $where[] = "YEAR(t1.publication_date) >= ".$data['year'];
      }
   
      
      if (isset($data['author']) && !empty($data['author'])) {
        $author = $data['author'];
        $query = "SELECT b.reference_id from user as a inner join authors as b on a.user_id = b.user_id where a.fullname LIKE '%$author%' GROUP By b.reference_id";
        $result = $this->db->query($query);
        if ($result ->getNumRows() > 0) {
          $result  =  $result->getResultArray();
          foreach($result as $row) {
            $referenceIds[] = $row['reference_id'];
          }
        }
      }

      if (isset($data['prodi']) && !empty($data['prodi'])) {
        $prodi = $data['prodi'];
        $query = "SELECT b.reference_id from user as a inner join authors as b on a.user_id = b.user_id where a.prodi_id=$prodi GROUP By b.reference_id";
        $result = $this->db->query($query);
        echo ($result->getNumRows());
        if ($result->getNumRows() > 0) {
          $result =  $result->getResultArray();
          foreach($data as $row) {
            $referenceIds[] = $row['reference_id'];
          }
        }
      }

      if (sizeof($referenceIds) > 0) {
        $stringReferenceId = implode(', ', $referenceIds);
        $where[] = "t1.reference_id in (".$stringReferenceId.")";
      }

      if ( sizeof($where) > 0 ) {
        $whereClause .= " WHERE ".implode(" AND ", $where);   
      }
    
      if (isset($data['search']) && !empty($data['search'])) {
         $query = "SELECT t1.*, t2.reference_type_name
          FROM reference as t1 join reference_type as t2 on t1.reference_type_id = t2.reference_type_id"
          .$whereClause." LIMIT $row OFFSET $startRow";

          // to count how many data founds
          $queryCount = "SELECT t1.*, t2.reference_type_name
          FROM reference as t1 join reference_type as t2 on t1.reference_type_id = t2.reference_type_id" .$whereClause;
      } else {
        $query = "SELECT t1.*, t2.reference_type_name
          FROM reference as t1 join reference_type as t2 on t1.reference_type_id = t2.reference_type_id"
          .$whereClause.
          " ORDER BY YEAR(t1.publication_date) DESC LIMIT $row OFFSET $startRow";

        $queryCount = "SELECT t1.*, t2.reference_type_name
          FROM reference as t1 join reference_type as t2 on t1.reference_type_id = t2.reference_type_id"
          .$whereClause.
          " ORDER BY YEAR(t1.publication_date)";
      }
      $queryCount = $this->db->query($queryCount);
      $dt['count'] = count($queryCount->getResultArray());
      $query = $this->db->query($query);
      $dt['data'] = $query->getResultArray();
      
      return $dt;
    }
  }

  public function getReferenceByStatus($status) {
    $query = "SELECT t1.*, t2.reference_type_name FROM reference AS t1 JOIN reference_type AS t2 ON t1.reference_type_id = t2.reference_type_id WHERE t1.status = $status";
    $query = $this->db->query($query);
      $query = $this->db->query($query);
      return $query->getResultArray();
  }
}