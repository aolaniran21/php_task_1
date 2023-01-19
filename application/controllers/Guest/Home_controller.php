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
class Home_controller extends Manaknight_controller
{

  public $_data = [
    'error'   => '',
    'success' => ''
  ];


  protected $_flash_error = [
    'error'   => '',
    'success' => ''
  ];

  public function __construct()
  {
    parent::__construct();
    $this->load->database();

    // load services
    $this->load->library('helpers_service');
    $this->load->library('stripe_helper_service');
    //  $this->load->library('stevesie_api_helper_service');
    //  $this->load->library('sale_detail_api_service');


    $this->load->model('school_model');
    $this->load->model('content_model');
    $this->load->model('professor_model');
    $this->load->model('textbook_model');
    $this->load->model('classes_model');

    $this->load->model('inventory_model');

    // $this->load->library('helpers_service');

    $this->_data['layout_clean_mode'] = FALSE;

    /**
     * social links
     */
    $facebook_link  = '#';
    $twitter_link   = '#';
    $instagram_link = '#';

    $this->_data['facebook_link']  = $facebook_link;
    $this->_data['twitter_link']   = $twitter_link;
    $this->_data['instagram_link'] = $instagram_link;
  }

  public function index($offset = 0)
  {

    $content = $this->content_model->get_all(['status' => 1]);
    $this->_data['content'] = [];
    if ($content) {
      $data = [];
      foreach ($content as $key => $value) {
        $data[$value->content_name] = $value;
      }
      $this->_data['content']  = $data;
    }
    $meta_data = $this->db->select('content,content_name')->from('content')->where('content_name', 'home_page_meta_title')
      ->or_where('content_name', 'home_page_meta_description')->get()->result_array();
    if ($meta_data) {
      $meta_data = array_column($meta_data, 'content', 'content_name');
      $this->_data['meta']['title'] = $meta_data['home_page_meta_title'];
      $this->_data['meta']['desc'] = $meta_data['home_page_meta_description'];
    }
    $this->_data['active'] = 'home';
    $this->_render('Guest/Home', $this->_data);
  }

  public function get_all_categories()
  {
    $data = $this->school_model->get_all(['status' => 1]);

    if ($data) {
      echo json_encode($data);
      exit();
    }
    echo json_encode(FALSE);
    exit();
  }

  public function load_items_by_school_select()
  {
    $school_id = $this->input->get('school_id');

    if ($school_id == 0) {
      $output = [
        'error'  => TRUE,
        'status' => 0,
        'msg'    => 'all'
      ];
      echo json_encode($output);
      exit();
    }

    $items = $this->inventory_model->get_all(['school_id' => $school_id]);

    $school_data = $this->school_model->get($school_id);

    if (!empty($items)) {
      $output = [
        'success'     => TRUE,
        'status'      => 200,
        'data'        => $items,
        'school_name' => $school_data->name
      ];
      echo json_encode($output);
      exit();
    } else {
      $output = [
        'error'  => TRUE,
        'status' => 0,
        'msg'    => 'No items found for: ' . $school_data->name . ' school.'
      ];
      echo json_encode($output);
      exit();
    }
  }

  public function item_details($id)
  {
    $item_details = $this->inventory_model->get_item_details_fe($id);

    if (!empty($item_details)) {
      $this->_data['item_details'] = $item_details;
    }

    $school_id = $item_details->school_id;

    $related_items = $this->inventory_model->get_all(['school_id' => $school_id]);

    if (!empty($related_items)) {
      $this->_data['related_items'] = $related_items;
    }

    // echo '<pre>';print_r($related_items);die();

    $this->_data['active'] = 'item_details';
    $this->_render('Guest/Item_details', $this->_data);
  }

  public function about()
  {
    $this->_data['active'] = 'about';
    $this->_render('Guest/About', $this->_data);
  }

