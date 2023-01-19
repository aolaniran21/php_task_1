<?php

use Aws\S3\S3Client;
use function GuzzleHttp\Psr7\str;

defined('BASEPATH') || exit('No direct script access allowed');
include_once 'Member_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2021*/
/**
 * Inventory Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */




use \Stripe\Stripe;
use \Stripe\Customer;
use \Stripe\Charge;
use \Stripe\Refund;
use \Stripe\Plan;
use \Stripe\Coupon;
use \Stripe\Product;
use \Stripe\Subscription;
use \Stripe\Invoice;
use \Stripe\Error;
use \Stripe\Webhook;
use \Stripe\Source;
use \Stripe\Dispute;
use \Stripe\Token;
use \Stripe\File;
use \Stripe\Exception;
use \Stripe\Event;
use \Stripe\InvoiceItem;
use \Stripe\PaymentMethod;
use \Stripe\PaymentIntent;



class Member_inventory_controller extends Member_controller
{
  protected $_model_file = 'inventory_model';
  public $_page_name     = 'Inventory';

  public function __construct()
  {
    parent::__construct();

    $this->load->model('school_model');
    $this->load->model('professor_model');
    $this->load->model('textbook_model');
    $this->load->model('classes_model');
    $this->load->model('user_model');
    $this->load->model('inventory_model');
    $this->load->model('credential_model');
  }

  function parseWord($userDoc)
  {
    $fileHandle = fopen($userDoc, "r");
    $word_text = @fread($fileHandle, filesize($userDoc));
    $line = "";
    $tam = filesize($userDoc);
    $nulos = 0;
    $caracteres = 0;
    for ($i = 1536; $i < $tam; $i++) {
      $line .= $word_text[$i];

      if ($word_text[$i] == 0) {
        $nulos++;
      } else {
        $nulos = 0;
        $caracteres++;
      }

      if ($nulos > 1996) {
        break;
      }
    }

    //echo $caracteres;

    $lines = explode(chr(0x0D), $line);
    //$outtext = "<pre>";

    $outtext = "";
    foreach ($lines as $thisline) {
      $tam = strlen($thisline);
      if (!$tam) {
        continue;
      }

      $new_line = "";
      for ($i = 0; $i < $tam; $i++) {
        $onechar = $thisline[$i];
        if ($onechar > chr(240)) {
          continue;
        }

        if ($onechar >= chr(0x20)) {
          $caracteres++;
          $new_line .= $onechar;
        }

        if ($onechar == chr(0x14)) {
          $new_line .= "</a>";
        }

        if ($onechar == chr(0x07)) {
          $new_line .= "\t";
          if (isset($thisline[$i + 1])) {
            if ($thisline[$i + 1] == chr(0x07)) {
              $new_line .= "\n";
            }
          }
        }
      }
      //troca por hiperlink
      $new_line = str_replace("HYPERLINK", "<a href=", $new_line);
      $new_line = str_replace("\o", ">", $new_line);
      $new_line .= "\n";

      //link de imagens
      $new_line = str_replace("INCLUDEPICTURE", "<br><img src=", $new_line);
      $new_line = str_replace("\*", "><br>", $new_line);
      $new_line = str_replace("MERGEFORMATINET", "", $new_line);


      $outtext .= nl2br($new_line);
    }

    return $outtext;
  }


