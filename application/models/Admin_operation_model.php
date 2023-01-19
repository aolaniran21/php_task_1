<?php defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2021*/
/**
 * Admin_operation_model Model
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_operation_model extends Manaknight_Model
{
    protected $_table = 'admin_operation';
    protected $_primary_key = 'id';
    protected $_return_type = 'array';
    protected $_allowed_fields = [
        'id',
        'user_id',
        'action',
        'detail',
        'last_ip',
        'user_agent',

    ];
    protected $_label_fields = [
        'ID', 'User', 'Action', 'Detail', 'Last IP', 'User Agent',
    ];
    protected $_use_timestamps = TRUE;
    protected $_created_field = 'created_at';
    protected $_updated_field = 'updated_at';
    protected $_validation_rules = [
        ['id', 'ID', ''],
        ['user_id', 'User', 'required|integer'],
        ['action', 'Action', 'required|max[50]'],
        ['detail', 'Detail', 'required'],
        ['last_ip', 'Last IP', 'required'],
        ['user_agent', 'User Agent', 'required'],

    ];
    protected $_validation_edit_rules = [
        ['id', 'ID', ''],
        ['user_id', 'User', ''],
        ['action', 'Action', ''],
        ['detail', 'Detail', ''],
        ['last_ip', 'Last IP', ''],
        ['user_agent', 'User Agent', ''],

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
        $data['last_ip'] = $this->get_ip();
        $data['last_ip'] = $this->get_user_agent();

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


    function get_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function get_user_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function get_user($where)
    {
        return $this->_join('user', 'user_id', $where, []);
    }

    public function get_user_paginated($page, $limit, $where, $order_by, $direction)
    {
        return $this->_join_paginate('user', 'user_id', $where, $page, $limit, $order_by, $direction, []);
    }

    public function count_paginated($where)
    {
        return count($this->_join('user', 'user_id', $where, []));
    }

    public function getMemberAccountData()
    {

        $data = [
            'month' => 0,
            'week' => 0,
            'year' => 0,
            'total' => 0
        ];

        $result = $this->db->select('count(*) as count')->from('user')->get()->row_array();
        if ($result) {
            $data['total'] = $result['count'];
        }

        $result = $this->db->select('count(id) as count')->from('user')->where('MONTH(created_at) =', date('n'))->where('YEAR(created_at)', date('Y'))->get()->row_array();
        if ($result) {
            $data['month'] = $result['count'];
        }

        $result = $this->db->select('count(id) as count')->from('user')->where('YEAR(created_at) =', date('Y'))->get()->row_array();
        if ($result) {
            $data['year'] = $result['count'];
        }

        $result = $this->db->select('count(id) as count')->from('user')->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)')->get()->row_array();
        if ($result) {
            $data['week'] = $result['count'];
        }


        return $data;
    }
    public function getMemberUploadData()
    {

        $data = [
            'month' => 0,
            'week' => 0,
            'year' => 0,
            'total' => 0
        ];

        $result = $this->db->select('count(id) as count')->from('inventory')->where('status', 1)->get()->row_array();
        if ($result) {
            $data['total'] = $result['count'];
        }

        $result = $this->db->select('count(id) as count')->from('inventory')->where('status', 1)->where('MONTH(created_at) =', date('n'))
            ->where('YEAR(created_at)', date('Y'))->get()->row_array();
        if ($result) {
            $data['month'] = $result['count'];
        }

        $result = $this->db->select('count(id) as count')->from('inventory')->where('status', 1)
            ->where('YEAR(created_at) =', date('Y'))->get()->row_array();
        if ($result) {
            $data['year'] = $result['count'];
        }

        $result = $this->db->select('count(id) as count')->from('inventory')->where('status', 1)
            ->where('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)')->get()->row_array();
        if ($result) {
            $data['week'] = $result['count'];
        }


        return $data;
    }
    public function getAccountsBySchool()
    {


        $result = $this->db->select('count(c.id) as count,s.name as school')->from('credential c')
            ->join('member_profile m', 'm.user_id=c.user_id')
            ->join('school s', 's.id=m.school_id', 'left')
            ->where('c.role_id', 1)
            ->group_by('m.school_id')
            ->order_by('count')
            ->limit(5)
            ->get()->result_array();


        return $result;
    }
    public function getUploadsBySchool()
    {

        $result = $this->db->select('count(i.id) as count,s.name as school')->from('inventory i')
            ->join('school s', 's.id=i.school_id')
            ->where('i.status', 1)
            ->group_by('i.school_id')
            ->order_by('count')
            ->limit(5)
            ->get()->result_array();


        return $result;
    }
}