  public function contact()
  {
    if ($this->input->post('submit_btn')) {

      if ($this->input->post('email') && $this->input->post('message')) {

        $email = htmlentities($this->input->post('email'));
        // $name = htmlentities($this->input->post('name'));
        $message = htmlentities($this->input->post('message'));


        $regex = '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/';
        if (!preg_match($regex, $email)) {
          $this->_data['status_'] = 'error';
          $this->_data['status_msg'] = 'Invalid Email';
        } else {

          $data = array(
            'name' => '',
            'email' => $email,
            'message' => $message,
            'created_at' => date('Y-m-d')
          );

          $this->db->insert('contact_us', $data);

          $this->load->model('email_model');
          $this->load->library('mail_service');
          $this->mail_service->set_adapter('smtp');
          $from_email = $this->config->item('from_email');

          $this->mail_service->send($from_email, $from_email, "Contact Us", $message, $email);

          $this->_data['status_'] = 'success';
          $this->_data['status_msg'] = 'Form submitted successfully.';
        }
      } else {
        $this->_data['status_'] = 'error';
        $this->_data['status_msg'] = 'Fill All required Fields';
      }
    }

    $this->_data['meta'] = [
      'title' => '',
      'desc' => '',
    ];

    $meta_data = $this->db->select('content,content_name')->from('content')->where('content_name', 'contact_page_meta_title')
      ->or_where('content_name', 'contact_page_meta_description')->get()->result_array();
    if ($meta_data) {
      $meta_data = array_column($meta_data, 'content', 'content_name');
      $this->_data['meta']['title'] = $meta_data['contact_page_meta_title'];
      $this->_data['meta']['desc'] = $meta_data['contact_page_meta_description'];
    }

    $this->_data['active'] = 'contact';
    $this->_render('Guest/Contact', $this->_data);
  }
  public function privacy_policy()
  {
    $this->_data['active'] = 'privacy_policy';
    $this->_data['data'] = $this->db->select('*')->from('content')->where('content_name', 'privacy_policy')->get()->row_array();
    $this->_render('Guest/Privacy_policy', $this->_data);
  }
  public function buy($offset = 0)
  {
    // if($this->session->userdata('user_id'))
    // { 
    //     //create setup intent used in js
    //     $stripe = new \Stripe\StripeClient(
    //         $this->config->item('stripe_secret_key')
    //     );
    //     $this->_data['clientSecret'] = $stripe->setupIntents->create([
    //         'payment_method_types' => ['card'],
    //     ])->client_secret;
    // }

    $this->_data['stripe_client']  = $this->config->item('stripe_publish_key');
    //get all school data

    if ($this->input->get('school_id', TRUE)) {
      $this->_data['school_data']  = $this->school_model->get_all(['status' => 1, 'id' => $this->input->get('school_id')]);
    }


    if ($this->input->get('professor_id', TRUE)) {
      $this->_data['professor_data']  = $this->professor_model->get_all(['status' => 1, 'id' => $this->input->get('professor_id')]);
    }


    if ($this->input->get('class_id', TRUE)) {
      $this->_data['classes_data']  = $this->classes_model->get_all(['status' => 1, 'id' => $this->input->get('class_id')]);
    }


    if ($this->input->get('isbn', TRUE)) {
      $this->_data['textbook_data']  = $this->db->select('distinct(isbn)')->from('inventory')->where('isbn', $this->input->get('isbn', TRUE))->get()->result_array();
    }
    if ($this->input->get('year', TRUE)) {
      $this->_data['year']  = $this->db->select('year')->from('inventory')->where('year', $this->input->get('year', TRUE))->get()->result_array();
    }





    $search_term    = $this->input->get('search_term', TRUE);
    $school_id      = $this->input->get('school_id', TRUE);
    $professor_id   = $this->input->get('professor_id', TRUE);
    $textbook_id    = $this->input->get('textbook_id', TRUE);
    $isbn           = $this->input->get('isbn', TRUE);
    $year           = $this->input->get('year', TRUE);
    $class_id       = $this->input->get('class_id', TRUE);
    $order_by       = $this->input->get('order_by', TRUE);
    $direction      = $this->input->get('direction', TRUE);


    // if($school_id || $professor_id || $textbook_id || $class_id || $search_term)
    // {
    // for pagination
    $this->load->library('pagination');

    $rows_data = $this->inventory_model->get_all_active_items_list_fe($search_term, $school_id, $professor_id, $textbook_id, $class_id, $isbn, $year, $order_by, $direction);

    $total_rows = 0;
    if (!empty($rows_data)) {
      $total_rows = count($rows_data);
    }
    $limit = 10;

    // $offset = $offset * $limit ;

    if ($offset != 0 or $offset != "") {
      $offset = ($offset - 1) * $limit;
    }


    $this->pagination->initialize([
      'reuse_query_string' => TRUE,
      'base_url'           => base_url() . '/buy',
      'total_rows'         => $total_rows,
      'per_page'           => $limit,
      'num_links'          => 2,
      'full_tag_open'      => '<ul class="pagination pagination-lg justify-content-center">',
      'full_tag_close'     => '</ul>',
      'attributes'         => ['class' => 'page-link'],
      'first_link'         => FALSE,
      'last_link'          => FALSE,
      'use_page_numbers'   => TRUE,
      'first_tag_open'     => '<li class="page-item">',
      'first_tag_close'    => '</li>',
      'prev_link'          => '&laquo',
      'prev_tag_open'      => '<li class="page-item">',
      'prev_tag_close'     => '</li>',
      'next_link'          => '&raquo',
      'next_tag_open'      => '<li class="page-item">',
      'next_tag_close'     => '</li>',
      'last_tag_open'      => '<li class="page-item">',
      'last_tag_close'     => '</li>',
      'cur_tag_open'       => '<li class="page-item active"><a href="#" class="page-link">',
      'cur_tag_close'      => '<span class="sr-only">(current)</span></a></li>',
      'num_tag_open'       => '<li class="page-item">',
      'num_tag_close'      => '</li>'
    ]);

    $items = $this->inventory_model->get_all_active_items_list_fe($search_term, $school_id, $professor_id, $textbook_id, $class_id, $isbn, $year, $order_by, $direction, $offset, $limit);

    if (!empty($items)) {
      $this->_data['links'] = $this->pagination->create_links();
      $this->_data['items'] = $items;
    }

    // }
    //notes amount 
    $notes_amount = $this->db->select('value')->from('setting')->where('key', 'fixed_paper_amount')->get()->row_array();
    if ($notes_amount) {
      $this->_data['notes_amount'] =  $notes_amount['value'];
    } else {
      $this->_data['notes_amount']  = 50;
    }

    $this->_data['user_downloaded_files'] = [];
    if ($this->session->userdata('user_id')) {
      $this->_data['user_downloaded_files'] = $this->db->select('inventory_id')->from('order')->where('purchase_user_id', $this->session->userdata('user_id'))->get()->result_array();
      if ($this->_data['user_downloaded_files']) {
        $this->_data['user_downloaded_files'] = array_column($this->_data['user_downloaded_files'], 'inventory_id');
      }
    }

    $this->_data['meta'] = [
      'title' => '',
      'desc'  => '',
    ];

    $meta_data = $this->db->select('content,content_name')->from('content')->where('content_name', 'buy_page_meta_title')->or_where('content_name', 'buy_page_meta_description')->get()->result_array();
    if ($meta_data) {
      $meta_data = array_column($meta_data, 'content', 'content_name');
      $this->_data['meta']['title'] = $meta_data['buy_page_meta_title'];
      $this->_data['meta']['desc']  = $meta_data['buy_page_meta_description'];
    }

    $this->_data['active'] = 'buy';
    $this->_render('Guest/Buy', $this->_data);
  }







