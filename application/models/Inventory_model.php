<?php defined('BASEPATH') || exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2021*/
/**
 * Inventory_model Model
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Inventory_model extends Manaknight_Model
{
  protected $_table          = 'inventory';
  protected $_primary_key    = 'id';
  protected $_return_type    = 'array';
  protected $_allowed_fields = [
    'id',
    'user_id',
    'title',
    'school_id',
    'professor_id',
    'class_id',
    'textbook_id',
    'word_count',
    'year',
    'isbn',
    'paypal_email',
    'file',
    'file_id',
    'feature_image',
    'feature_image2',
    'feature_image3',
    'feature_image_id',
    'note_type',
    'description',
    'inventory_note',
    'pin_to_top',
    'approve',
    'status'
  ];
  protected $_label_fields = [
    'ID', 'Title', 'School ID', 'Professor ID', 'Class ID', 'Textbook ID', 'Word Count', 'Year', 'ISBN', 'Paypal Email', 'File', 'File ID', 'Feature Image', 'Feature Image ID', 'Note Type', 'Description', 'Inventory Note', 'Pin to top', 'Approve', 'Status'
  ];
  protected $_use_timestamps   = TRUE;
  protected $_created_field    = 'created_at';
  protected $_updated_field    = 'updated_at';
  protected $_validation_rules = [
    ['id', 'ID', ''],
    // ['title', 'Title', 'required'],
    ['school_id', 'School ', 'required|integer'],
    ['professor_id', 'Professor', 'required|integer'],
    ['class_id', 'Course ', 'required|integer'],
    // ['textbook_id', 'Textbook ', ''],
    ['word_count', 'Word Count', 'required|integer'],
    ['year', 'Year', 'required|integer'],
    ['isbn', 'ISBN', ''],
    // ['paypal_email', 'Paypal Email', 'required'],
    // ['file', 'Outline', 'required'],
    // ['file_id', 'File ID', ''],
    // ['feature_image', 'Preview File', 'required'],
    // ['feature_image_id', 'Feature Image ID', ''],
    ['note_type', 'Note Type', 'required'],
    ['description', 'Description', ''],
    ['inventory_note', 'Inventory Note', ''],
    ['pin_to_top', 'Pin to top', ''],
    ['approve', 'Approve', ''],
    ['status', 'Status', '']
  ];
  protected $_validation_edit_rules = [
    ['id', 'ID', ''],
    // ['title', 'Title', 'required'],
    ['school_id', 'School', 'required|integer'],
    ['professor_id', 'Professor', 'required|integer'],
    ['class_id', 'Class', 'required|integer'],
    // ['textbook_id', 'ISBN', 'required|integer'],
    ['word_count', 'Word Count', 'required'],
    ['year', 'Year', 'required'],
    // ['isbn', 'ISBN', ''],
    // ['paypal_email', 'Paypal Email', 'required'],
    // ['file', 'File', 'required'],
    // ['file_id', 'File ID', ''],
    // ['feature_image', 'Feature Image', 'required'],
    // ['feature_image_id', 'Feature Image ID', ''],
    ['note_type', 'Note Type', 'required'],
    ['description', 'Description', ''],
    ['inventory_note', 'Inventory Note', ''],
    ['pin_to_top', 'Pin to top', ''],
    ['approve', 'Approve', ''],
    ['status', 'Status', '']
  ];
  protected $_validation_messages = [];

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * If you need to modify payload before create, overload this function
   *
   * @param mixed $data
   * @return mixed
   */
  protected function _pre_create_processing($data)
  {

    return $data;
  }

  /**
   * If you need to modify payload before edit, overload this function
   *
   * @param mixed $data
   * @return mixed
   */
  protected function _post_edit_processing($data)
  {

    return $data;
  }

  /**
   * Allow user to add extra counting condition so user don't have to change main function
   *
   * @param mixed $parameters
   * @return $db
   */
  protected function _custom_counting_conditions(&$db)
  {

    return $db;
  }

  public function note_type_mapping()
  {
    return [
      1 => 'Outline',
      2 => 'Lecture Notes'
    ];
  }

  public function pin_to_top_mapping()
  {
    return [
      0 => 'No',
      1 => 'Yes'
    ];
  }

  public function approve_mapping()
  {
    return [
      1 => 'Yes',
      0 => 'No'
    ];
  }

  public function status_mapping()
  {
    return [
      1 => 'Active',
      0 => 'Inactive',
      2 => 'Removed'
    ];
  }

  // frontend functions
  public function get_all_active_items_list_fe($search_term = '', $school_id = '', $professor_id = '', $textbook_id = '', $class_id = '', $isbn = '', $year = '', $order_by = '', $direction = '', $offset = '', $limit = '')
  {
    $this->db->from('inventory');
    $this->db->join('school', 'inventory.school_id = school.id', 'LEFT');
    $this->db->join('professor', 'inventory.professor_id = professor.id', 'LEFT');
    $this->db->join('textbook', 'inventory.textbook_id = textbook.id', 'LEFT');
    $this->db->join('classes', 'inventory.class_id = classes.id', 'LEFT');
    $this->db->join('user', 'inventory.user_id = user.id', 'LEFT');
    $this->db->join('review', 'inventory.id = review.inventory_id', 'LEFT');

    $this->db->select('inventory.*,sum(review.rating) as rating,count(review.id) as rating_count,user.first_name ,school.name AS school_name,classes.name AS class_name,textbook.name AS textbook_name,textbook.isbn AS textbook_isbn,professor.name AS professor_name');
    $this->db->where('inventory.status', 1);
    $this->db->where('school.status', 1);
    $this->db->group_by('inventory.id');


    if ($order_by && $direction) {
      if (in_array($order_by, ['school_id', 'isbn', 'class_id', 'professor_id', 'textbook_id', 'year', 'word_count', 'note_type'])) {
        $this->db->order_by($order_by, $direction);
      } else if ($order_by == 'seller') {
        $this->db->order_by('user.first_name', $direction);
      } else if ($order_by == 'review') {
        $this->db->order_by('rating', $direction);
      }
    } else {
      $this->db->order_by('inventory.created_at', 'DESC');
    }

    if ($search_term) {
      $query = $this->db->where(' (inventory.title like  "%' . $search_term . '%"  or 
            classes.name like  "%' . $search_term . '%" or
            school.name like  "%' . $search_term . '%" or 
            textbook.name like  "%' . $search_term . '%" or 
            professor.name like  "%' . $search_term . '%" or
            inventory.year like "%' . $search_term . '%")');
    }

    if ($school_id) {
      $query = $this->db->where('inventory.school_id', $school_id);
    }

    if ($professor_id) {
      $query = $this->db->where('inventory.professor_id', $professor_id);
    }

    if ($textbook_id) {
      $query = $this->db->where('inventory.textbook_id', $textbook_id);
    }
    if ($isbn) {
      $query = $this->db->where('inventory.isbn', $isbn);
    }
    if ($year) {
      $query = $this->db->where('inventory.year', $year);
    }

    if ($class_id) {
      $query = $this->db->where('inventory.class_id', $class_id);
    }

    if ($limit) {
      $this->db->limit($limit, $offset);
    }

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return $query->result();
    }
    return FALSE;
  }



  public function get_current_user_order_count($user_id)
  {
    return  $this->db->select('count(id) as order_count,inventory_id')->from('order')->where('status', 1)->where('sale_user_id', $user_id)->group_by('inventory_id')->get()->result_array();
  }
  public function get_current_user_refunded_count($user_id)
  {
    return  $this->db->select('count(id) as order_count,inventory_id')->from('order')->where('status', 2)->where('sale_user_id', $user_id)->group_by('inventory_id')->get()->result_array();
  }
  public function get_all_active_items_by_school_list_fe($school_id, $offset = '', $limit = '')
  {
    $this->db->from('inventory');
    $this->db->join('school', 'inventory.school_id = school.id', 'BOTH');

    $this->db->select('inventory.*, school.id AS school_id, school.name AS school_name');

    $this->db->where('inventory.school_id', $school_id);

    $this->db->where('inventory.status', 1);
    $this->db->where('school.status', 1);

    $this->db->order_by('school.created_at', 'DESC');
    $this->db->order_by('inventory.created_at', 'DESC');

    if ($limit != '') {
      $this->db->limit($limit, $offset);
    }

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      return $query->result();
    }
    return FALSE;
  }

  public function get_item_details_fe($id)
  {
    $this->db->select('inventory.*, school.id AS school_id, school.name AS school_name');
    $this->db->from('inventory');

    $this->db->join('school', 'inventory.school_id = school.id');

    $this->db->where('inventory.id', $id);
    $this->db->where('inventory.status', 1);
    $this->db->where('school.status', 1);

    $data = $this->db->get()->row();
    // die($this->db->last_query());

    if ($data) {
      return $data;
    }
    return FALSE;
  }



  /**
   * Count number of model
   *
   * @access public
   * @param mixed $parameters
   * @return integer $result
   */
  public function count($parameters)
  {
    $this->db->select('user.first_name, user.last_name, credential.email, inventory.* ');
    if (!empty($parameters)) {
      foreach ($parameters as $key => $value) {
        if (is_numeric($key) && strlen($value) > 0) {
          $this->db->where($value);
          continue;
        }

        if ($key === NULL && $value === NULL) {
          continue;
        }

        if (!is_null($value)) {
          if (is_numeric($value)) {
            $this->db->where($key, $value);
            continue;
          }

          if (is_string($value)) {
            $this->db->like($key, $value);
            continue;
          }

          $this->db->where($key, $value);
        }
      }
    }

    $this->db->join('user', 'inventory.user_id = user.id', 'LEFT');
    $this->db->join('credential', 'user.id = credential.user_id', 'LEFT');


    $this->_custom_counting_conditions($this->db);
    $this->db->from($this->_table);
    return $this->db->count_all_results();
  }

  /**
   * Get paginated model
   *
   * @access public
   * @param integer $page default 0
   * @param integer $limit default 10
   * @return array
   */
  public function get_paginated($page = 0, $limit = 10, $where = [], $order_by = '', $direction = 'ASC')
  {
    $this->db->limit($limit, $page);

    if ($order_by === '') {
      $order_by = $this->_primary_key;
    }
    $this->db->select('user.first_name, user.last_name, credential.email, inventory.* ');
    $this->db->order_by($this->clean_alpha_num_field($order_by), $this->clean_alpha_field($direction));

    if (!empty($where)) {
      foreach ($where as $field => $value) {
        if (is_numeric($field) && strlen($value) > 0) {
          $this->db->where($value);
          continue;
        }

        if ($field === NULL && $value === NULL) {
          continue;
        }

        if ($value !== NULL) {
          if (is_numeric($value)) {
            $this->db->where($field, $value);
            continue;
          }

          if (is_string($value)) {
            $this->db->like($field, $value);
            continue;
          }

          $this->db->where($field, $value);
        }
      }
    }

    $this->db->join('user', 'inventory.user_id = user.id', 'LEFT');
    $this->db->join('credential', 'user.id = credential.user_id', 'LEFT');

    $query = $this->db->get($this->_table);
    $result = [];

    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $result[] = $row;
      }
    }

    return $result;
  }
}
