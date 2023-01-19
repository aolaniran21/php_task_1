<?php defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2021*/
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['v1/api/admin/inventory_gallery_image_list/delete'] = 'Admin/Admin_inventory_gallery_image_list_controller/delete';
$route['admin/inventory_gallery_image_list/view/(:num)'] = 'Admin/Admin_inventory_gallery_image_list_controller/view/$1';
$route['admin/inventory_gallery_image_list/edit/(:num)'] = 'Admin/Admin_inventory_gallery_image_list_controller/edit/$1';
$route['v1/api/admin/inventory_attribute/bulk_delete'] = 'Admin/Admin_inventory_attribute_controller/bulk_delete';
$route['v1/api/home/load_items_by_category_select'] = 'Guest/Home_controller/load_items_by_category_select';
$route['admin/inventory_gallery_image_list/(:num)'] = 'Admin/Admin_inventory_gallery_image_list_controller/index/$1';
$route['v1/api/admin/spreadsheet/api_bulk_delete'] = 'Admin/Admin_spreadsheet_api_controller/api_bulk_delete';
$route['v1/api/admin/inventory_attribute/delete'] = 'Admin/Admin_inventory_attribute_controller/delete';
$route['v1/api/admin/attribute_type/bulk_delete'] = 'Admin/Admin_attribute_type_controller/bulk_delete';
$route['admin/inventory_gallery_image_list/add'] = 'Admin/Admin_inventory_gallery_image_list_controller/add';
$route['admin/inventory_attribute/view/(:num)'] = 'Admin/Admin_inventory_attribute_controller/view/$1';
$route['admin/inventory_attribute/edit/(:num)'] = 'Admin/Admin_inventory_attribute_controller/edit/$1';
$route['v1/api/admin/transaction/bulk_delete'] = 'Admin/Admin_transaction_controller/bulk_delete';
$route['v1/api/admin/spreadsheet/edit/(:num)'] = 'Admin/Admin_spreadsheet_api_controller/edit/$1';
$route['v1/api/admin/spreadsheet/bulk_delete'] = 'Admin/Admin_spreadsheet_controller/bulk_delete';
$route['v1/api/member/user_card/set_default'] = 'Member/Member_user_card_controller/set_default';
$route['v1/api/admin/test_image/bulk_delete'] = 'Admin/Admin_test_image_controller/bulk_delete';
$route['v1/api/admin/marketing/bulk_delete'] = 'Admin/Admin_marketing_controller/bulk_delete';
$route['v1/api/admin/attribute_type/delete'] = 'Admin/Admin_attribute_type_controller/delete';
$route['admin/inventory_gallery_image_list'] = 'Admin/Admin_inventory_gallery_image_list_controller/index/0';
$route['v1/api/admin/settings/edit/(:num)'] = 'Admin/Admin_setting_controller/edit/$1';
$route['v1/api/update_spreadsheet/(:num)'] = 'Admin/Admin_spreadsheet_controller/spreadsheet_data/$1';
$route['v1/api/member/transaction/delete'] = 'Member/Member_transaction_controller/delete';
$route['v1/api/get_marketing_slug/(:any)'] = 'Admin/Marketing_controller/generate_marketing_slug/$1';
$route['member/post_review/(:num)/(:num)'] = 'Member/Member_inventory_controller/post_review/$1/$2';
$route['admin/inventory_attribute/(:num)'] = 'Admin/Admin_inventory_attribute_controller/index/$1';
$route['admin/attribute_type/view/(:num)'] = 'Admin/Admin_attribute_type_controller/view/$1';
$route['admin/attribute_type/edit/(:num)'] = 'Admin/Admin_attribute_type_controller/edit/$1';
$route['v1/api/admin/transaction/delete'] = 'Admin/Admin_transaction_controller/delete';
$route['v1/api/admin/spreadsheet/(:num)'] = 'Admin/Admin_spreadsheet_api_controller/index/$1';
$route['v1/api/admin/delete_order_notes'] = 'Admin/Admin_order_controller/delete_order_notes';
$route['v1/api/member/user_card/delete'] = 'Member/Member_user_card_controller/delete';
$route['v1/api/member/inventory/delete'] = 'Member/Member_inventory_controller/delete';
$route['v1/api/home/get_all_categories'] = 'Guest/Home_controller/get_all_categories';
$route['v1/api/admin/users/view/(:num)'] = 'Admin/Admin_credential_api_controller/view/$1';
$route['v1/api/admin/users/edit/(:num)'] = 'Admin/Admin_credential_api_controller/edit/$1';
$route['v1/api/admin/suggestion/delete'] = 'Admin/Admin_suggestion_controller/delete';
$route['member/transaction/view/(:num)'] = 'Member/Member_transaction_controller/view/$1';
$route['member/transaction/edit/(:num)'] = 'Member/Member_transaction_controller/edit/$1';
$route['admin/suggestion/delete/(:num)'] = 'Admin/Admin_suggestion_controller/delete/$1';
$route['v1/api/admin/professor/delete'] = 'Admin/Admin_professor_controller/delete';
$route['v1/api/admin/marketing/delete'] = 'Admin/Admin_marketing_controller/delete';
$route['v1/api/admin/inventory/delete'] = 'Admin/Admin_inventory_controller/delete';
$route['v1/api/admin/edit_order_notes'] = 'Admin/Admin_order_controller/edit_order_notes';
$route['admin/transaction/view/(:num)'] = 'Admin/Admin_transaction_controller/view/$1';
$route['admin/transaction/edit/(:num)'] = 'Admin/Admin_transaction_controller/edit/$1';
$route['admin/spreadsheet/edit/(:num)'] = 'Admin/Admin_spreadsheet_controller/edit/$1';
$route['admin/inventory_attribute/add'] = 'Admin/Admin_inventory_attribute_controller/add';
$route['v1/api/member/suggestion/add'] = 'Member/Member_inventory_controller/add_suggestion';
$route['v1/api/admin/textbook/delete'] = 'Admin/Admin_textbook_controller/delete';
$route['v1/api/admin/spreadsheet/add'] = 'Admin/Admin_spreadsheet_api_controller/add';
$route['v1/api/admin/sms/view/(:num)'] = 'Admin/Admin_sms_api_controller/view/$1';
$route['v1/api/admin/sms/edit/(:num)'] = 'Admin/Admin_sms_api_controller/edit/$1';
$route['v1/api/admin/category/delete'] = 'Admin/Admin_category_controller/delete';
$route['v1/api/admin/add_order_notes'] = 'Admin/Admin_order_controller/add_order_notes';
$route['member/purchases/view/(:num)'] = 'Member/Member_purchases_controller/view/$1';
$route['member/inventory/view/(:num)'] = 'Member/Member_inventory_controller/view/$1';
$route['admin/test_image/edit/(:num)'] = 'Admin/Admin_test_image_controller/edit/$1';
$route['admin/suggestion/view/(:num)'] = 'Admin/Admin_suggestion_controller/view/$1';
$route['admin/contact_us/view/(:num)'] = 'Admin/Admin_contact_us_controller/view/$1';
$route['v1/api/admin/content/delete'] = 'Admin/Admin_content_controller/delete';
$route['member/review/(:num)/(:num)'] = 'Member/Member_inventory_controller/review/$1/$2';
$route['member/dispute_order/(:num)'] = 'Member/Member_inventory_controller/dispute_order';
$route['admin/ticket/resolve/(:num)'] = 'Admin/Admin_ticket_controller/resolve/$1';
$route['admin/professor/edit/(:num)'] = 'Admin/Admin_professor_controller/edit/$1';
$route['admin/marketing/view/(:num)'] = 'Admin/Admin_marketing_controller/view/$1';
$route['admin/marketing/edit/(:num)'] = 'Admin/Admin_marketing_controller/edit/$1';
$route['admin/inventory/view/(:num)'] = 'Admin/Admin_inventory_controller/view/$1';
$route['admin/inventory/edit/(:num)'] = 'Admin/Admin_inventory_controller/edit/$1';
$route['admin/attribute_type/(:num)'] = 'Admin/Admin_attribute_type_controller/index/$1';
$route['v1/api/update_sheet/(:num)'] = 'Admin/Admin_spreadsheet_controller/update_sheet/$1';
$route['v1/api/admin/school/delete'] = 'Admin/Admin_school_controller/delete';
$route['member/dispute/view/(:num)'] = 'Member/Member_dispute_controller/view/$1';
$route['admin/textbook/edit/(:num)'] = 'Admin/Admin_textbook_controller/edit/$1';
$route['admin/review/reject/(:num)'] = 'Admin/Admin_review_controller/reject/$1';
$route['admin/payout/mark_all_paid'] = 'Admin/Admin_payout_controller/mark_all_paid';
$route['admin/category/edit/(:num)'] = 'Admin/Admin_category_controller/edit/$1';
$route['v1/api/member/cart/delete'] = 'Member/Member_cart_controller/delete';
$route['v1/api/file/import/(:any)'] = 'Guest/Image_controller/file_import/$1';
$route['v1/api/admin/users/(:num)'] = 'Admin/Admin_credential_api_controller/index/$1';
$route['v1/api/admin/image/delete'] = 'Admin/Admin_image_controller/delete';
$route['v1/api/admin/color/delete'] = 'Admin/Admin_color_controller/delete';
$route['v1/api/admin/class/delete'] = 'Admin/Admin_class_controller/delete';
$route['member/update_credentials'] = 'Member/Member_profile_controller/update_credentials';
$route['member/transaction/(:num)'] = 'Member/Member_transaction_controller/index/$1';
$route['admin/setting/edit/(:num)'] = 'Admin/Admin_setting_controller/edit/$1';
$route['admin/order/refund/(:num)'] = 'Admin/Admin_order_controller/refund/$1';
$route['admin/inventory_attribute'] = 'Admin/Admin_inventory_attribute_controller/index/0';
$route['admin/dispute/edit/(:num)'] = 'Admin/Admin_dispute_controller/edit/$1';
$route['admin/content/edit/(:num)'] = 'Admin/Admin_content_controller/edit/$1';
$route['v1/api/admin/spreadsheet'] = 'Admin/Admin_spreadsheet_api_controller/index/0';
$route['v1/api/admin/cart/delete'] = 'Admin/Admin_cart_controller/delete';
$route['admin/update_credentials'] = 'Admin/Admin_profile_controller/update_credentials';
$route['admin/transaction/(:num)'] = 'Admin/Admin_transaction_controller/index/$1';
$route['admin/spreadsheet/(:num)'] = 'Admin/Admin_spreadsheet_controller/index/$1';
$route['admin/school/edit/(:num)'] = 'Admin/Admin_school_controller/edit/$1';
$route['admin/refund/edit/(:num)'] = 'Admin/Admin_refund_controller/edit/$1';
$route['admin/payout/paid/(:num)'] = 'Admin/Admin_payout_controller/paid/$1';
$route['admin/emails/view/(:num)'] = 'Admin/Admin_email_controller/view/$1';
$route['admin/emails/edit/(:num)'] = 'Admin/Admin_email_controller/edit/$1';
$route['admin/attribute_type/add'] = 'Admin/Admin_attribute_type_controller/add';
$route['v1/api/admin/tax/delete'] = 'Admin/Admin_tax_controller/delete';
$route['member/user_card/(:num)'] = 'Member/Member_user_card_controller/index/$1';
$route['member/purchases/(:num)'] = 'Member/Member_purchases_controller/index/$1';
$route['member/inventory/(:num)'] = 'Member/Member_inventory_controller/index/$1';
$route['member/cart/view/(:num)'] = 'Member/Member_cart_controller/view/$1';
$route['member/cart/edit/(:num)'] = 'Member/Member_cart_controller/edit/$1';
$route['admin/users/view/(:num)'] = 'Admin/Admin_credential_controller/view/$1';
$route['admin/users/edit/(:num)'] = 'Admin/Admin_credential_controller/edit/$1';
$route['admin/test_image/(:num)'] = 'Admin/Admin_test_image_controller/index/$1';
$route['admin/suggestion/(:num)'] = 'Admin/Admin_suggestion_controller/index/$1';
$route['admin/order/view/(:num)'] = 'Admin/Admin_order_controller/view/$1';
$route['admin/image/view/(:num)'] = 'Admin/Admin_image_controller/view/$1';
$route['admin/contact_us/(:num)'] = 'Admin/Admin_contact_us_controller/index/$1';
$route['admin/color/view/(:num)'] = 'Admin/Admin_color_controller/view/$1';
$route['admin/color/edit/(:num)'] = 'Admin/Admin_color_controller/edit/$1';
$route['admin/class/edit/(:num)'] = 'Admin/Admin_class_controller/edit/$1';
$route['v1/api/member/purchase'] = 'Member/Member_inventory_controller/purchase';
$route['v1/api/admin/users/add'] = 'Admin/Admin_credential_api_controller/add';
$route['member/transaction/add'] = 'Member/Member_transaction_controller/add';
$route['admin/user_card/(:num)'] = 'Admin/Admin_user_card_controller/index/$1';
$route['admin/professor/(:num)'] = 'Admin/Admin_professor_controller/index/$1';
$route['admin/marketing/(:num)'] = 'Admin/Admin_marketing_controller/index/$1';
$route['admin/inventory/(:num)'] = 'Admin/Admin_inventory_controller/index/$1';
$route['admin/cart/view/(:num)'] = 'Admin/Admin_cart_controller/view/$1';
$route['admin/cart/edit/(:num)'] = 'Admin/Admin_cart_controller/edit/$1';
$route['member/dispute/(:num)'] = 'Member/Member_dispute_controller/index/$1';
$route['admin/transaction/add'] = 'Admin/Admin_transaction_controller/add';
$route['admin/textbook/(:num)'] = 'Admin/Admin_textbook_controller/index/$1';
$route['admin/tax/view/(:num)'] = 'Admin/Admin_tax_controller/view/$1';
$route['admin/tax/edit/(:num)'] = 'Admin/Admin_tax_controller/edit/$1';
$route['admin/spreadsheet/add'] = 'Admin/Admin_spreadsheet_controller/add';
$route['admin/sms/view/(:num)'] = 'Admin/Admin_sms_controller/view/$1';
$route['admin/sms/edit/(:num)'] = 'Admin/Admin_sms_controller/edit/$1';
$route['admin/category/(:num)'] = 'Admin/Admin_category_controller/index/$1';
$route['v1/api/stripe_events'] = 'Admin/Stripe_webhooks_api_controller/index';
$route['v1/api/image/get_all'] = 'Guest/Image_controller/get_all_images';
$route['v1/api/assets/(:num)'] = 'Guest/Image_controller/paginate/$1';
$route['v1/api/admin/sms/add'] = 'Admin/Admin_sms_api_controller/add';
$route['v1/api/add_inventory'] = 'Member/Member_inventory_controller/add_inventory';
$route['terms_and_conditions'] = 'Guest/Home_controller/terms_and_conditions';
$route['member/user_card/add'] = 'Member/Member_user_card_controller/add';
$route['member/ticket/(:num)'] = 'Member/Member_ticket_controller/index/$1';
$route['member/inventory/add'] = 'Member/Member_inventory_controller/add';
$route['admin/test_image/add'] = 'Admin/Admin_test_image_controller/add';
$route['admin/setting/(:num)'] = 'Admin/Admin_setting_controller/index/$1';
$route['admin/dispute/(:num)'] = 'Admin/Admin_dispute_controller/index/$1';
$route['admin/content/(:num)'] = 'Admin/Admin_content_controller/index/$1';
$route['admin/attribute_type'] = 'Admin/Admin_attribute_type_controller/index/0';
$route['v1/api/image/upload'] = 'Guest/Image_controller';
$route['v1/api/autocomplete'] = 'Guest/Home_controller/autocomplete';
$route['member/sales/(:num)'] = 'Member/Member_sales_controller/index/$1';
$route['member/reset/(:num)'] = 'Member/Member_reset_controller/index/$1';
$route['member/post_setting'] = 'Member/Member_inventory_controller/post_setting';
$route['member/post_dispute'] = 'Member/Member_inventory_controller/post_dispute';
$route['admin/ticket/(:num)'] = 'Admin/Admin_ticket_controller/index/$1';
$route['admin/school/(:num)'] = 'Admin/Admin_school_controller/index/$1';
$route['admin/review/(:num)'] = 'Admin/Admin_review_controller/index/$1';
$route['admin/refund/(:num)'] = 'Admin/Admin_refund_controller/index/$1';
$route['admin/professor/add'] = 'Admin/Admin_professor_controller/add';
$route['admin/payout/(:num)'] = 'Admin/Admin_payout_controller/index/$1';
$route['admin/marketing/add'] = 'Admin/Admin_marketing_controller/add';
$route['admin/inventory/add'] = 'Admin/Admin_inventory_controller/add';
$route['admin/emails/(:num)'] = 'Admin/Admin_email_controller/index/$1';
$route['v1/api/preview_csv'] = 'Guest/Image_controller/preview_csv';
$route['v1/api/file/upload'] = 'Guest/Image_controller/file_upload';
$route['v1/api/admin/users'] = 'Admin/Admin_credential_api_controller/index/0';
$route['member/transaction'] = 'Member/Member_transaction_controller/index/0';
$route['member/cart/(:num)'] = 'Member/Member_cart_controller/index/$1';
$route['admin/users/(:num)'] = 'Admin/Admin_credential_controller/index/$1';
$route['admin/textbook/add'] = 'Admin/Admin_textbook_controller/add';
$route['admin/order/(:num)'] = 'Admin/Admin_order_controller/index/$1';
$route['admin/image/(:num)'] = 'Admin/Admin_image_controller/index/$1';
$route['admin/color/(:num)'] = 'Admin/Admin_color_controller/index/$1';
$route['admin/class/(:num)'] = 'Admin/Admin_class_controller/index/$1';
$route['admin/category/add'] = 'Admin/Admin_category_controller/add';
$route['v1/api/professors'] = 'Guest/Api_controller/get_professors';
$route['v1/api/get_review'] = 'Guest/Home_controller/get_review';
$route['member/credential'] = 'Member/Member_profile_credential_controller';
$route['admin/transaction'] = 'Admin/Admin_transaction_controller/index/0';
$route['admin/spreadsheet'] = 'Admin/Admin_spreadsheet_controller/index/0';
$route['admin/dispute/add'] = 'Admin/Admin_dispute_controller/add';
$route['admin/content/add'] = 'Admin/Admin_content_controller/add';
$route['admin/cart/(:num)'] = 'Admin/Admin_cart_controller/index/$1';
$route['v1/api/textbooks'] = 'Guest/Api_controller/get_textbooks';
$route['v1/api/years'] = 'Guest/Api_controller/get_years';
$route['v1/api/admin/sms'] = 'Admin/Admin_sms_api_controller';
$route['member/user_card'] = 'Member/Member_user_card_controller/index/0';
$route['member/purchases'] = 'Member/Member_purchases_controller/index/0';
$route['member/inventory'] = 'Member/Member_inventory_controller/index/0';
$route['member/dashboard'] = 'Member/Member_dashboard_controller';
$route['admin/test_image'] = 'Admin/Admin_test_image_controller/index/0';
$route['admin/tax/(:num)'] = 'Admin/Admin_tax_controller/index/$1';
$route['admin/suggestion'] = 'Admin/Admin_suggestion_controller/index/0';
$route['admin/school/add'] = 'Admin/Admin_school_controller/add';
$route['admin/refund/add'] = 'Admin/Admin_refund_controller/add';
$route['admin/emails/add'] = 'Admin/Admin_email_controller/add';
$route['admin/credential'] = 'Admin/Admin_profile_credential_controller';
$route['admin/contact_us'] = 'Admin/Admin_contact_us_controller/index/0';
$route['member/register'] = 'Member/Member_register_controller';
$route['member/cart/add'] = 'Member/Member_cart_controller/add';
$route['admin/users/add'] = 'Admin/Admin_credential_controller/add';
$route['admin/user_card'] = 'Admin/Admin_user_card_controller/index/0';
$route['admin/professor'] = 'Admin/Admin_professor_controller/index/0';
$route['admin/marketing'] = 'Admin/Admin_marketing_controller/index/0';
$route['admin/inventory'] = 'Admin/Admin_inventory_controller/index/0';
$route['admin/image/add'] = 'Admin/Admin_image_controller/add';
$route['admin/dashboard'] = 'Admin/Admin_dashboard_controller';
$route['admin/color/add'] = 'Admin/Admin_color_controller/add';
$route['admin/class/add'] = 'Admin/Admin_class_controller/add';
$route['v1/api/schools'] = 'Guest/Api_controller/get_schools';
$route['v1/api/courses'] = 'Guest/Api_controller/get_courses';
$route['v1/api/contact'] = 'Guest/Home_controller/contact';
$route['privacy_policy'] = 'Guest/Home_controller/privacy_policy';
$route['preview/(:num)'] = 'Guest/Home_controller/preview/$1';
$route['member/setting'] = 'Member/Member_inventory_controller/setting';
$route['member/profile'] = 'Member/Member_profile_controller';
$route['member/dispute'] = 'Member/Member_dispute_controller/index/0';
$route['admin/textbook'] = 'Admin/Admin_textbook_controller/index/0';
$route['admin/settings'] = 'Admin/Admin_setting_controller/index';
$route['admin/category'] = 'Admin/Admin_category_controller/index/0';
$route['admin/cart/add'] = 'Admin/Admin_cart_controller/add';
$route['v1/api/assets'] = 'Guest/Image_controller/paginate/0';
$route['member/ticket'] = 'Member/Member_ticket_controller/index/0';
$route['member/logout'] = 'Member/Member_login_controller/logout';
$route['member/forgot'] = 'Member/Member_forgot_controller';
$route['admin/tax/add'] = 'Admin/Admin_tax_controller/add';
$route['admin/sms/add'] = 'Admin/Admin_sms_controller/add';
$route['admin/setting'] = 'Admin/Admin_setting_controller/index/0';
$route['admin/profile'] = 'Admin/Admin_profile_controller';
$route['admin/dispute'] = 'Admin/Admin_dispute_controller/index/0';
$route['admin/content'] = 'Admin/Admin_content_controller/index/0';
$route['member/sales'] = 'Member/Member_sales_controller/index/0';
$route['member/login'] = 'Member/Member_login_controller';
$route['health_check'] = 'Health_check_controller/index';
$route['admin/ticket'] = 'Admin/Admin_ticket_controller/index/0';
$route['admin/school'] = 'Admin/Admin_school_controller/index/0';
$route['admin/review'] = 'Admin/Admin_review_controller/index/0';
$route['admin/refund'] = 'Admin/Admin_refund_controller/index/0';
$route['admin/payout'] = 'Admin/Admin_payout_controller/index/0';
$route['admin/logout'] = 'Admin/Admin_login_controller/logout';
$route['admin/emails'] = 'Admin/Admin_email_controller/index/0';
$route['member/cart'] = 'Member/Member_cart_controller/index/0';
$route['admin/users'] = 'Admin/Admin_credential_controller/index/0';
$route['admin/order'] = 'Admin/Admin_order_controller/index/0';
$route['admin/login'] = 'Admin/Admin_login_controller';
$route['admin/image'] = 'Admin/Admin_image_controller/index/0';
$route['admin/color'] = 'Admin/Admin_color_controller/index/0';
$route['admin/class'] = 'Admin/Admin_class_controller/index/0';
$route['buy/(:num)'] = 'Guest/Home_controller/buy/$1';
$route['admin/cart'] = 'Admin/Admin_cart_controller/index/0';
$route['member/me'] = 'Member/Member_me_controller/me';
$route['admin/tax'] = 'Admin/Admin_tax_controller/index/0';
$route['admin/sms'] = 'Admin/Admin_sms_controller';
$route['facebook'] = 'Member/Member_social_login_controller/facebook';
$route['admin/me'] = 'Admin/Admin_me_controller/me';
$route['a/(:any)'] = 'Admin/Marketing_controller/generate_custom_marketing_page/$1';
$route['migrate'] = 'Migrate';
$route['contact'] = 'Guest/Home_controller/contact';
$route['google'] = 'Member/Member_social_login_controller/google';
$route['(:num)'] = 'Guest/Home_controller/index/$1';
$route['about'] = 'Guest/Home_controller/about';
$route['sell'] = 'Guest/Home_controller/sell';
$route['buy'] = 'Guest/Home_controller/buy';

$route['404_override'] = 'Custom404';
$route['translate_uri_dashes'] = false;
$route['default_controller'] = 'Welcome/index';