  public function sell()
  {
    $content = $this->content_model->get_all(['status' => 1]);
    $this->_data['content'] = [];
    if ($content) {
      $data = [];
      foreach ($content as $key => $value) {
        $data[$value->content_name] = $value;
      }
      $this->_data['content']  = $data;
    }

    // //get all school data
    // $this->_data['school_data']     = $this->school_model->get_all(['status' => 1]); 
    // $this->_data['professor_data']  = $this->professor_model->get_all(['status' => 1]); 
    // $this->_data['classes_data']    = $this->classes_model->get_all(['status' => 1]); 
    // $this->_data['textbook_data']   = $this->textbook_model->get_all(['status' => 1]);



    $data = $this->db->select('paypal_email')->from('user')->where('id', $this->session->userdata('user_id'))->get()->row_array();

    if (isset($data['paypal_email'])) {
      $this->_data['paypal_email']  = $data['paypal_email'];
    }



    $this->_data['meta'] = [
      'title' => '',
      'desc' => '',
    ];

    $meta_data = $this->db->select('content,content_name')->from('content')->where('content_name', 'sell_page_meta_title')->or_where('content_name', 'sell_page_meta_description')->get()->result_array();

    if ($meta_data) {
      $meta_data = array_column($meta_data, 'content', 'content_name');
      $this->_data['meta']['title'] = $meta_data['sell_page_meta_title'];
      $this->_data['meta']['desc'] = $meta_data['sell_page_meta_description'];
    }

    $this->_data['active'] = 'sell';

    $this->_render('Guest/Sell', $this->_data);
  }


