<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['system_site_short_name']='ems';
$config['offline_controllers']=array('home','sys_site_offline');
$config['external_controllers']=array('home');//user can use them without login
$config['system_max_actions']=8;

$config['system_status_yes']='Yes';
$config['system_status_no']='No';
$config['system_status_active']='Active';
$config['system_status_inactive']='In-Active';
$config['system_status_delete']='Deleted';
$config['system_status_closed']='Closed';
$config['system_status_pending']='Pending';
$config['system_status_rollback']='Rollback';
$config['system_status_forwarded']='Forwarded';
$config['system_status_complete']='Completed';
$config['system_status_incomplete']='Incomplete';
$config['system_status_approved']='Approved';
$config['system_status_delivered']='Delivered';
$config['system_status_received']='Received';
$config['system_status_rejected']='Rejected';
$config['system_status_initial']='Initial';
$config['system_status_additional']='Additional';
$config['system_status_paid']='Paid';
$config['system_status_present']='Present';
$config['system_status_absent']='Absent';
$config['system_status_cl']='Casual Leave';


$config['system_base_url_profile_picture']='http://50.116.76.180/login/';

$config['system_customer_type_outlet_id']=1;
$config['system_customer_type_customer_id']=2;

//System Configuration
$config['system_purpose_fms_menu_odd_color']='fms_menu_odd_color';
$config['system_purpose_fms_menu_even_color']='fms_menu_even_color';

//System File Type
$config['system_file_type_image']='Image';
$config['system_file_type_video']='Video';
$config['system_file_type_video_ext']='wmv|mp4|mov|ftv|mkv|3gp|avi';
$config['system_file_type_video_max_size']=102400;//100mb