  public function index($page)
  {
    $this->load->library('pagination');
    include_once __DIR__ . '/../../view_models/Inventory_member_list_paginate_view_model.php';
    $session       = $this->get_session();
    $format        = $this->input->get('format', TRUE) ?? 'view';
    $order_by      = $this->input->get('order_by', TRUE) ?? '';
    $direction     = $this->input->get('direction', TRUE) ?? 'ASC';
    $per_page_sort = $this->input->get('per_page_sort', TRUE) ?? 25;

    $this->_data['view_model'] = new Inventory_member_list_paginate_view_model(
      $this->inventory_model,
      $this->pagination,
      '/member/inventory/0'
    );
    $this->_data['view_model']->set_heading('Inventory');
    $this->_data['view_model']->set_id(($this->input->get('id', TRUE) != NULL) ? $this->input->get('id', TRUE) : NULL);
    $this->_data['view_model']->set_title(($this->input->get('title', TRUE) != NULL) ? $this->input->get('title', TRUE) : NULL);
    $this->_data['view_model']->set_school_id(($this->input->get('school_id', TRUE) != NULL) ? $this->input->get('school_id', TRUE) : NULL);
    $this->_data['view_model']->set_professor_id(($this->input->get('professor_id', TRUE) != NULL) ? $this->input->get('professor_id', TRUE) : NULL);
    $this->_data['view_model']->set_class_id(($this->input->get('class_id', TRUE) != NULL) ? $this->input->get('class_id', TRUE) : NULL);
    $this->_data['view_model']->set_textbook_id(($this->input->get('textbook_id', TRUE) != NULL) ? $this->input->get('textbook_id', TRUE) : NULL);
    $this->_data['view_model']->set_word_count(($this->input->get('word_count', TRUE) != NULL) ? $this->input->get('word_count', TRUE) : NULL);
    $this->_data['view_model']->set_year(($this->input->get('year', TRUE) != NULL) ? $this->input->get('year', TRUE) : NULL);
    $this->_data['view_model']->set_status(($this->input->get('status', TRUE) != NULL) ? $this->input->get('status', TRUE) : NULL);

    $where = [
      'inventory.id'           => $this->_data['view_model']->get_id(),
      'inventory.title'        => $this->_data['view_model']->get_title(),
      'inventory.school_id'    => $this->_data['view_model']->get_school_id(),
      'inventory.professor_id' => $this->_data['view_model']->get_professor_id(),
      'inventory.class_id'     => $this->_data['view_model']->get_class_id(),
      'inventory.textbook_id'  => $this->_data['view_model']->get_textbook_id(),
      'inventory.word_count'   => $this->_data['view_model']->get_word_count(),
      'inventory.year'         => $this->_data['view_model']->get_year(),
      'inventory.status'       => $this->_data['view_model']->get_status(),
      'inventory.user_id'      => $this->session->userdata('user_id'),
    ];

    $this->_data['view_model']->set_total_rows($this->inventory_model->count($where));

    $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
    $this->_data['view_model']->set_per_page($per_page_sort);
    $this->_data['view_model']->set_order_by($order_by);
    $this->_data['view_model']->set_sort($direction);
    $this->_data['view_model']->set_sort_base_url('/member/inventory/0');
    $this->_data['view_model']->set_page($page);
    $this->_data['view_model']->set_list($this->inventory_model->get_paginated(
      $this->_data['view_model']->get_page(),
      $this->_data['view_model']->get_per_page(),
      $where,
      $order_by,
      $direction
    ));


    // load data
    $schools    = $this->school_model->get_all(['status' => 1]);
    $professors = $this->professor_model->get_all(['status' => 1]);
    $textbooks  = $this->textbook_model->get_all(['status' => 1]);
    $classes    = $this->classes_model->get_all(['status' => 1]);
    $purchase_array   = $this->inventory_model->get_current_user_order_count($this->session->userdata('user_id'));
    if ($purchase_array) {
      $purchase_array = array_column($purchase_array, 'order_count', 'inventory_id');
    }
    $refunded_array   = $this->inventory_model->get_current_user_refunded_count($this->session->userdata('user_id'));
    if ($refunded_array) {
      $refunded_array = array_column($refunded_array, 'order_count', 'inventory_id');
    }
    // print_r($this->_data['view_model']->get_list());exit;

    if ($this->_data['view_model']->get_list()) {

      //get all school data
      $school_data  = array_column($schools, 'name', 'id');
      //get all professor data
      $professor_data  = array_column($professors, 'name', 'id');
      //get all classes data
      $classes_data  = array_column($classes, 'name', 'id');
      //get all textbook data
      $textbook_data  = array_column($textbooks, 'isbn', 'id');

      foreach ($this->_data['view_model']->get_list() as $key => &$value) {
        $value->school_id = $school_data[$value->school_id];
        $value->professor_id = $professor_data[$value->professor_id];
        $value->class_id = $classes_data[$value->class_id];
        $value->textbook_id = $textbook_data[$value->textbook_id] ?? '';
        if (isset($purchase_array[$value->id]))
          $value->purchased_count = $purchase_array[$value->id] ?? 0;
        else
          $value->purchased_count = 0;

        if (isset($refunded_array[$value->id]))
          $value->refunded_count = $refunded_array[$value->id] ?? 0;
        else
          $value->refunded_count = 0;
      }
    }

    if ($format == 'csv') {
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="export.csv"');

      echo $this->_data['view_model']->to_csv();
      exit();
    }


    if (!empty($schools)) {
      $this->_data['schools'] = $schools;
    }

    if (!empty($professors)) {
      $this->_data['professors'] = $professors;
    }

    if (!empty($textbooks)) {
      $this->_data['textbooks'] = $textbooks;
    }

    if (!empty($classes)) {
      $this->_data['classes'] = $classes;
    }

    if ($format != 'view') {
      return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($this->_data['view_model']->to_json()));
    }

