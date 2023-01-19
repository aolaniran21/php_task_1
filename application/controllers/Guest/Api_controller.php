<?php defined('BASEPATH') || exit('No direct script access allowed');
// include_once 'Manaknight_controller.php';

/**
 * Frontend Controller to Manage all Frontend pages
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Api_controller extends Manaknight_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();


        $this->load->model('school_model');
        $this->load->model('professor_model');
        $this->load->model('textbook_model');
        $this->load->model('classes_model');
    }



    // select2 customer search 
    public function get_schools()
    {

        $custom_query = '';
        if ($this->input->POST('term')) {
            $custom_query = ' `name` LIKE "%' .  $this->input->POST('term', true)  . '%" ';
            $data_list = $this->school_model->get_all_custom_where($custom_query, ['status' => 1]);

            $response_list = array();
            foreach ($data_list as $d_key => $_data) {
                array_push($response_list, array('text' => $_data->name, 'id' => $_data->id));
            }

            $output['results'] = $response_list;
            echo json_encode($output);
            exit();
        }
    }


    // select2 customer search 
    public function get_professors()
    {

        $custom_query = '';
        if ($this->input->get('term')) {
            $custom_query = ' `name` LIKE "%' .  $this->input->get('term', true)  . '%" ';
            $data_list = $this->professor_model->get_all_custom_where($custom_query, ['status' => 1]);

            $response_list = array();
            foreach ($data_list as $d_key => $_data) {
                array_push($response_list, array('text' => $_data->name, 'id' => $_data->id));
            }

            $output['results'] = $response_list;
            echo json_encode($output);
            exit();
        }
    }



    // select2 customer search 
    public function get_courses()
    {

        $custom_query = '';
        if ($this->input->get('term')) {
            $custom_query = ' `name` LIKE "%' .  $this->input->get('term', true)  . '%" ';
            $data_list = $this->classes_model->get_all_custom_where($custom_query, ['status' => 1]);

            $response_list = array();
            foreach ($data_list as $d_key => $_data) {
                array_push($response_list, array('text' => $_data->name, 'id' => $_data->id));
            }

            $output['results'] = $response_list;
            echo json_encode($output);
            exit();
        }
    }



    // select2 customer search 
    public function get_textbooks()
    {
        if ($this->input->get('term')) {
            $custom_query = ' `isbn` LIKE "%' .  $this->input->get('term', true)  . '%" ';
            $data_list = $this->db->select('distinct(isbn)')->from('inventory')->where($custom_query)->get()->result_array();


            $response_list = array();
            foreach ($data_list as $d_key => $_data) {
                array_push($response_list, array('text' => $_data['isbn'], 'id' => $_data['isbn']));
            }

            $output['results'] = $response_list;
            echo json_encode($output);
            exit();
        }
    }
    // select2 customer search 
    public function get_years()
    {
        if ($this->input->get('term')) {
            $custom_query = ' `year` LIKE "%' .  $this->input->get('term', true)  . '%" ';
            $data_list = $this->db->select('year')->from('inventory')->where($custom_query)->get()->result_array();


            $response_list = array();
            foreach ($data_list as $d_key => $_data) {
                array_push($response_list, array('text' => $_data['year'], 'id' => $_data['year']));
            }

            $output['results'] = $response_list;
            echo json_encode($output);
            exit();
        }
    }


    // select2 customer search 
    public function update_old_orders_if_refunded()
    {
        $this->load->model('order_model');
        $this->load->model('payout_model');


        $all_order = $this->order_model->get_all(['status' => 2]);
        foreach ($all_order as $_key => $_value) {
            $this->payout_model->update('payout', ['status' => 3], ['order_id' => $_value->id]);
        }
    }
}
