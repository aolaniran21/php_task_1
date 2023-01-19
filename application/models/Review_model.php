<?php defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2021*/
/**
 * Review_model Model
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Review_model extends Manaknight_Model
{
	protected $_table = 'review';
	protected $_primary_key = 'id';
	protected $_return_type = 'array';
	protected $_allowed_fields = [
		'id',
		'user_id',
		'inventory_id',
		'order_id',
		'status',
		'comment',
		'private_review',
		'rating',

	];
	protected $_label_fields = [
		'Id', 'User ID', 'Inventory ID', 'order_id', 'Status', 'Comment', 'Private Review', 'Rating',
	];
	protected $_use_timestamps = TRUE;
	protected $_created_field = 'created_at';
	protected $_updated_field = 'updated_at';
	protected $_validation_rules = [
		['id', 'Id', ''],
		['user_id', 'User ID', 'required|integer'],
		['inventory_id', 'Inventory ID', 'required|integer'],
		['order_id', 'order_id', 'integer'],
		['status', 'Status', 'required|integer'],
		['comment', 'Comment', 'required'],
		['private_review', 'Private Review', 'required|text'],
		['rating', 'Rating', 'required'],

	];
	protected $_validation_edit_rules = [
		['id', 'Id', ''],
		['user_id', 'User ID', 'required|integer'],
		['inventory_id', 'Inventory ID', 'required|integer'],
		['order_id', 'order_id', 'integer'],
		['status', 'Status', 'required|integer'],
		['comment', 'Comment', 'required'],
		['private_review', 'Private Review', 'required|text'],
		['rating', 'Rating', 'required'],

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


	public function rating_mapping()
	{
		return [
			1 => '1/10',
			2 => '2/10',
			3 => '3/10',
			4 => '4/10',
			5 => '5/10',
			6 => '6/10',
			7 => '7/10',
			8 => '8/10',
			9 => '9/10',
			10 => '10/10',
		];
	}
}
