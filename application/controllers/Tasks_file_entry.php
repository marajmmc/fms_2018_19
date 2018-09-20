<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks_file_entry extends Root_Controller
{
    public $message;
    public $permissions;
    public $controller_url;

    public function __construct()
    {
        parent::__construct();
        $this->message = "";
        $this->permissions = User_helper::get_permission(get_class($this));
        $this->controller_url = strtolower(get_class($this));
    }

    public function index($action = "list", $id = 0)
    {
        if ($action == 'list')
        {
            $this->system_list();
        }
        elseif ($action == 'get_items')
        {
            $this->system_get_items();
        }
        elseif ($action == "add")
        {
            $this->system_add();
        }
        elseif ($action == 'edit')
        {
            $this->system_edit($id);
        }
        elseif ($action == 'details')
        {
            $this->system_details($id);
        }
        elseif ($action == 'save')
        {
            $this->system_save();
        }
        elseif ($action == 'save_new_file')
        {
            $this->system_save_new_file();
        }
        elseif ($action == "set_preference")
        {
            $this->system_set_preference();
        }
        elseif ($action == "save_preference")
        {
            System_helper::save_preference();
        }
        else
        {
            $this->system_list();
        }
    }

    private function get_preference_headers($method)
    {
        if ($method == 'list')
        {
            $data['id'] = 1;
            $data['file_name'] = 1;
            $data['responsible_employee'] = 1;
            $data['date_opening'] = 1;
            $data['file_status'] = 1;
            $data['number_of_page'] = 1;
            $data['hc_location'] = 1;
            $data['category_name'] = 1;
            $data['sub_category_name'] = 1;
            $data['class_name'] = 1;
            $data['type_name'] = 1;
            $data['company_name'] = 1;
            $data['department_name'] = 1;
            $data['ordering'] = 1;
            $data['status'] = 1;
        }
        else
        {
            $data = array();
        }

        return $data;
    }

    private function system_list()
    {
        $user = User_helper::get_user();
        $method = 'list';
        if (isset($this->permissions['action0']) && ($this->permissions['action0'] == 1))
        {
            $data['title'] = 'Permitted Files List for File Entry';
            $data['system_preference_items'] = System_helper::get_preference($user->user_id, $this->controller_url, $method, $this->get_preference_headers($method));
            $ajax['status'] = true;
            $ajax['system_content'][] = array('id' => '#system_content', 'html' => $this->load->view($this->controller_url . '/list', $data, true));
            if ($this->message)
            {
                $ajax['system_message'] = $this->message;
            }
            $ajax['system_page_url'] = site_url($this->controller_url);
            $this->json_return($ajax);
        }
        else
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->lang->line('YOU_DONT_HAVE_ACCESS');
            $this->json_return($ajax);
        }
    }

    private function system_get_items()
    {
        $current_records = $this->input->post('total_records');
        if (!$current_records)
        {
            $current_records = 0;
        }
        $pagesize = $this->input->post('pagesize');
        if (!$pagesize)
        {
            $pagesize = 40;
        }
        else
        {
            $pagesize = $pagesize * 2;
        }
        $user = User_helper::get_user();
        $this->db->from($this->config->item('table_fms_setup_file_name') . ' file_name');
        $this->db->select('file_name.id,file_name.name file_name,file_name.date_start date_opening,file_name.status_file file_status,file_name.status,file_name.ordering');

        $this->db->join($this->config->item('table_fms_setup_assign_file_user_group') . ' assigned_file', 'assigned_file.id_file=file_name.id');
        $this->db->join($this->config->item('table_fms_setup_file_type') . ' type', 'type.id=file_name.id_type');
        $this->db->select('type.name type_name');

        $this->db->join($this->config->item('table_fms_setup_file_class') . ' class', 'class.id=type.id_class');
        $this->db->select('class.name class_name');

        $this->db->join($this->config->item('table_fms_setup_file_sub_category') . ' sub_category', 'sub_category.id=class.id_sub_category');
        $this->db->select('sub_category.name sub_category_name');

        $this->db->join($this->config->item('table_fms_setup_file_category') . ' category', 'category.id=sub_category.id_category');
        $this->db->select('category.name category_name');

        $this->db->join($this->config->item('table_fms_setup_file_hc_location') . ' hc_location', 'hc_location.id=file_name.id_hc_location');
        $this->db->select('hc_location.name hc_location');

        $this->db->join($this->config->item('table_login_setup_user_info') . ' user_info', 'user_info.user_id=file_name.employee_id', 'left');
        $this->db->join($this->config->item('table_login_setup_user') . ' user', 'user.id=user_info.user_id');
        $this->db->select('CONCAT(user_info.name," - ",user.employee_id) responsible_employee');

        $this->db->join($this->config->item('table_login_setup_department') . ' department', 'department.id=file_name.id_department', 'left');
        $this->db->select('department.name department_name');

        $this->db->join($this->config->item('table_login_setup_company') . ' company', 'company.id=file_name.id_company', 'left');
        $this->db->select('company.full_name company_name');

        $this->db->join($this->config->item('table_fms_tasks_digital_file') . ' digital_file', 'digital_file.id_file_name=file_name.id', 'left');
        $this->db->select('SUM(CASE WHEN digital_file.status="' . $this->config->item('system_status_active') . '" AND SUBSTRING(digital_file.mime_type,1,5)="image" THEN 1 ELSE 0 END) number_of_page');

        $this->db->where('category.status', $this->config->item('system_status_active'));
        $this->db->where('sub_category.status', $this->config->item('system_status_active'));
        $this->db->where('class.status', $this->config->item('system_status_active'));
        $this->db->where('type.status', $this->config->item('system_status_active'));
        $this->db->where('file_name.status !=', $this->config->item('system_status_delete'));
        $this->db->where('user_info.revision', 1);
        $this->db->where('assigned_file.user_group_id', $user->user_group);
        $this->db->where('assigned_file.revision', 1);
        $this->db->order_by('file_name.id', 'DESC');
        $this->db->order_by('category.ordering');
        $this->db->order_by('sub_category.ordering');
        $this->db->order_by('class.ordering');
        $this->db->order_by('type.ordering');
        $this->db->order_by('file_name.ordering');
        $this->db->group_by('file_name.id');
        $this->db->limit($pagesize, $current_records);
        $items = $this->db->get()->result_array();
        foreach ($items as &$item)
        {
            $item['date_opening'] = System_helper::display_date($item['date_opening']);
        }
        $this->json_return($items);
    }

    private function system_add()
    {
        if (isset($this->permissions['action1']) && ($this->permissions['action1'] == 1))
        {
            $user = User_helper::get_user();
            $data = array();

            $this->db->from($this->config->item('table_fms_setup_file_name') . ' file_name');
            $this->db->join($this->config->item('table_fms_setup_assign_file_user_group') . ' file_user_group', 'file_user_group.id_file = file_name.id');

            $this->db->join($this->config->item('table_fms_setup_file_type') . ' file_type', 'file_type.id = file_name.id_type');
            $this->db->select('file_type.id type_id, file_type.name type_name');

            $this->db->join($this->config->item('table_fms_setup_file_class') . ' file_class', 'file_class.id = file_type.id_class');
            $this->db->select('file_class.id class_id, file_class.name class_name');

            $this->db->join($this->config->item('table_fms_setup_file_sub_category') . ' file_sub_category', 'file_sub_category.id = file_class.id_sub_category');
            $this->db->select('file_sub_category.id sub_category_id, file_sub_category.name sub_category_name');

            $this->db->join($this->config->item('table_fms_setup_file_category') . ' file_category', 'file_category.id = file_sub_category.id_category');
            $this->db->select('file_category.id category_id, file_category.name category_name');

            $this->db->where('file_user_group.user_group_id', $user->user_group);
            $this->db->where('file_user_group.action0', 1);
            $this->db->where('file_user_group.revision', 1);
            $this->db->where('file_category.status', $this->config->item('system_status_active'));
            $this->db->where('file_sub_category.status', $this->config->item('system_status_active'));
            $this->db->where('file_class.status', $this->config->item('system_status_active'));
            $this->db->where('file_type.status', $this->config->item('system_status_active'));
            $this->db->where('file_name.status', $this->config->item('system_status_active'));

            $this->db->order_by('file_category.ordering');
            $this->db->order_by('file_sub_category.ordering');
            $this->db->order_by('file_class.ordering');
            $this->db->order_by('file_type.ordering');

            $this->db->group_by('file_type.id');
            $results = $this->db->get()->result_array();
            if (sizeof($results) < 1)
            {
                $ajax['status'] = false;
                $ajax['system_message'] = $this->lang->line('YOU_DONT_HAVE_ACCESS');
                $this->json_return($ajax);
            }
            $data['categories'] = array();
            $data['sub_categories'] = array();
            $data['classes'] = array();
            $data['types'] = array();
            $data['sub_category_counter'] = 0;
            $data['class_counter'] = 0;
            $data['type_counter'] = count($results);
            foreach ($results as $result)
            {
                $data['categories'][$result['category_id']] = array('value' => $result['category_id'], 'text' => $result['category_name']);
                if (!isset($data['sub_categories'][$result['category_id']][$result['sub_category_id']]))
                {
                    $data['sub_categories'][$result['category_id']][$result['sub_category_id']] = array('value' => $result['sub_category_id'], 'text' => $result['sub_category_name']);
                    ++$data['sub_category_counter'];
                }
                if (!isset($data['classes'][$result['sub_category_id']][$result['class_id']]))
                {
                    $data['classes'][$result['sub_category_id']][$result['class_id']] = array('value' => $result['class_id'], 'text' => $result['class_name']);
                    ++$data['class_counter'];
                }
                $data['types'][$result['class_id']][$result['type_id']] = array('value' => $result['type_id'], 'text' => $result['type_name']);
            }

            $this->db->from($this->config->item('table_login_setup_company') . ' setup_company');
            $this->db->select('setup_company.id value, setup_company.full_name text');
            $this->db->join($this->config->item('table_login_setup_users_company') . ' setup_users_company', 'setup_users_company.company_id = setup_company.id');
            $this->db->where('setup_company.status', $this->config->item('system_status_active'));
            $this->db->where('setup_users_company.revision', 1);
            $this->db->where('setup_users_company.user_id', $user->user_id);
            $this->db->order_by('setup_company.ordering');
            $data['companies'] = $this->db->get()->result_array();

            if ($user->department_id > 0)
            {
                $result = Query_helper::get_info($this->config->item('table_login_setup_department'), 'name', array('id=' . $user->department_id), 1, 0);
                $data['department'] = array('value' => $user->department_id, 'text' => $result['name']);
            }
            else
            {
                $data['department'] = array('value' => '', 'text' => 'Not Assigned');
            }
            $data['employee'] = array('value' => $user->user_id, 'text' => $user->name);
            $data['hc_locations'] = Query_helper::get_info($this->config->item('table_fms_setup_file_hc_location'), 'id value,name text', array(), 0, 0, 'ordering');


            $data['title'] = 'Create New ' . $this->lang->line('LABEL_FILE_NAME');
            $ajax['status'] = true;
            $ajax['system_content'][] = array("id" => "#system_content", "html" => $this->load->view($this->controller_url . "/add", $data, true));
            if ($this->message)
            {
                $ajax['system_message'] = $this->message;
            }
            $ajax['system_page_url'] = site_url($this->controller_url . '/index/add');
            $this->json_return($ajax);
        }
        else
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->json_return($ajax);
        }
    }

    private function system_edit($id)
    {
        if ((isset($this->permissions['action2']) && ($this->permissions['action2'] == 1)))
        {
            if ($id > 0)
            {
                $item_id = $id;
            }
            else
            {
                $item_id = $this->input->post('id');
            }
            $file_permissions = $this->get_file_permission($item_id);
            if ($file_permissions['action1'] == 1 || $file_permissions['action2'] == 1 || $file_permissions['action3'] == 1)
            {
                $data['item'] = $this->get_file_info($item_id);
                $data['file_permissions'] = $file_permissions;
                $data['file_items'] = $this->get_file_items($item_id);
                $results = Query_helper::get_info($this->config->item('table_fms_tasks_digital_file'), '*', array('id_file_name =' . $item_id, 'status ="' . $this->config->item('system_status_active') . '"'));
                $data['stored_files'] = array();
                foreach ($results as $result)
                {
                    $data['stored_files'][$result['id_file_item']][] = $result;
                }
                $data['hc_locations'] = Query_helper::get_info($this->config->item('table_fms_setup_file_hc_location'), array('id value', 'name text'), array('status ="' . $this->config->item('system_status_active') . '"'), 0, 0, array('ordering ASC'));
                $data['users'] = System_helper::get_users_info(array());
                $data['title'] = "Entry Pages For::" . $data['item']['name'];
                $ajax['status'] = true;
                $ajax['system_content'][] = array('id' => '#system_content', 'html' => $this->load->view($this->controller_url . '/edit', $data, true));
                if ($this->message)
                {
                    $ajax['system_message'] = $this->message;
                }
                $ajax['system_page_url'] = site_url($this->controller_url . '/index/edit/' . $item_id);
                $this->json_return($ajax);
            }
            else
            {
                System_helper::invalid_try('Edit', $item_id, 'Dont have file permission');
                $ajax['status'] = false;
                $ajax['system_message'] = 'Invalid Try';
                $this->json_return($ajax);
            }
        }
        else
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->lang->line('YOU_DONT_HAVE_ACCESS');
            $this->json_return($ajax);
        }
    }

    private function get_file_permission($file_name_id)
    {
        $user = User_helper::get_user();
        $actions = Query_helper::get_info($this->config->item('table_fms_setup_assign_file_user_group'), '*', array('user_group_id =' . $user->user_group, 'id_file =' . $file_name_id, 'revision =1'), 1);
        if (!$actions)
        {
            for ($index = 0; $index < $this->config->item('system_fms_max_actions'); $index++)
            {
                $actions['action' . $index] = 0;
            }
        }
        else
        {
            $result = Query_helper::get_info($this->config->item('table_fms_setup_file_name'), 'status_file', array('id=' . $file_name_id), 1);
            $actions['editable'] = false;
            if ($result['status_file'] == $this->config->item('system_status_file_open'))
            {
                $actions['editable'] = true;
            }
        }
        return $actions;
    }

    private function system_save_new_file()
    {
        $data = $this->input->post('item');
        $user = User_helper::get_user();
        $time = time();

        if (!(isset($this->permissions['action1']) && ($this->permissions['action1'] == 1)))
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->lang->line('YOU_DONT_HAVE_ACCESS');
            $this->json_return($ajax);
        }
        if (!$this->check_validation())
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->message;
            $this->json_return($ajax);
        }

        $this->db->trans_start(); //DB Transaction Handle START

        $data['date_start'] = System_helper::get_time($data['date_start']);
        $data['user_created'] = $user->user_id;
        $data['date_created'] = $time;
        $file_name_id = Query_helper::add($this->config->item('table_fms_setup_file_name'), $data);
        if ($file_name_id === false)
        {
            $ajax['status'] = false;
            $ajax['desk_message'] = $this->lang->line('MSG_SAVED_FAIL');
            $this->json_return($ajax);
        }

        $data = array();
        $data['user_created'] = $user->user_id;
        $data['date_created'] = $time;
        $data['user_group_id'] = $user->user_group;
        $data['id_file'] = $file_name_id;
        $data['revision'] = 1;
        for ($i = 0; $i < $this->config->item('system_fms_max_actions'); ++$i)
        {
            $data['action' . $i] = 0;
        }
        $data['action0'] = 1;
        $data['action1'] = 1;
        Query_helper::add($this->config->item('table_fms_setup_assign_file_user_group'), $data);

        $this->db->trans_complete(); //DB Transaction Handle END

        if ($this->db->trans_status() === TRUE)
        {
            $save_and_new = $this->input->post('system_save_new_status');
            $this->message = $this->lang->line('MSG_SAVED_SUCCESS');
            if ($save_and_new == 1)
            {
                $this->system_add();
            }
            else
            {
                $this->system_edit($file_name_id);
            }
        }
        else
        {
            $ajax['status'] = false;
            $ajax['desk_message'] = $this->lang->line('MSG_SAVED_FAIL');
            $this->json_return($ajax);
        }
    }

    private function get_file_info($file_name_id)
    {
        $this->db->from($this->config->item('table_fms_setup_file_name') . ' file_name');
        $this->db->select('file_name.*');

        $this->db->join($this->config->item('table_fms_setup_file_type') . ' type', 'type.id=file_name.id_type');
        $this->db->select('type.name type_name');

        $this->db->join($this->config->item('table_fms_setup_file_class') . ' class', 'class.id=type.id_class');
        $this->db->select('class.name class_name');

        $this->db->join($this->config->item('table_fms_setup_file_sub_category') . ' sub_category', 'sub_category.id=class.id_sub_category');
        $this->db->select('sub_category.name sub_category_name');

        $this->db->join($this->config->item('table_fms_setup_file_category') . ' category', 'category.id=sub_category.id_category');
        $this->db->select('category.name category_name');

        $this->db->join($this->config->item('table_fms_setup_file_hc_location') . ' hc_location', 'hc_location.id=file_name.id_hc_location');
        $this->db->select('hc_location.name hc_location');

        $this->db->join($this->config->item('table_login_setup_user_info') . ' user_info', 'user_info.user_id=file_name.employee_id', 'left');
        $this->db->join($this->config->item('table_login_setup_user') . ' user', 'user.id=user_info.user_id');
        $this->db->select('CONCAT(user_info.name," - ",user.employee_id) responsible_employee');

        $this->db->join($this->config->item('table_login_setup_department') . ' department', 'department.id=file_name.id_department', 'left');
        $this->db->select('department.name department_name');

        $this->db->join($this->config->item('table_login_setup_company') . ' company', 'company.id=file_name.id_company', 'left');
        $this->db->select('company.full_name company_name');

        $this->db->join($this->config->item('table_fms_tasks_digital_file') . ' digital_file', 'digital_file.id_file_name=file_name.id', 'left');
        $this->db->select('SUM(CASE WHEN digital_file.status="' . $this->config->item('system_status_active') . '" THEN 1 ELSE 0 END) file_total');
        $this->db->select('SUM(CASE WHEN digital_file.status="' . $this->config->item('system_status_active') . '" AND SUBSTRING(digital_file.mime_type,1,5)="image" THEN 1 ELSE 0 END) number_of_page');

        $this->db->where('user_info.revision', 1);
        $this->db->where('file_name.id', $file_name_id);
        $this->db->where('file_name.status', $this->config->item('system_status_active'));
        return $this->db->get()->row_array();
    }

    private function get_file_items($file_id)
    {
        $this->db->from($this->config->item('table_fms_setup_file_item') . ' file_item');
        $this->db->select('file_item.id,file_item.name,file_item.status');
        $this->db->join($this->config->item('table_fms_setup_file_type') . ' file_type', 'file_type.id=file_item.id_type');
        $this->db->join($this->config->item('table_fms_setup_file_name') . ' file_name', 'file_name.id_type=file_type.id');
        $this->db->where('file_name.id', $file_id);
        $this->db->order_by('file_item.ordering');
        $results = $this->db->get()->result_array();
        return $results;
    }

    private function system_save()
    {
        $id = $this->input->post('id');
        $id_hc_location = $this->input->post('id_hc_location');
        $items_old = $this->input->post('items_old');
        $items_new = $this->input->post('items_new');
        $status_file = $this->input->post('status_file');

        $user = User_helper::get_user();
        $time = time();
        $file_open_time_for_edit = $this->input->post('file_open_time_for_edit');
        $allowed_types = 'gif|jpg|png|doc|docx|pdf|xls|xlsx|ppt|pptx|txt';
        
        if (!($id > 0))
        {
            $ajax['status'] = false;
            $ajax['system_message'] = 'Invalid Edit ID.';
            $this->json_return($ajax);
        }
        if (!(isset($this->permissions['action2']) && ($this->permissions['action2'] == 1)))
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->lang->line('YOU_DONT_HAVE_ACCESS');
            $this->json_return($ajax);
        }
        $file_permissions = $this->get_file_permission($id);
        if (!$file_permissions['editable'])
        {
            if ($file_permissions['action1'] == 1 || $file_permissions['action2'] == 1 || $file_permissions['action3'] == 1)
            {
                if ($id_hc_location)
                {
                    $data = array();
                    $data['id_hc_location'] = $id_hc_location;
                    $data['date_updated'] = $time;
                    $data['user_updated'] = $user->user_id;
                    $this->db->trans_start();
                    Query_helper::update($this->config->item('table_fms_setup_file_name'), $data, array('id =' . $id));
                    $this->db->trans_complete(); //DB Transaction Handle END
                    if ($this->db->trans_status() !== true)
                    {
                        $ajax['status'] = false;
                        $ajax['system_message'] = $this->lang->line('MSG_SAVED_FAIL');
                        $this->json_return($ajax);
                    }
                }
            }
            else
            {
                System_helper::invalid_try('Edit', $id, 'Dont have file permission');
                $ajax['status'] = false;
                $ajax['system_message'] = 'Invalid Try';
                $this->json_return($ajax);
            }
            $this->message = $this->lang->line('MSG_SAVED_SUCCESS');
            $this->system_list();
            exit();
        }
        if ($file_permissions['action1'] == 1 || $file_permissions['action2'] == 1 || $file_permissions['action3'] == 1)
        {
            //check if already saved
            $result = Query_helper::get_info($this->config->item('table_fms_setup_file_name'), array('date_created', 'date_updated'), array('id =' . $id), 1);
            $time_last_saved = $result['date_created'];
            if ($result['date_updated'] > 0)
            {
                $time_last_saved = $result['date_updated'];
            }
            if ($file_open_time_for_edit <= $time_last_saved)
            {
                $this->message = 'This file already saved by another person while you editing.<br>Please try again.';
                $this->system_edit($id);
            }

            //upload files and check if ok upload
            $folder = FCPATH . $this->config->item('system_folder_upload') . '/' . $id;
            if (!is_dir($folder))
            {
                mkdir($folder, 0777);
            }
            $uploaded_files = System_helper::upload_file($this->config->item('system_folder_upload') . '/' . $id, $allowed_types);
            foreach ($uploaded_files as $file)
            {
                if (!$file['status'])
                {
                    $ajax['status'] = false;
                    $ajax['system_message'] = $file['message'];
                    $this->json_return($ajax);
                    die();
                }
            }

            $stored_files = array();
            if (!is_array($items_old))
            {
                $items_old = array();
            }
            //check validation of delete and edit
            if ($file_permissions['action2'] == 1 || $file_permissions['action3'] == 1)
            {
                $stored_files = Query_helper::get_info($this->config->item('table_fms_tasks_digital_file'), '*', array('id_file_name =' . $id, 'status ="' . $this->config->item('system_status_active') . '"'));
                if (sizeof($stored_files) != sizeof($items_old))
                {
                    if ($file_permissions['action3'] != 1)
                    {
                        System_helper::invalid_try('DELETE', $id, $this->config->item('table_fms_tasks_digital_file') . ' Try to delete file in illegal way.');
                        $ajax['status'] = false;
                        $ajax['system_message'] = 'Invalid Try';
                        $this->json_return($ajax);
                        die();
                    }
                }

            }
            //check validation of delete and edit complete
            $this->db->trans_start(); //DB Transaction Handle START
            if ($file_permissions['action2'] == 1 || $file_permissions['action3'] == 1)
            {
                foreach ($stored_files as $file)
                {
                    $data = array();
                    if (isset($items_old[$file['id']]))
                    {
                        if (isset($uploaded_files['file_old_' . $file['id']]))
                        {
                            $data['name'] = $uploaded_files['file_old_' . $file['id']]['info']['file_name'];
                            $data['file_path'] = $this->config->item('system_folder_upload') . '/' . $id . '/' . $uploaded_files['file_old_' . $file['id']]['info']['file_name'];
                            $data['mime_type'] = $uploaded_files['file_old_' . $file['id']]['info']['file_type'];
                        }
                        if ($file['remarks'] != $items_old[$file['id']]['remarks'])
                        {
                            $data['remarks'] = $items_old[$file['id']]['remarks'];
                        }
                        if ($file['date_entry'] != System_helper::get_time($items_old[$file['id']]['date_entry']))
                        {
                            $data['date_entry'] = System_helper::get_time($items_old[$file['id']]['date_entry']);
                        }
                    }
                    else
                    {
                        $data['status'] = $this->config->item('system_status_delete');
                    }
                    if (sizeof($data) > 0)
                    {
                        $data['date_updated'] = $time;
                        $data['user_updated'] = $user->user_id;
                        Query_helper::update($this->config->item('table_fms_tasks_digital_file'), $data, array('id =' . $file['id']));
                    }
                }
            }
            if ($file_permissions['action1'] == 1)
            {
                if (is_array($items_new))
                {
                    foreach ($items_new as $key => $data)
                    {
                        $data['name'] = 'no_image.jpg';
                        $data['file_path'] = 'images/no_image.jpg';
                        $data['id_file_name'] = $id;
                        $data['mime_type'] = 'image/jpeg';
                        $data['date_entry'] = System_helper::get_time($data['date_entry']);
                        $data['status'] = $this->config->item('system_status_active');
                        $data['date_created'] = $time;
                        $data['user_created'] = $user->user_id;
                        if (isset($uploaded_files['file_new_' . $key]))
                        {
                            $data['name'] = $uploaded_files['file_new_' . $key]['info']['file_name'];
                            $data['file_path'] = $this->config->item('system_folder_upload') . '/' . $id . '/' . $uploaded_files['file_new_' . $key]['info']['file_name'];
                            $data['mime_type'] = $uploaded_files['file_new_' . $key]['info']['file_type'];
                        }
                        Query_helper::add($this->config->item('table_fms_tasks_digital_file'), $data);
                    }
                }
            }
            //last updated by
            $data = array();
            $data['date_updated'] = $time;
            $data['user_updated'] = $user->user_id;
            if (isset($this->permissions['action2']) && ($this->permissions['action2'] == 1))
            {
                if ($id_hc_location)
                {
                    $data['id_hc_location'] = $id_hc_location;
                }
                if ($status_file == $this->config->item('system_status_file_open') || $status_file == $this->config->item('system_status_file_close'))
                {
                    $data['status_file'] = $status_file;
                }
            }
            Query_helper::update($this->config->item('table_fms_setup_file_name'), $data, array('id =' . $id));

            $this->db->trans_complete(); //DB Transaction Handle END
            if ($this->db->trans_status() === true)
            {
                $this->message = $this->lang->line('MSG_SAVED_SUCCESS');
                $this->system_list();
            }
            else
            {
                $ajax['status'] = false;
                $this->message = $this->lang->line('MSG_SAVED_FAIL');
                $this->system_edit($id);
            }
        }
        else
        {
            System_helper::invalid_try('UPDATE', $id, $this->config->item('table_fms_tasks_digital_file') . ' Try to save file in illegal way.');
            $ajax['status'] = false;
            $ajax['system_message'] = 'Invalid Try';
            $this->json_return($ajax);
        }
    }

    private function system_details($id)
    {
        if ($id > 0)
        {
            $item_id = $id;
        }
        else
        {
            $item_id = $this->input->post('id');
        }
        $file_permissions = $this->get_file_permission($item_id);
        if ($file_permissions['action0'] == 1)
        {
            $data['file_permissions'] = $file_permissions;
            $data['item'] = $this->get_file_info($item_id);
            $data['file_items'] = $this->get_file_items($item_id);
            $results = Query_helper::get_info($this->config->item('table_fms_tasks_digital_file'), '*', array('id_file_name =' . $item_id, 'status ="' . $this->config->item('system_status_active') . '"'));
            $data['stored_files'] = array();
            foreach ($results as $result)
            {
                $data['stored_files'][$result['id_file_item']][] = $result;
            }
            $data['users'] = System_helper::get_users_info(array());
            $data['title'] = "Details For::" . $data['item']['name'];
            $ajax['system_content'][] = array('id' => '#system_content', 'html' => $this->load->view($this->controller_url . '/details', $data, true));
            $ajax['system_page_url'] = site_url($this->controller_url . '/index/details/' . $item_id);
            $ajax['status'] = true;
            if ($this->message)
            {
                $ajax['system_message'] = $this->message;
            }
            $this->json_return($ajax);
        }
        else
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->lang->line('YOU_DONT_HAVE_ACCESS');
            $this->json_return($ajax);
        }
    }

    private function system_set_preference()
    {
        $user = User_helper::get_user();
        $method = 'list';
        if (isset($this->permissions['action6']) && ($this->permissions['action6'] == 1))
        {
            $data['system_preference_items'] = System_helper::get_preference($user->user_id, $this->controller_url, $method, $this->get_preference_headers($method));
            $data['preference_method_name'] = $method;
            $ajax['status'] = true;
            $ajax['system_content'][] = array("id" => "#system_content", "html" => $this->load->view("preference_add_edit", $data, true));
            $ajax['system_page_url'] = site_url($this->controller_url . '/index/set_preference');
            $this->json_return($ajax);
        }
        else
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->json_return($ajax);
        }
    }

    private function check_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('item[name]', $this->lang->line('LABEL_NAME'), 'required|trim');
        $this->form_validation->set_rules('item[id_type]', $this->lang->line('LABEL_TYPE_NAME'), 'required');
        $this->form_validation->set_rules('item[id_hc_location]', $this->lang->line('LABEL_HC_LOCATION'), 'required');
        $this->form_validation->set_rules('item[date_start]', $this->lang->line('LABEL_DATE_START'), 'required');
        $this->form_validation->set_rules('item[status_file]', $this->lang->line('LABEL_FILE_STATUS'), 'required');
        $this->form_validation->set_rules('item[employee_id]', $this->lang->line('LABEL_RESPONSIBLE_EMPLOYEE'), 'required');
        $this->form_validation->set_rules('item[id_company]', $this->lang->line('LABEL_COMPANY_NAME'), 'required');
        $this->form_validation->set_rules('item[id_department]', $this->lang->line('LABEL_DEPARTMENT_NAME'), 'required');
        if ($this->form_validation->run() == false)
        {
            $this->message = validation_errors();
            return false;
        }
        return true;
    }
}
