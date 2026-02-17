<?php 
namespace App\Models;
use CodeIgniter\Model;

class AuthorModel extends Model
{
  protected $table      = 'authors';
  protected $primaryKey = 'id';
  protected $useAutoIncrement = true;
  protected $returnType     = 'array';
  protected $allowedFields = ['reference_id', 'user_id', 'external_author_name', 'external_author_affiliation', 'description'];

  protected $useTimestamps = true;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
}
?>