<?php defined('BASEPATH') or exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2021*/
/**
 * Review Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_review_controller extends Admin_controller
{
    protected $_model_file = 'review_model';
    public $_page_name = 'Review';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }



    public function index($page)
    {
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Review_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';
        $per_page_sort = $this->input->get('per_page_sort', TRUE) ?? 25;

        $this->_data['view_model'] = new Review_admin_list_paginate_view_model(
            $this->review_model,
            $this->pagination,
            '/admin/review/0'
        );
        $this->_data['view_model']->set_heading('Review');
        $this->_data['view_model']->set_id(($this->input->get('id', TRUE) != NULL) ? $this->input->get('id', TRUE) : NULL);
        $this->_data['view_model']->set_order_id(($this->input->get('order_id', TRUE) != NULL) ? $this->input->get('order_id', TRUE) : NULL);
        $this->_data['view_model']->set_user_id(($this->input->get('user_id', TRUE) != NULL) ? $this->input->get('user_id', TRUE) : NULL);
        $this->_data['view_model']->set_comment(($this->input->get('comment', TRUE) != NULL) ? $this->input->get('comment', TRUE) : NULL);
        $this->_data['view_model']->set_private_review(($this->input->get('private_review', TRUE) != NULL) ? $this->input->get('private_review', TRUE) : NULL);
        $this->_data['view_model']->set_rating(($this->input->get('rating', TRUE) != NULL) ? $this->input->get('rating', TRUE) : NULL);
        $this->_data['view_model']->set_status(($this->input->get('status', TRUE) != NULL) ? $this->input->get('status', TRUE) : NULL);

        $where = [
            'id' => $this->_data['view_model']->get_id(),
            'order_id' => $this->_data['view_model']->get_order_id(),
            'user_id' => $this->_data['view_model']->get_user_id(),
            'comment' => $this->_data['view_model']->get_comment(),
            'private_review' => $this->_data['view_model']->get_private_review(),
            'rating' => $this->_data['view_model']->get_rating(),
            'status' => $this->_data['view_model']->get_status(),


        ];

        $this->_data['view_model']->set_total_rows($this->review_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page($per_page_sort);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/review/0');
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_list($this->review_model->get_paginated(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction
        ));


        $user_id_array = $this->user_model->get_all();
        $this->_data['users'] = $user_id_array;
        if ($user_id_array) {
            $user_id_array = array_column($user_id_array, 'first_name', 'id');
        }
        // print_r($user_id_array);exit;
        if ($this->_data['view_model']->get_list()) {

            foreach ($this->_data['view_model']->get_list() as $key => &$value) {
                $value->user_id = $user_id_array[$value->user_id] ?? '';
            }
        }

        if ($format == 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="export.csv"');

            echo $this->_data['view_model']->to_csv();
            exit();
        }

        if ($format != 'view') {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Admin/Review', $this->_data);
    }

    public function reject($id)
    {
        $this->review_model->update('review', ['status' => 0], ['id' => $id]);

        $this->success('Review Rejected successfully');
        return redirect('admin/review/0', 'refresh');
    }
}