    return $this->render('Member/Inventory', $this->_data);
  }

  public function add()
  {
    include_once __DIR__ . '/../../view_models/Inventory_member_add_view_model.php';
    $session               = $this->get_session();
    $this->form_validation = $this->inventory_model->set_form_validation(
      $this->form_validation,
      $this->inventory_model->get_all_validation_rule()
    );
    $this->_data['view_model'] = new Inventory_member_add_view_model($this->inventory_model);
    $this->_data['view_model']->set_heading('Inventory');

    $this->load->model('image_model');
    $gallery_images = $this->image_model->get_all();
    foreach ($gallery_images as $key => $image) {
      if ($image->type == 4) {
        $image->show_url = base_url() . $image->url;
      } else {
        $image->show_url = $image->url;
      }
      $image->type = $this->image_model->type_mapping()[$image->type];
    }
    $this->_data['gallery_images'] = $gallery_images;

    // load data
    $schools    = $this->school_model->get_all(['status' => 1]);
    $professors = $this->professor_model->get_all(['status' => 1]);
    $textbooks  = $this->textbook_model->get_all(['status' => 1]);
    $classes    = $this->classes_model->get_all(['status' => 1]);

    if (!empty($schools)) {
      $this->_data['schools'] = $schools;
    }

    if (!empty($professors)) {
      $this->_data['professors'] = $professors;
    }

    if (!empty($textbooks)) {
      $this->_data['textbooks'] = $textbooks;
    }

    if (!empty($classes)) {
      $this->_data['classes'] = $classes;
    }

    if ($this->form_validation->run() === FALSE) {
      return $this->render('Member/InventoryAdd', $this->_data);
    }

    $title            = $this->input->post('title', TRUE);
    $school_id        = $this->input->post('school_id', TRUE);
    $professor_id     = $this->input->post('professor_id', TRUE);
    $class_id         = $this->input->post('class_id', TRUE);
    $textbook_id      = $this->input->post('textbook_id', TRUE);
    $word_count       = $this->input->post('word_count', TRUE);
    $year             = $this->input->post('year', TRUE);
    $isbn             = $this->input->post('isbn', TRUE);
    // $paypal_email     = $this->input->post('paypal_email', TRUE);
    $file             = $this->input->post('file', TRUE);
    $file_id          = $this->input->post('file_id', TRUE);
    $feature_image    = $this->input->post('feature_image', TRUE);
    $feature_image_id = $this->input->post('feature_image_id', TRUE);
    $note_type        = $this->input->post('note_type', TRUE);
    // $description      = $this->input->post('description', TRUE);
    // $inventory_note   = $this->input->post('inventory_note', TRUE);

    // $pin_to_top       = $this->input->post('pin_to_top', TRUE);
    // $approve          = $this->input->post('approve', TRUE);
    // $status           = $this->input->post('status', TRUE);
    $session_user_id = $this->session->userdata('user_id');

    $result = $this->inventory_model->create([
      'title'            => $title,
      'school_id'        => $school_id,
      'user_id'          => $session_user_id,
      'professor_id'     => $professor_id,
      'class_id'         => $class_id,
      'textbook_id'      => $textbook_id,
      'word_count'       => $word_count,
      'year'             => $year,
      'isbn'             => $isbn,
      // 'paypal_email'     => $paypal_email,
      'file'             => $file,
      'file_id'          => $file_id,
      'feature_image'    => $feature_image,
      'feature_image_id' => $feature_image_id,
      'note_type'        => $note_type,
      // 'description'      => $description,
      // 'inventory_note'   => $inventory_note,
      'pin_to_top'       => 0,
      'approve'          => 1,
      'status'           => 1
    ]);

    if ($result) {
      $this->success('Added successfully');
      return $this->redirect('/member/inventory/0', 'refresh');
    }

    $this->_data['error'] = 'Error';
    return $this->render('Member/InventoryAdd', $this->_data);
  }






  protected function s3_file_upload()
  {
    $this->load->model('image_model');
    $this->load->library('mime_service');

    $s3 = new S3Client([
      'version' => $this->config->item('aws_version'),
      'region'  => $this->config->item('aws_region'),
      'endpoint'  => $this->config->item('aws_endpoint'),
      'use_path_style_endpoint' => true,
      'credentials' => [
        'key'    => $this->config->item('aws_key'),
        'secret' => $this->config->item('aws_secret'),
      ]
    ]);

    // $s3->setEndpoint('s3.us-west-001.backblazeb2.com');

    if (!(isset($_FILES) && count($_FILES) > 0 && isset($_FILES['file_upload']))) {
      $output['error'] = 'Upload file failed';
      return $output;
    }

    $file = $_FILES['file_upload'];
    $size = $file['size'];
    $path = $file['tmp_name'];
    $type = $file['type'];
    $extension = $this->mime_service->get_extension($type);

    if ($size > $this->config->item('upload_byte_size_limit')) {
      $output['error'] = 'Upload file size too big';
      return $output;
    }

    $filename = md5(uniqid() . time()) . $extension;
    $width = 0;
    $height = 0;
    $session = $this->get_session();
    $user_id = isset($session['user_id']) ? $session['user_id'] : 0;

    try {
      $result = $s3->putObject([
        'Bucket' => $this->config->item('aws_bucket'),
        'Key'    => $filename,
        'Body'   => fopen($path, 'r'),
        'ACL'    => 'public-read',
      ]);

      $image_id = $this->image_model->create([
        'url' => $result->get('ObjectURL'),
        'type' => 5,
        'user_id' => $user_id,
        'width' => $width,
        'caption' => '',
        'height' => $height
      ]);

      $output['file'] = $result->get('ObjectURL');
      return $output;
    } catch (Aws\S3\Exception\S3Exception $e) {
      echo "<pre>";
      print_r($e->getMessage());
      die;
      $output['error'] = $e->getMessage();
      return $output;
    }
  }


  public function add_inventory()
  {

    include_once __DIR__ . '/../../view_models/Inventory_member_add_view_model.php';
    $session               = $this->get_session();
    $this->form_validation = $this->inventory_model->set_form_validation(
      $this->form_validation,
      $this->inventory_model->get_all_validation_rule()
    );
    $this->_data['view_model'] = new Inventory_member_add_view_model($this->inventory_model);
    $this->_data['view_model']->set_heading('Inventory');

    $this->load->model('image_model');
    $gallery_images = $this->image_model->get_all();
    foreach ($gallery_images as $key => $image) {
      if ($image->type == 4) {
        $image->show_url = base_url() . $image->url;
      } else {
        $image->show_url = $image->url;
      }
      $image->type = $this->image_model->type_mapping()[$image->type];
    }
    $this->_data['gallery_images'] = $gallery_images;

    // load data
    $schools    = $this->school_model->get_all(['status' => 1]);
    $professors = $this->professor_model->get_all(['status' => 1]);
    $textbooks  = $this->textbook_model->get_all(['status' => 1]);
    $classes    = $this->classes_model->get_all(['status' => 1]);

    if (!empty($schools)) {
      $this->_data['schools'] = $schools;
    }

    if (!empty($professors)) {
      $this->_data['professors'] = $professors;
    }

    if (!empty($textbooks)) {
      $this->_data['textbooks'] = $textbooks;
    }

    if (!empty($classes)) {
      $this->_data['classes'] = $classes;
    }

    if ($this->form_validation->run() === FALSE  || (!($this->input->post('remember1')) || !($this->input->post('remember2')))) {

      $this->error(validation_errors());
      return $this->redirect('/sell', 'refresh');
    }

    $file = '';
    $preview_files = [];

    $file_upload_type = $this->config->item('file_upload');
    if ($file_upload_type == 's3') {
      $output =  $this->s3_file_upload();
      if (isset($output['file'])) {
        $file = $output['file'];
      } else if (isset($output['error'])) {
        $this->error($output['error']);
        return $this->redirect('/sell', 'refresh');
      }
    } else {
      $config['upload_path']          = './uploads/';
      $config['allowed_types']        = 'doc|docx';
      // $config['max_size']             = 100;


      $this->load->library('upload', $config);

      if (!$this->upload->do_upload('file_upload')) {
        $this->error($this->upload->display_errors());
        return $this->redirect('/sell', 'refresh');
      } else {
        $data = $this->upload->data();
        $file =  'uploads/' . $data['file_name'];
        // $this->load->view('upload_success', $data);
      }

      //   echo '<pre>';
      $upload_file_prop = pathinfo($file);

      // $phpWord = new \PhpOffice\PhpWord\PhpWord();

      $type = '';
      if ($upload_file_prop['extension'] == 'doc') {
        $type = 'MsDoc';
      } else if ($upload_file_prop['extension'] == 'docx') {
        $type = 'Word2007';
      }

      if ($type == '') {
        $this->error('File Name is not correct');
        return $this->redirect('/sell', 'refresh');
      }
    }





    $uri = __DIR__ . "/../../../" . $file;


    $error          = false;
    $api_fileName   = '';
    $api_folderName = '';


    $school_id        = $this->input->post('school_id', TRUE);
    $professor_id     = $this->input->post('professor_id', TRUE);
    $class_id         = $this->input->post('class_id', TRUE);
    $textbook         = $this->input->post('textbook', TRUE) ?? '0';
    $word_count       = $this->input->post('word_count', TRUE);
    $year             = $this->input->post('year', TRUE);

    $note_type        = $this->input->post('note_type', TRUE);

    $session_user_id = $this->session->userdata('user_id');

    $result = $this->inventory_model->create([
      'title'             => ' ',
      'school_id'         => $school_id,
      'user_id'           => $session_user_id,
      'professor_id'      => $professor_id,
      'class_id'          => $class_id,
      'isbn'              => $textbook ?? '',
      'word_count'        => $word_count,
      'year'              => $year,
      'file'              => $file,
      'feature_image'     =>  "",
      'feature_image2'    =>  "",
      'feature_image3'    =>  "",
      'note_type'         => $note_type,
      'pin_to_top'        => 0,
      'approve'           => 1,
      'status'            => 1
    ]);

    $paypal_email = $this->input->post('paypal_email', TRUE);
    if ($paypal_email) {
      $this->db->set('paypal_email', $paypal_email)->where('id', $session_user_id)->update('user');
    }
    if ($result) {
      $this->load->model('email_model');
      $this->load->library('mail_service');
      $this->mail_service->set_adapter('smtp');
      $from_email = $this->config->item('from_email');
      $message = "A new outline has been added with ID " . $result . ".";
      $this->mail_service->send($from_email, $from_email, "New Outline Added", $message);



      $user_data = $this->user_model->get($this->session->userdata('user_id'));
      $logo      = base_url('assets/frontend/img/logo.png');

      //send email to uploader  
      $template = $this->email_model->get_template('outline_uploaded', [
        'name'     => $user_data->first_name . " " . $user_data->last_name,
        'logo'     => $logo,
      ]);
      $this->mail_service->send($from_email, $this->session->userdata('email'), $template->subject, $template->html);


      $this->success('Added successfully');
      return $this->redirect('/sell', 'refresh');
    }

    $this->error('Error');
    return $this->redirect('/sell', 'refresh');
  }

  public function edit($id)
  {
    $model   = $this->inventory_model->get($id);
    $session = $this->get_session();
    if (!$model) {
      $this->error('Error');
      return redirect('/member/inventory/0');
    }

    include_once __DIR__ . '/../../view_models/Inventory_member_edit_view_model.php';
    $this->form_validation = $this->inventory_model->set_form_validation(
      $this->form_validation,
      $this->inventory_model->get_all_edit_validation_rule()
    );
    $this->_data['view_model'] = new Inventory_member_edit_view_model($this->inventory_model);
    $this->_data['view_model']->set_model($model);
    $this->_data['view_model']->set_heading('Inventory');

    $this->load->model('image_model');
    $gallery_images = $this->image_model->get_all();
    foreach ($gallery_images as $key => $image) {
      if ($image->type == 4) {
        $image->show_url = base_url() . $image->url;
      } else {
        $image->show_url = $image->url;
      }
      $image->type = $this->image_model->type_mapping()[$image->type];
    }
    $this->_data['gallery_images'] = $gallery_images;

    // load data
    $schools    = $this->school_model->get_all(['status' => 1]);
    $professors = $this->professor_model->get_all(['status' => 1]);
    $textbooks  = $this->textbook_model->get_all(['status' => 1]);
    $classes    = $this->classes_model->get_all(['status' => 1]);

    if (!empty($schools)) {
      $this->_data['schools'] = $schools;
    }

    if (!empty($professors)) {
      $this->_data['professors'] = $professors;
    }

    if (!empty($textbooks)) {
      $this->_data['textbooks'] = $textbooks;
    }

    if (!empty($classes)) {
      $this->_data['classes'] = $classes;
    }

    if ($this->form_validation->run() === FALSE) {
      return $this->render('Member/InventoryEdit', $this->_data);
    }

    // $title            = $this->input->post('title', TRUE);
    $school_id        = $this->input->post('school_id', TRUE);
    $professor_id     = $this->input->post('professor_id', TRUE);
    $class_id         = $this->input->post('class_id', TRUE);
    $textbook_id      = $this->input->post('textbook_id', TRUE) ?? '0';
    $word_count       = $this->input->post('word_count', TRUE);
    $year             = $this->input->post('year', TRUE);
    $isbn             = $this->input->post('isbn', TRUE);
    // $paypal_email     = $this->input->post('paypal_email', TRUE);
    $file             = $this->input->post('file', TRUE);
    $file_id          = $this->input->post('file_id', TRUE);
    $feature_image    = $this->input->post('feature_image', TRUE);
    $feature_image_id = $this->input->post('feature_image_id', TRUE);
    $note_type        = $this->input->post('note_type', TRUE);
    $description      = $this->input->post('description', TRUE);
    $inventory_note   = $this->input->post('inventory_note', TRUE);

    // $pin_to_top       = $this->input->post('pin_to_top', TRUE);
    // $approve          = $this->input->post('approve', TRUE);
    // $status           = $this->input->post('status', TRUE);

    $result = $this->inventory_model->edit([
      // 'title'            => $title,
      'school_id'        => $school_id,
      'professor_id'     => $professor_id,
      'class_id'         => $class_id,
      'textbook_id'      => $textbook_id,
      'word_count'       => $word_count,
      'year'             => $year,
      // 'isbn'             => $isbn,
      // 'paypal_email'     => $paypal_email,
      // 'file'             => $file,
      // 'file_id'          => $file_id,
      // 'feature_image'    => $feature_image,
      // 'feature_image_id' => $feature_image_id,
      'note_type'        => $note_type,
      // 'description'      => $description ?? '',
      // 'inventory_note'   => $inventory_note ?? '',
      'pin_to_top'       => 0,
      'approve'          => 1,
      'status'           => 1
    ], $id);

    if ($result) {
      $this->success('Updated successfully');
      return $this->redirect('/member/inventory/0', 'refresh');
    }

    $this->_data['error'] = 'Error';
    return $this->render('Member/InventoryEdit', $this->_data);
  }

  public function view($id)
  {
    $model = $this->inventory_model->get($id);

    if (!$model) {
      $this->error('Error');
      return redirect('/member/inventory/0');
    }

    include_once __DIR__ . '/../../view_models/Inventory_member_view_view_model.php';
    $this->_data['view_model'] = new Inventory_member_view_view_model($this->inventory_model);
    $this->_data['view_model']->set_heading('Inventory');
    $this->_data['view_model']->set_model($model);

    //get all school data
    $this->_data['school_data']  = array_column($this->school_model->get_all(['status' => 1]), 'name', 'id');
    //get all professor data
    $this->_data['professor_data']  = array_column($this->professor_model->get_all(['status' => 1]), 'name', 'id');
    //get all classes data
    $this->_data['classes_data']  = array_column($this->classes_model->get_all(['status' => 1]), 'name', 'id');
    //get all textbook data
    $this->_data['textbook_data']  = array_column($this->textbook_model->get_all(['status' => 1]), 'isbn', 'id');

    $this->_data['view_model']->set_school_id($this->_data['school_data'][$this->_data['view_model']->get_school_id()]);
    $this->_data['view_model']->set_professor_id($this->_data['professor_data'][$this->_data['view_model']->get_professor_id()]);
    $this->_data['view_model']->set_class_id($this->_data['classes_data'][$this->_data['view_model']->get_class_id()]);
    // $this->_data['view_model']->set_textbook_id( $this->_data['textbook_data'][$this->_data['view_model']->get_textbook_id()]);


    return $this->render('Member/InventoryView', $this->_data);
  }

  public function dispute_order()
  {

    $this->_data['orders'] = $this->db->query('select * from `order` o where o.id not in (select order_id from ticket where user_id=' . $this->session->userdata("user_id") . ')')->result_array();

    return $this->render('Member/Dispute', $this->_data);
  }
  public function post_dispute()
  {

    if (empty($this->input->get('comment')) || empty($this->input->get('order_id'))) {
      $this->_data['status_'] = 'error';
      $this->_data['status_msg'] = 'Fill Required Fields';
    } else {

      $already_disputed = $this->db->select('*')->from('ticket')->where('order_id', intval($this->input->get('order_id')))->get()->row_array();
      if (empty($already_disputed)) {

        $this->db->insert('ticket', [
          'message' => $this->input->get('comment'),
          'user_id' => $this->session->userdata('user_id'),
          'order_id' => intval($this->input->get('order_id')),
          'status' => 0,
          'receive_status' => 0,
          'created_at' => date('Y-m-d h:i:s')
        ]);

        $this->load->model('email_model');
        $this->load->library('mail_service');
        $this->mail_service->set_adapter('smtp');

        $user_data = $this->db->select('u.*,c.email')->from('user u')->join('credential c', 'c.user_id=u.id')->where('u.id', $this->session->userdata('user_id'))->get()->row_array();



        $from_email = $this->config->item('from_email');
        $domain = explode("@", $from_email)[1];

        $template = $this->email_model->get_template('dispute_added', [
          'email' => $user_data['email'],
          'order_id' => $this->input->get('order_id'),
        ]);

        $this->mail_service->send($from_email, $from_email, $template->subject, $template->html);

        $this->_data['status_'] = 'success';
        $this->_data['status_msg'] = 'Dispute Submitted Successfully.';
      }
      $this->_data['orders'] = $this->db->query('select * from `order` o where o.id not in (select order_id from ticket where user_id=' . $this->session->userdata("user_id") . ')')->result_array();
    }

    return $this->render('Member/Dispute', $this->_data);
  }
  public function review($invetory_id, $order_id)
  {

    return $this->render('Member/Review', $this->_data);
  }
  public function post_review($invetory_id, $order_id)
  {

    if (empty($this->input->get('comment')) || empty($this->input->get('review')) || empty($this->input->get('inventory_id'))) {
      $this->_data['status_'] = 'error';
      $this->_data['status_msg'] = 'Fill Required Fields';
    } else {

      if ($this->db->select('*')->from('review')->where('user_id', $this->session->userdata('user_id'))->where('inventory_id', intval($this->input->get('inventory_id')))->get()->result_array()) {
        $this->_data['status_'] = 'error';
        $this->_data['status_msg'] = 'Can not give Review again for same Paper';
      } else {

        $this->db->insert('review', [
          'comment' => $this->input->get('comment'),
          'private_review' => $this->input->get('private_review'),
          'user_id' => $this->session->userdata('user_id'),
          'rating' => intval($this->input->get('review')),
          'inventory_id' => intval($this->input->get('inventory_id')),
          'order_id' => intval($order_id),
          'status' => 1,
          'created_at' => date('Y-m-d h:i:s')
        ]);

        $this->_data['status_'] = 'success';
        $this->_data['status_msg'] = 'Review Submitted Successfully.';
      }
    }

    return $this->render('Member/Review', $this->_data);
  }

  public function setting()
  {

    $this->_data['paypal_email'] = $this->db->select('paypal_email')->from('user')->where('id', $this->session->userdata('user_id'))->get()->row_array();
    return $this->render('Member/Setting', $this->_data);
  }
  public function post_setting()
  {
    $regex = '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/';

    if (empty($this->input->get('email'))) {
      $this->_data['status_'] = 'error';
      $this->_data['status_msg'] = 'Enter Email';
    } else {

      if (!preg_match($regex, $this->input->get('email'))) {
        $this->_data['status_'] = 'error';
        $this->_data['status_msg'] = 'Invalid Email';
      } else {

        $this->db->set('paypal_email', $this->input->get('email'))
          ->where('id', $this->session->userdata('user_id'))->update('user');

        $this->_data['status_'] = 'success';
        $this->_data['status_msg'] = 'Email updated submitted successfully.';
      }
    }
    $this->_data['paypal_email'] = $this->db->select('paypal_email')->from('user')->where('id', $this->session->userdata('user_id'))->get()->row_array();
    return $this->render('Member/Setting', $this->_data);
  }

  public function delete()
  {
    $id = $this->input->post('id');
    $model = $this->inventory_model->get($id);


    if (!empty($id)) {
      $result = $this->inventory_model->real_delete($id);

      if ($result) {
        $output['success'] = TRUE;
        $output['status']  = 200;
        $output['msg']     = 'Notes deleted successfully.';
        echo json_encode($output);
        exit();
      } else {
        $output['error']  = TRUE;
        $output['status'] = 0;
        $output['msg']    = 'Error! Please try again later.';
        echo json_encode($output);
        exit();
      }
    } else {
      $output['error']  = TRUE;
      $output['status'] = 0;
      $output['msg']    = 'Error! Data not found.';
      echo json_encode($output);
      exit();
    }
  }

  public function add_suggestion()
  {
    $this->load->model('suggestion_model');

    $this->form_validation = $this->suggestion_model->set_form_validation(
      $this->form_validation,
      $this->suggestion_model->get_all_validation_rule()
    );
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

    $name             = $this->input->post('name', TRUE);
    $email            = $this->input->post('email', TRUE);
    $suggestion_type  = $this->input->post('suggestion_type', TRUE);
    $suggestion_name  = $this->input->post('suggestion_name', TRUE);
    $additional_notes = $this->input->post('additional_notes', TRUE);

    $payload_1 = [
      'name'             => $name,
      'email'            => $email,
      'suggestion_type'  => $suggestion_type,
      'suggestion_name'  => $suggestion_name,
      'additional_notes' => $additional_notes
    ];

    $this->db->trans_begin();
    $result_1 = $this->suggestion_model->create($payload_1);

    if ($result_1) {
      $this->db->trans_commit();
      $res = [
        'success' => TRUE,
        'status'  => 200,
        'msg'     => 'Success! Suggestion Added.'
      ];

      $type_name = "";
      if ($suggestion_type == 1) {
        $type_name = "School";
      } else if ($suggestion_type == 2) {
        $type_name = "Professor";
      } else if ($suggestion_type == 3) {
        $type_name = "Textbook";
      } else if ($suggestion_type == 4) {
        $type_name = "Course";
      }

      $this->load->model('email_model');
      $this->load->library('mail_service');
      $this->mail_service->set_adapter('smtp');
      $from_email = $this->config->item('from_email');

      $message = "A new suggestion has been recieved.<br>";
      $message .= "Name :- " . $name . " <br>";
      $message .= "Email :- " . $email . " <br>";
      $message .= "Suggestion Type :- " . $type_name . " <br>";
      $message .= "Suggestion Name :- " . $suggestion_name . " <br>";
      $message .= "Additional Note :- " . $additional_notes . " <br>";
      $this->mail_service->send($from_email, $from_email, "New Suggestion", $message);


      echo json_encode($res);
      exit;
    } else {
      $this->db->trans_rollback();
      $res = [
        'error'  => TRUE,
        'status' => 0,
        'msg'    => 'Error! Suggestion add failed.'
      ];
      echo json_encode($res);
      exit;
    }
  }

  // public function purchasec(){
  //   // for demo
  //  if($this->session->userdata('user_id') && is_numeric($this->input->post('id'))  ){

  //   $this->load->model('email_model');
  //   $this->load->library('mail_service');
  //   $this->mail_service->set_adapter('smtp');


  //     $user_data = $this->db->select('u.*,c.email')->from('user u')->join('credential c','c.user_id=u.id')->where('u.id',$this->session->userdata('user_id'))->get()->row_array();
  //     $output = [];


  //       $invetory_data = $this->db->select('i.*,p.name as professor,c.name as class')->from('inventory i ')
  //       ->join('professor p','p.id=i.professor_id')
  //       ->join('classes c','c.id=i.class_id')
  //       ->where('i.id',$this->input->post('id'))->get()->row_array();

  //       $insert = [
  //         'purchase_user_id'=>$user_data['id'],
  //         'sale_user_id'=>$invetory_data['user_id'],
  //         'inventory_id'=>$invetory_data['id'],
  //         'order_date'=>date('Y-m-d'),
  //         'order_time'=>date('h:i:s'),
  //         'subtotal'=>50,
  //         'tax'=>0,
  //         'discount'=>0,
  //         'total'=>50,
  //         'stripe_charge_id'=>'Test11',
  //         'stripe_intent'=>json_encode(['test'=>1]),
  //         'status'=>1,
  //         'created_at'=>date('Y-m-d'),
  //         'updated_at'=>date('Y-m-d'),
  //       ];

  //       $this->db->insert('order',$insert);


  //       //send email 

  //       $from_email = $this->config->item('from_email');
  //       $domain = explode("@", $from_email)[1];

  //       $template = $this->email_model->get_template('give_review', [ 
  //         'email' => $user_data['email']
  //       ]);

  //       $this->mail_service->send('admin@' . $domain , $from_email , $template->subject, $template->html);


  //       $output['status'] = true;
  //       $output['file_source'] = base_url().$invetory_data['file'];
  //       $output['file_name'] = $invetory_data['professor'].'.'.$invetory_data['class'].'.'.$invetory_data['year'];
  //       $output['extension'] = explode('.',$invetory_data['file'])[1];
  //       echo json_encode($output);
  //       exit();


  //   }else{
  //     $output['status'] = false;
  //     echo json_encode($output);
  //     exit();
  //   }
  // }




  public function purchase()
  {

    if ($this->session->userdata('user_id') && $this->input->post('id')) {
      $this->form_validation->set_rules('card_name', 'Card Name', 'required');
      $this->form_validation->set_rules('card_number', 'Card Number', 'required');
      $this->form_validation->set_rules('exp_month', 'Card Expiry Month', 'required');
      $this->form_validation->set_rules('exp_year', 'Card Expiry Year', 'required');
      $this->form_validation->set_rules('card_cvc', 'Card CVC', 'required');



      if ($this->form_validation->run() === FALSE) {
        $error_msg = validation_errors();
        $output['error_msg'] = $error_msg;
        echo json_encode($output);
        exit();
      }

      try {
        $card_number = $this->input->post('card_number', TRUE);
        $card_cvc    = $this->input->post('card_cvc', TRUE);
        $card_name   = $this->input->post('card_name', TRUE);
        $exp_year    = $this->input->post('exp_year', TRUE);
        $exp_month   = $this->input->post('exp_month', TRUE);


        $this->load->model('email_model');
        $this->load->library('mail_service');
        $this->mail_service->set_adapter('smtp');

        //create setup intent used in js  
        Stripe::setApiKey($this->config->item('stripe_secret_key'));

        $user_data = $this->db->select('u.*,c.email')->from('user u')->join('credential c', 'c.user_id=u.id')->where('u.id', $this->session->userdata('user_id'))->get()->row_array();




        if (empty($user_data['stripe_id'])) {

          $obj = Customer::create([
            'customer' => [
              'email' => $user_data['email'],
            ],
          ]);

          // $obj = $stripe->customers->create([
          //     'email'          => $user_data['email'],
          //     'payment_method' => $this->input->post('key'),
          // ]);

          $this->db->set('stripe_id', $obj->id);
          $this->db->where('id', $user_data['id']);
          $this->db->update('user');
          $user_data['stripe_id'] = $obj->id;
        }


        $token = Token::create([
          'card' => [
            'number'    => $card_number,
            'exp_month' => $exp_month,
            'exp_year'  => $exp_year,
            'cvc'       => $card_cvc,
          ],
        ]);


        $stripe_token_id    = $token->id;
        $stripe_customer_id = $user_data['stripe_id'];
        // assign card to the user (not default)
        $card_data = Customer::createSource(
          $stripe_customer_id,
          [
            'source' => $stripe_token_id,
          ]
        );





        // // add payment method 
        // $card_response = $stripe->paymentMethods->attach(
        //     $this->input->post('key'),
        //     ['customer' => $user_data['stripe_id']]
        // );


        // $payment_method_id = '';

        // if($card_response)
        // {
        //     $payment_method_id = $card_response->id;
        // }

        // if($payment_method_id=='')
        // {

        //     $data = $stripe->paymentMethods->all([
        //         'customer' => $user_data['stripe_id'],
        //         'type'     => 'card',
        //     ]);

        //     foreach ($data->data as $key => $value) 
        //     {
        //         $payment_method_id = $value->id;
        //     }
        // }

        //notes amount 
        $notes_amount = $this->db->select('value')->from('setting')->where('key', 'fixed_paper_amount')->get()->row_array();
        if ($notes_amount) {
          $notes_amount  =  $notes_amount['value'];
        } else {
          $notes_amount  =  50;
        }

        //sales percent 
        $sale_percent = $this->db->select('value')->from('setting')->where('key', 'payout_percentage_seller')->get()->row_array();
        if ($sale_percent) {
          $sale_percent  =  $sale_percent['value'];
        } else {
          $sale_percent  = 20;
        }


        // $payment_intent = $stripe->paymentIntents->create([
        //     'amount'            => $notes_amount*100,
        //     'currency'          => 'usd',
        //     'confirm'           => true,
        //     'payment_method'    => $payment_method_id,
        //     'payment_method_types'  => ['card'],
        //     'customer'              => $user_data['stripe_id'],
        //     'capture_method'        => 'manual',
        //     'description'           => 'Buying Papers From Outline Gurus',
        // ]);

        // $result =  $stripe->paymentIntents->capture($payment_intent->id );

        $amount = str_replace(",", "", $notes_amount);

        $price_total         = 0;
        $price_total         = $amount * 100;
        $stripe_customer_id  = $user_data['stripe_id'];
        $stripe_card_id      = $card_data->id;

        $result = Charge::create(array(
          "amount"        => $price_total,
          "currency"      => "usd",
          "customer"      => $stripe_customer_id,
          "receipt_email" => $this->session->userdata('email'),
          "source"        => $stripe_card_id,
          "description"   => 'Buying Papers From OutlineGurus',
        ));



        $invetory_data = $this->db->select('i.*,p.name as professor,c.name as class')->from('inventory i ')
          ->join('professor p', 'p.id=i.professor_id')
          ->join('classes c', 'c.id=i.class_id')
          ->where('i.id', $this->input->post('id'))->get()->row_array();

        $insert = [
          'purchase_user_id'  => $user_data['id'],
          'sale_user_id'      => $invetory_data['user_id'],
          'inventory_id'      => $invetory_data['id'],
          'order_date'        => date('Y-m-d'),
          'order_time'        => date('h:i:s'),
          'subtotal'          => $notes_amount,
          'tax'               => 0,
          'discount'          => 0,
          'total'             => $notes_amount,
          'stripe_charge_id'  => $result->id,
          'stripe_intent'     => json_encode($result),
          'status'            => 1,
          'created_at'        => date('Y-m-d'),
          'updated_at'        => date('Y-m-d'),
        ];

        $this->db->insert('order', $insert);
        $order_id = $this->db->insert_id();


        //payout table
        $insert = [
          'user_id'       => $invetory_data['user_id'],
          'order_id'      => $order_id,
          'inventory_id'  => $invetory_data['id'],
          'created_at'    => date('Y-m-d'),
          'amount'        => ($sale_percent * $notes_amount) / 100,
          'payout_date'   => null,
          'status'        => 0, //unpaid
        ];

        $this->db->insert('payout', $insert);


        $logo  = base_url('assets/frontend/img/logo.png');

        //send email 

        $from_email = $this->config->item('from_email');
        $domain     = explode("@", $from_email)[1];

        $template = $this->email_model->get_template('give_review', [
          'email' => $user_data['first_name'] . " " . $user_data['last_name'],
          'link' => '<br><a href="' . base_url() . '/member/review/' . $invetory_data['id'] . '/' . $order_id . '">Click here to leave review</a> ',
        ]);




        $html  = $template->html;
        $html .= "<br> Thanks,";
        $html .= "<br><br> The OutlineGurus Team";
        $html .= "<br> <img  src='" . $logo . "' style='width:149px' />";

        $email_to_user = $this->mail_service->send($from_email, $user_data['email'], $template->subject, $html);






        $invetory_data2  = $this->inventory_model->get($this->input->post('id'));
        $sale_user_data = $this->user_model->get($invetory_data2->user_id);
        $email_data     = $this->credential_model->get_by_fields(['user_id' => $invetory_data2->user_id]);


        //send email to uploader 
        $template = $this->email_model->get_template('outline_downloaded', [
          'name'     => $sale_user_data->first_name . " " . $sale_user_data->last_name,
          'logo'     => $logo,
        ]);
        $send = $this->mail_service->send($from_email, $email_data->email, $template->subject, $template->html);


        $output['status']       = true;
        $output['file_name']    = $invetory_data['professor'] . '.' . $invetory_data['class'] . '.' . $invetory_data['year'];
        $output['file_source']  = $invetory_data['file'];
        $output['extension']    = explode('.', $invetory_data['file'])[1];
        echo json_encode($output);
        exit();
      } catch (\Stripe\Exception\CardException $e) {
        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        echo json_encode($output);
        exit();
      } catch (\Stripe\Exception\RateLimitException $e) {

        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        echo json_encode($output);
        exit();
      } catch (\Stripe\Exception\InvalidRequestException $e) {

        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        echo json_encode($output);
        exit();
      } catch (\Stripe\Exception\AuthenticationException $e) {
        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        echo json_encode($output);
        exit();
      } catch (\Stripe\Exception\ApiConnectionException $e) {
        $output['error'] = TRUE;
        $output['error_msg']   = $e->message();
        echo json_encode($output);
        exit();
      } catch (\Stripe\Exception\ApiErrorException $e) {
        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        echo json_encode($output);
        exit();
      } catch (Exception $e) {
        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        echo json_encode($output);
        exit();
      }
    } else {
      $output['status'] = false;
      echo json_encode($output);
      exit();
    }
  }


  protected function pay_using_stripe($amount, $stripe_customer_id, $stripe_card_id)
  {

    $stripe_secret_key  = $this->_config->item('stripe_secret_key');

    Stripe::setApiKey($stripe_secret_key);

    $get_user_data      = $this->_customer_model->get($user_id);
    $get_user_card_data = $this->_customer_cards_model->get($user_card_id);
    $stripe_customer_id = $get_user_data->stripe_id;
    $stripe_card_id     = $get_user_card_data->card_token;

    if ($stripe_customer_id && $amount) {
      $amount = str_replace(",", "", $amount);

      $price_total         = 0;
      $price_total         = $amount * 100;
      $stripe_customer_id  = $stripe_customer_id;


      try {
        $charge = Charge::create(array(
          "amount"        => $price_total,
          "currency"      => "usd",
          "customer"      => $stripe_customer_id,
          "source"        => $stripe_card_id,
          "description"   => $description
        ));


        $output['success']   = true;
        $output['response']  = $charge;
        return $output;
        exit();
      } catch (\Stripe\Exception\CardException $e) {
        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        return $output;
        exit();
      } catch (\Stripe\Exception\RateLimitException $e) {


        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        return $output;
        exit();
      } catch (\Stripe\Exception\InvalidRequestException $e) {


        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        return $output;
        exit();
      } catch (\Stripe\Exception\AuthenticationException $e) {
        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        return $output;
        exit();
      } catch (\Stripe\Exception\ApiConnectionException $e) {

        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        return $output;
        exit();
      } catch (\Stripe\Exception\ApiErrorException $e) {
        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        return $output;
        exit();
      } catch (Exception $e) {
        $output['error'] = TRUE;
        $output['error_msg']   = $e->getError()->message;
        return $output;
        exit();
      }
    } else {
      $output['error']     = true;
      $output['error_msg'] = "Error! Token and Amount is required.";
      return $output;
      exit();
    }
  }










  public function test_docx()
  {
    $file = "uploads/test_doc_4.docx";
    $uri = __DIR__ . "/../../../" . $file;

    //phpinfo();
    //die();

    $preview_files = [];
    try {
      $FilePathPdf         = "uploads/" . uniqid() . "pdf-file.pdf";
      $rendererLibraryPath = __DIR__ . "/../../../vendor/dompdf/dompdf";
      $rendererName        = \PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF;
      \PhpOffice\PhpWord\Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
      \PhpOffice\PhpWord\Settings::setPdfRendererPath($rendererLibraryPath);


      $phpWord    = \PhpOffice\PhpWord\IOFactory::load($uri);
      $pdfWriter  = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
      $pdfWriter->save($FilePathPdf);

      $document   =  __DIR__ . "/../../../" . $FilePathPdf;
      $im         = new imagick($document);

      $u_id = uniqid();
      if ($im) {
        $im->setIteratorIndex(0);
        $im->setResolution(600, 600);
        $im->setImageFormat('png');
        $im->setImageCompression(imagick::COMPRESSION_ZIP);
        $im->setImageCompressionQuality(100);
        $im->setCompressionQuality(100);


        $uid = __DIR__ . "/../../../uploads/";

        $im->writeImage($uid . $u_id . "-ttt.png");
        $preview_files[0]  = 'uploads/' . $u_id . '-ttt.png';

        $im->clear();
        $im->destroy();
      }


      $im2 = new imagick($document);

      if ($im2) {
        $im2->setIteratorIndex(1);
        $im2->setResolution(600, 600);
        $im2->setImageFormat('png');
        $im2->setImageCompression(imagick::COMPRESSION_ZIP);
        $im2->setImageCompressionQuality(100);
        $im2->setCompressionQuality(100);

        $uid = __DIR__ . "/../../../uploads/";

        $im2->writeImage($uid . $u_id . "-ttt2.png");
        $preview_files[1]  = 'uploads/' . $u_id . '-ttt2.png';

        $im2->clear();
        $im2->destroy();
      }


      $im3 = new imagick($document);

      if ($im3) {
        $im3->setResolution(600, 600);
        $im3->setImageFormat('png');
        $im3->setImageCompression(imagick::COMPRESSION_ZIP);
        $im3->setImageCompressionQuality(100);
        $im3->setCompressionQuality(100);


        $uid = __DIR__ . "/../../../uploads/";

        $im3->writeImage($uid . $u_id . "-ttt3.png");
        $preview_files[2]  = 'uploads/' . $u_id . '-ttt3.png';

        $im3->clear();
        $im3->destroy();
      }
    } catch (\BadMethodCallException $e) {
      echo date('H:i:s'), ' error ...' . $e->getMessage();
    }



    echo "<pre>";
    print_r($preview_files);
    print_r($im);
    die();
  }
}
