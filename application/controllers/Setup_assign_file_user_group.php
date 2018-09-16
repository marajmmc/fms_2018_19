<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setup_assign_file_user_group extends Root_Controller
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
        if ($action == "list")
        {
            $this->system_list();
        }
        elseif ($action == "get_items")
        {
            $this->system_get_items();
        }
        elseif ($action == "edit")
        {
            $this->system_edit($id);
        }
        elseif ($action == "save")
        {
            $this->system_save();
        }
        elseif ($action == "details")
        {
            $this->system_details($id);
        }
        elseif ($action == 'search')
        {
            $this->system_search($id);
        }
        elseif ($action == "set_preference")
        {
            $this->system_set_preference('list');
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
        $data['id'] = 1;
        $data['name'] = 1;
        $data['file_total_permission'] = 1;
        $data['ordering'] = 1;
        $data['status'] = 1;
        return $data;
    }

    private function system_set_preference($method = 'list')
    {
        $user = User_helper::get_user();
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

    private function system_list()
    {
        if (isset($this->permissions['action0']) && ($this->permissions['action0'] == 1))
        {
            $user = User_helper::get_user();
            $method = 'list';
            $data['system_preference_items'] = System_helper::get_preference($user->user_id, $this->controller_url, $method, $this->get_preference_headers($method));
            $data['title'] = "List of User Group to Assign Files";
            $ajax['status'] = true;
            $ajax['system_content'][] = array("id" => "#system_content", "html" => $this->load->view($this->controller_url . "/list", $data, true));
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
            $ajax['system_message'] = $this->lang->line("YOU_DONT_HAVE_ACCESS");
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
        $this->db->from($this->config->item('table_system_user_group'));
        $this->db->select('*');
        if ($user->user_group != 1)
        {
            $this->db->where('id!=', 1);
        }
        $this->db->limit($pagesize, $current_records);
        $user_groups = $this->db->get()->result_array();


        $this->db->from($this->config->item('table_fms_setup_assign_file_user_group'));
        $this->db->select('COUNT(id_file) file_total_permission, user_group_id', false);
        $this->db->where('revision', 1);
        $this->db->where('action0', 1);
        $this->db->group_by('user_group_id');
        $results = $this->db->get()->result_array();

        $file_total_permissions = array();
        foreach ($results as $result)
        {
            $file_total_permissions[$result['user_group_id']]['file_total_permission'] = $result['file_total_permission'];
        }
        foreach ($user_groups as &$user_group)
        {
            if (isset($file_total_permissions[$user_group['id']]['file_total_permission']))
            {
                $user_group['file_total_permission'] = $file_total_permissions[$user_group['id']]['file_total_permission'];
            }
            else
            {
                $user_group['file_total_permission'] = 0;
            }
        }
        $this->json_return($user_groups);
    }

    private function system_search($id)
    {
        if (isset($this->permissions['action2']) && ($this->permissions['action2'] == 1))
        {
            if ($id > 0)
            {
                $item_id = $id;
            }
            else
            {
                $item_id = $this->input->post('id');
            }
            $data = array();
            $this->db->from($this->config->item('table_system_user_group'));
            $this->db->select('name');
            $this->db->where('id', $item_id);
            $user_group_name = $this->db->get()->row_array();

            $data['item_id'] = $item_id;
            $data['categories'] = Query_helper::get_info($this->config->item('table_fms_setup_file_category'), array('id value', 'name text'), array('status ="' . $this->config->item('system_status_active') . '"'), 0, 0, array('ordering ASC'));

            $data['title'] = 'Edit File Permission to (' . $user_group_name['name'] . ')';
            $ajax['status'] = true;
            $ajax['system_content'][] = array("id" => "#system_content", "html" => $this->load->view($this->controller_url . "/search", $data, true));
            if ($this->message)
            {
                $ajax['system_message'] = $this->message;
            }
            $ajax['system_page_url'] = site_url($this->controller_url . '/index/search/' . $item_id);
            $this->json_return($ajax);
        }
        else
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->lang->line('YOU_DONT_HAVE_ACCESS');
            $this->json_return($ajax);
        }
    }

    private function system_edit($id)
    {
        if (isset($this->permissions['action2']) && ($this->permissions['action2'] == 1))
        {
            $data = array();
            $data['item_id'] = $this->input->post('id_user_group');
            $data['id_sub_category'] = $this->input->post('id_sub_category');

            if ($data['item_id'] > 0 && $data['id_sub_category'] > 0)
            {
                $data['permitted_files'] = array();
                $this->db->from($this->config->item('table_fms_setup_assign_file_user_group'));
                $this->db->where('user_group_id', $data['item_id']);
                $this->db->where('action0', 1);
                $this->db->where('revision', 1);
                $results = $this->db->get()->result_array();
                foreach ($results as $result)
                {
                    $data['permitted_files'][$result['id_file']] = $result;
                }

                $data['all_files'] = array();
                $this->db->from($this->config->item('table_fms_setup_file_name') . ' file_name');
                $this->db->select('file_name.id file_id, file_name.name file_name, file_type.id type_id, file_type.name type_name, file_class.id class_id, file_class.name class_name');
                $this->db->join($this->config->item('table_fms_setup_file_type') . ' file_type', 'file_type.id = file_name.id_type');
                $this->db->join($this->config->item('table_fms_setup_file_class') . ' file_class', 'file_class.id = file_type.id_class');
                $this->db->where('file_class.id_sub_category', $data['id_sub_category']);
                $this->db->where('file_name.status', $this->config->item('system_status_active'));
                $this->db->order_by('file_class.id');
                $this->db->order_by('file_type.id');
                $this->db->order_by('file_name.id');
                $results = $this->db->get()->result_array();
                foreach ($results as $result)
                {
                    $data['all_files'][$result['class_id']]['name'] = $result['class_name'];
                    $data['all_files'][$result['class_id']]['types'][$result['type_id']]['name'] = $result['type_name'];
                    $data['all_files'][$result['class_id']]['types'][$result['type_id']]['files'][$result['file_id']]['name'] = $result['file_name'];
                }

                $ajax['system_content'][] = array('id' => '#edit_form', 'html' => $this->load->view($this->controller_url . '/add_edit', $data, true));
                if ($this->message)
                {
                    $ajax['system_message'] = $this->message;
                }
                $ajax['status'] = true;
                $this->json_return($ajax);
            }
            else
            {
                $ajax['status'] = false;
                $ajax['system_message'] = 'You have violated your rules.';
                $this->json_return($ajax);
            }
        }
        else
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->json_return($ajax);
        }
    }

    private function system_save()
    {
        $id = $this->input->post("id");
        $id_sub_category = $this->input->post('id_sub_category');
        $items = $this->input->post('items');

        if (!(isset($this->permissions['action2']) && ($this->permissions['action2'] == 1)))
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->json_return($ajax);
        }
        if (!$items && !is_array($items)) // Validation
        {
            $ajax['status'] = false;
            $ajax['system_message'] = 'Minimum One permission needed for 1 or, more file(s)';
            $this->json_return($ajax);
        }

        $user = User_helper::get_user();
        $time = time();

        if ($id > 0)
        {
            $this->db->trans_start(); //DB Transaction Handle START
            // UPDATE revision number
            $query = 'UPDATE ' . $this->config->item('table_fms_setup_assign_file_user_group') . ' file_user_group';
            $query .= ' JOIN ' . $this->config->item('table_fms_setup_file_name') . ' file_name ON file_name.id = file_user_group.id_file';
            $query .= ' JOIN ' . $this->config->item('table_fms_setup_file_type') . ' file_type ON file_type.id = file_name.id_type';
            $query .= ' JOIN ' . $this->config->item('table_fms_setup_file_class') . ' file_class ON file_class.id = file_type.id_class';
            $query .= ' SET file_user_group.revision = file_user_group.revision + 1';
            $query .= ' WHERE file_class.id_sub_category =' . $id_sub_category;
            $query .= ' AND file_user_group.user_group_id =' . $id;
            $this->db->query($query);

            // INSERT permissions
            foreach ($items as $id_file => $actions)
            {
                $data_add = array();
                foreach ($actions as $index => $status)
                {
                    $data_add[$index] = 1;
                }
                $data_add['action0'] = 1;
                $data_add['id_file'] = $id_file;
                $data_add['user_group_id'] = $id;
                $data_add['user_created'] = $user->user_id;
                $data_add['date_created'] = $time;
                Query_helper::add($this->config->item('table_fms_setup_assign_file_user_group'), $data_add);
            }
            $this->db->trans_complete(); //DB Transaction Handle END

            if ($this->db->trans_status() === TRUE)
            {
                $save_and_new = $this->input->post('system_save_new_status');
                $this->message = $this->lang->line("MSG_SAVED_SUCCESS");
                if ($save_and_new == 1)
                {
                    $this->system_search($id);
                }
                else
                {
                    $this->system_list();
                }
            }
            else
            {
                $ajax['status'] = false;
                $ajax['system_message'] = $this->lang->line("MSG_SAVED_FAIL");
                $this->json_return($ajax);
            }
        }
        else
        {
            $ajax['status'] = false;
            $ajax['system_message'] = 'You are violating your rules.';
            $this->json_return($ajax);
        }
    }

    private function system_details($id)
    {
        if (isset($this->permissions['action0']) && ($this->permissions['action0'] == 1))
        {
            if ($id > 0)
            {
                $item_id = $id;
            }
            else
            {
                $item_id = $this->input->post('id');
            }
            $data = array();
            $this->db->from($this->config->item('table_fms_setup_file_name') . ' file_name');
            $this->db->select('file_name.id file_id, file_name.name file_name');

            $this->db->join($this->config->item('table_fms_setup_file_type') . ' file_type', 'file_type.id = file_name.id_type');
            $this->db->select('file_type.id type_id, file_type.name type_name');

            $this->db->join($this->config->item('table_fms_setup_file_class') . ' file_class', 'file_class.id = file_type.id_class');
            $this->db->select('file_class.id class_id, file_class.name class_name');

            $this->db->join($this->config->item('table_fms_setup_file_sub_category') . ' file_sub_category', 'file_sub_category.id=file_class.id_sub_category');
            $this->db->select('file_sub_category.id sub_category_id, file_sub_category.name sub_category_name');

            $this->db->join($this->config->item('table_fms_setup_file_category') . ' file_category', 'file_category.id=file_sub_category.id_category');
            $this->db->select('file_category.id category_id, file_category.name category_name');

            $this->db->join($this->config->item('table_fms_setup_assign_file_user_group') . ' file_user_group', 'file_user_group.id_file = file_name.id', 'left');
            $this->db->select('file_user_group.*');

            $this->db->where('file_name.status', $this->config->item('system_status_active'));
            $this->db->where('file_user_group.user_group_id', $item_id);
            $this->db->where('file_user_group.revision', 1);
            $this->db->order_by('file_category.id');
            $this->db->order_by('file_sub_category.id');
            $this->db->order_by('file_class.id');
            $this->db->order_by('file_type.id');
            $this->db->order_by('file_name.id');
            $data['all_files'] = $this->db->get()->result_array();


            $this->db->from($this->config->item('table_system_user_group'));
            $this->db->select('name');
            $this->db->where('id', $item_id);
            $user_group_name = $this->db->get()->row_array();

            $data['item_id'] = $item_id;
            $data['title'] = 'Details File Permissions for (' . $user_group_name['name'] . ')';
            $ajax['status'] = true;
            $ajax['system_content'][] = array("id" => "#system_content", "html" => $this->load->view($this->controller_url . "/details", $data, true));
            if ($this->message)
            {
                $ajax['system_message'] = $this->message;
            }
            $ajax['system_page_url'] = site_url($this->controller_url . '/index/details/' . $item_id);
            $this->json_return($ajax);
        }
        else
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->json_return($ajax);
        }
    }
}