  public function checkout()
  {

    if (!$this->session->userdata('user_id')) {

      echo json_encode(['status' => 'error', 'message' => 'You Have to Login First']);
      exit;
    }

    $user_id = $this->session->userdata('user_id');
    // stripe_helper_service
    $card_number = $this->input->post('card_number', TRUE);
    $exp_month   = $this->input->post('exp_month', TRUE);
    $exp_year    = $this->input->post('exp_year', TRUE);
    $cvc         = $this->input->post('cvc', TRUE);
    $inv_id      = $this->input->post('inv_id', TRUE);

    $new_card_last4 = substr($card_number, 12);

    // add card
    $this->stripe_helper_service->set_config($this->config);
    $response = $this->stripe_helper_service->create_stripe_token($card_number, $exp_month, $exp_year, $cvc);

    if (isset($response['success'])) {
      $stripe_token_id = $response['token']->id;

      $this->stripe_helper_service->set_config($this->config);
      $this->stripe_helper_service->set_user_model($this->user_model);
      // pass token_id to assign card to user
      $res_card_data = $this->stripe_helper_service->add_new_card($stripe_token_id, $user_id);

      if (isset($res_card_data['success'])) {
        $stripe_card_id   = $res_card_data['card_data']->id;
        $stripe_brand     = $res_card_data['card_data']->brand;
        $stripe_exp_month = $res_card_data['card_data']->exp_month;
        $stripe_exp_year  = $res_card_data['card_data']->exp_year;
        $stripe_last4     = $res_card_data['card_data']->last4;

        // store the card id with the associated user
        $check_new_card = $this->user_card_model->create([
          'is_default'     => 0,
          'user_id'        => $user_id,
          'stripe_card_id' => $stripe_card_id,
          'brand'          => $stripe_brand,
          'exp_month'      => $stripe_exp_month,
          'exp_year'       => $stripe_exp_year,
          'last4'          => $stripe_last4,
          'cvc'            => $cvc,
          'status'         => 1
        ]);
      } else {
        // when user do not have the user->stripe_id
        echo json_encode(['status' => 'error', 'message' => 'Error']);
        exit;
      }
    } else {
      // when new card validation failed
      echo json_encode(['status' => 'error', 'message' => 'Error You card is invalid']);
      exit;
    }
  }


  public function autocomplete()
  {
    $html = '';
    if ($this->input->post('keyword')) {

      $result = $this->db->query("
        SELECT name FROM school WHERE name LIKE '%" . htmlentities($this->input->post('keyword')) . "%'
        UNION ALL
        SELECT name  FROM classes WHERE name LIKE '%" . htmlentities($this->input->post('keyword')) . "%'
        UNION ALL
        SELECT name  FROM professor WHERE name LIKE '%" . htmlentities($this->input->post('keyword')) . "%'
        UNION ALL
        SELECT name  FROM textbook WHERE name LIKE '%" . htmlentities($this->input->post('keyword')) . "%'
        UNION ALL
        SELECT title as name  FROM inventory WHERE title LIKE '%" . htmlentities($this->input->post('keyword')) . "%'
        ")->result_array();




      if (!empty($result)) {
        $html .= '<ul id="country-list" style="z-index:1000;width:91%;max-height:200px;overflow-y:scroll;">';
        foreach ($result as $key => $value) {
          $html .= '<li onClick="selectCountry(`' . $value["name"] . '`);">' . $value["name"] . '</li>';
        }
        $html .= '</ul>';
      }
    }
    echo json_encode(['html' => $html]);
    exit();
  }
  public function preview($id)
  {

    $this->_data['active'] = 'Preview';
    $this->_data['data'] = $this->inventory_model->get_by_fields(['status' => 1, 'id' => $id]);
    $this->_render('Guest/Preview', $this->_data);
  }
  public function terms_and_conditions()
  {
    // $terms = $this->terms_and_conditions_model->get(1);

    // if (!empty($terms))
    // {
    //   $this->_data['terms'] = $terms;
    // }
    $this->_data['data'] = $this->db->select('*')->from('content')->where('content_name', 'terms_conditon')->get()->row_array();
    $this->_data['active'] = 'terms_and_conditions';
    $this->_render('Guest/Terms_and_conditions', $this->_data);
  }

  protected function _render($template, $_data)
  {
    $this->_data['page_section'] = $template;

    $this->load->view('Guest/Header', $this->_data);
    $this->load->view($template, $this->_data);
    $this->load->view('Guest/Footer', $this->_data);
  }

  public function dd()
  {
    $output = '';

    echo "<pre>";
    print_r($output);
    die();
  }

  public function get_review()
  {
    if (isset($_POST['id'])) {

      $data = $this->db->select('r.*,u.image,u.first_name')->from('review r')->join('user u', 'r.user_id=u.id')->where('inventory_id', $_POST['id'])->where('r.status', 1)->order_by('r.created_at')->get()->result_array();
      echo json_encode(['status' => true, 'data' => $data]);
      exit;
    }
    echo json_encode(['status' => false]);
    exit;
  }
}
