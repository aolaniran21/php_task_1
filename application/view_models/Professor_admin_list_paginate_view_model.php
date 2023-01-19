<?php defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2021*/
/**
 * Professor List Paginate View Model
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Professor_admin_list_paginate_view_model
{
    protected $_library;
    protected $_base_url = '';
    protected $_heading = 'Professor';
    protected $_total_rows = 0;
    protected $_format_layout = '';
    protected $_per_page = 10;
    protected $_page;
    protected $_num_links = 5;
    protected $_column = ['Action', 'ID', 'Name', 'Status'];
    protected $_field_column = ['', 'id', 'name', 'status'];
    protected $_list = [];
    protected $_links = '';
    protected $_sort_base_url = '';
    protected $_order_by = '';
    protected $_sort = '';
    protected $_entity;

    public function __construct($entity, $library, $base_url)
    {
        $this->_entity = $entity;
        $this->_library = $library;
        $this->_base_url = str_replace('/0', '/', $base_url);
    }

    /**
     * set_total_rows function
     *
     * @param integer $total_rows
     * @return void
     */
    public function set_total_rows($total_rows)
    {
        $this->_total_rows = $total_rows;
    }

    /**
     * set_per_page function
     *
     * @param integer $per_page
     * @return void
     */
    public function set_per_page($per_page)
    {
        $this->_per_page = $per_page;
    }
    /**
     * format_layout function
     *
     * @param integer $_format_layout
     * @return void
     */
    public function set_format_layout($_format_layout)
    {
        $this->_format_layout = $_format_layout;
    }

    /**
     * set_order_by function
     *
     * @param string $order_by
     * @return void
     */
    public function set_order_by($order_by)
    {
        $this->_order_by = $order_by;
    }

    /**
     * set_sort function
     *
     * @param string $sort
     * @return void
     */
    public function set_sort($sort)
    {
        $this->_sort = $sort;
    }

    /**
     * set_sort_base_url function
     *
     * @param string $sort_base_url
     * @return void
     */
    public function set_sort_base_url($sort_base_url)
    {
        $this->_sort_base_url = $sort_base_url;
    }

    /**
     * set_page function
     *
     * @param integer $page
     * @return void
     */
    public function set_page($page)
    {
        $this->_page = $page;
    }

    /**
     * set_list function
     *
     * @param mixed $list
     * @return void
     */
    public function set_list($list)
    {
        $this->_list = $list;
    }

    /**
     * get_total_rows function
     *
     * @return integer
     */
    public function get_total_rows()
    {
        return $this->_total_rows;
    }

    /**
     * get_format_layout function
     *
     * @return integer
     */
    public function get_format_layout()
    {
        return $this->_format_layout;
    }

    /**
     * get_per_page function
     *
     * @return integer
     */
    public function get_per_page()
    {
        return $this->_per_page;
    }

    /**
     * get_page function
     *
     * @return integer
     */
    public function get_page()
    {
        return $this->_page;
    }

    /**
     * num_links function
     *
     * @return integer
     */
    public function get_num_links()
    {
        return $this->_num_links;
    }

    /**
     * set_order_by function
     *
     */
    public function get_order_by()
    {
        return $this->_order_by;
    }

    /**
     * get_field_column function
     *
     */
    public function get_field_column()
    {
        return $this->_field_column;
    }

    /**
     * set_sort function
     *
     */
    public function get_sort()
    {
        return $this->_sort;
    }

    /**
     * set_sort_base_url function
     *
     */
    public function get_sort_base_url()
    {
        return $this->_sort_base_url;
    }

    /**
     * num_pages function
     *
     * @return integer
     */
    public function get_num_page()
    {
        $num = ceil($this->_total_rows / $this->_per_page);
        return ($num > 0) ? (int) $num : 1;
    }

    public function image_or_file($file)
    {
        $images = ['.jpg', '.png', '.gif', '.jpeg', '.bmp'];
        $is_image = FALSE;
        if ($this->strposa($file, $images)) {
            return "<div class='mkd-image-container'><img class='img-fluid' src='{$file}' onerror=\"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\"/></div>";
        }

        return "<a href='{$file}' target='__blank'>{$file}</a>";
    }

    /**
     * Strpos for array
     *
     * @param string $haystack
     * @param array $needle
     * @return boolean
     */
    private function strposa($haystack, $needle)
    {
        foreach ($needle as $query) {
            if (strpos($haystack, $query) !== FALSE) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * get_list function
     *
     * @return mixed
     */
    public function get_list()
    {
        $this->_library->initialize([
            'reuse_query_string' => TRUE,
            'base_url' => $this->_base_url,
            'total_rows' => $this->_total_rows,
            'per_page' => $this->_per_page,
            'num_links' => $this->_num_links,
            'full_tag_open' => '<ul class="pagination justify-content-end">',
            'full_tag_close' => '</ul>',
            'attributes' => ['class' => 'page-link'],
            'first_link' => FALSE,
            'last_link' => FALSE,
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close' => '</li>',
            'prev_link' => '&laquo',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            'next_link' => '&raquo',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
            'cur_tag_close' => '<span class="sr-only">(current)</span></a></li>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>'
        ]);
        return $this->_list;
    }

    /**
     * get_links function
     *
     * @return mixed
     */
    public function get_links()
    {
        $this->_links = $this->_library->create_links();
        return $this->_links;
    }

    /**
     * set_heading function
     *
     * @param string $heading
     * @return void
     */
    public function set_heading($heading)
    {
        $this->_heading = $heading;
    }

    /**
     * get_heading function
     *
     * @return string
     */
    public function get_heading()
    {
        return $this->_heading;
    }

    public function set_column($column)
    {
        $this->_column = $column;
    }

    public function get_column()
    {
        return $this->_column;
    }

    public function timeago($date)
    {
        $timestamp = strtotime($date);

        $strTime = array('second', 'minute', 'hour', 'day', 'month', 'year');
        $length = array('60', '60', '24', '30', '12', '10');

        $currentTime = time();
        if ($currentTime >= $timestamp) {
            $diff  = time() - $timestamp;

            for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
                $diff = $diff / $length[$i];
            }

            $diff = round($diff);
            return $diff . ' ' . $strTime[$i] . '(s) ago ';
        }
    }

    public function time_default_mapping()
    {
        $results = [];
        for ($i = 0; $i < 24; $i++) {
            for ($j = 0; $j < 60; $j++) {
                $hour = ($i < 10) ? '0' . $i : $i;
                $min = ($j < 10) ? '0' . $j : $j;
                $results[($i * 60) + $j] = "$hour:$min";
            }
        }
        return $results;
    }

    public function get_query_parameter()
    {
        $get_parameter = $_GET;
        $blacklist = array(
            'order_by',
            'direction'
        );
        $query_list = array();
        foreach ($get_parameter as $key => $value) {
            if (!in_array($key, $blacklist)) {
                $query_list[] = "$key=$value";
            }
        }
        return "&" . implode("&", $query_list);
    }


    public function get_id()
    {
        return $this->_id;
    }

    public function set_id($id)
    {
        $this->_id = $id;
    }

    public $_id = NULL;

    public function get_name()
    {
        return $this->_name;
    }

    public function set_name($name)
    {
        $this->_name = $name;
    }

    public $_name = NULL;

    public function get_status()
    {
        return $this->_status;
    }

    public function set_status($status)
    {
        $this->_status = $status;
    }

    public $_status = NULL;

    public function status_mapping()
    {
        return $this->_entity->status_mapping();
    }

    public function to_json()
    {
        $list = $this->get_list();

        $clean_list = [];

        foreach ($list as $key => $value) {
            $list[$key]->status = $this->status_mapping()[$value->status];
            $clean_list_entry = [];
            $clean_list_entry['id'] = $list[$key]->id;
            $clean_list_entry['name'] = $list[$key]->name;
            $clean_list_entry['status'] = $list[$key]->status;
            $clean_list[] = $clean_list_entry;
        }

        return [
            'page' => $this->get_page(),
            'num_page' => $this->get_num_page(),
            'num_item' => $this->get_total_rows(),
            'item' => $clean_list
        ];
    }

    public function to_csv()
    {
        $list = $this->get_list();

        $clean_list = [];

        foreach ($list as $key => $value) {
            $list[$key]->status = $this->status_mapping()[$value->status];
            $clean_list_entry = [];
            $clean_list_entry['id'] = $list[$key]->id;
            $clean_list_entry['name'] = $list[$key]->name;
            $clean_list_entry['status'] = $list[$key]->status;
            $clean_list[] = $clean_list_entry;
        }

        $columns = $this->get_column();
        $columns = array_diff($columns, ['Action']);
        $csv = implode(",", $columns) . "\n";
        $fields = array_filter($this->get_field_column());
        foreach ($clean_list as $row) {
            $row_csv = [];
            foreach ($row as $key => $column) {
                if (in_array($key, $fields)) {
                    $row_csv[] = '"' . $column . '"';
                }
            }
            $csv = $csv . implode(',', $row_csv) . "\n";
        }
        return $csv;
    }
}
