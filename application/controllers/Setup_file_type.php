<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setup_file_type extends Root_Controller
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
        elseif ($action == "add")
        {
            $this->system_add();
        }
        elseif ($action == "edit")
        {
            $this->system_edit($id);
        }
        elseif ($action == "save")
        {
            $this->system_save();
        }
        elseif ($action == "set_preference")
        {
            $this->system_set_preference('list');
        }
        elseif ($action == "set_preference_all")
        {
            $this->system_set_preference('list_all');
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
        $data['category_name'] = 1;
        $data['sub_category_name'] = 1;
        $data['class_name'] = 1;
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
            $data['title'] = "Active File Type List";
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

        $this->db->from($this->config->item('table_fms_setup_file_type') . ' file_type');
        $this->db->select('file_type.*');
        $this->db->join($this->config->item('table_fms_setup_file_class') . ' file_class', 'file_type.id_class=file_class.id');
        $this->db->select('file_class.name class_name');
        $this->db->join($this->config->item('table_fms_setup_file_sub_category') . ' file_sub_category', 'file_sub_category.id=file_class.id_sub_category');
        $this->db->select('file_sub_category.name sub_category_name');
        $this->db->join($this->config->item('table_fms_setup_file_category') . ' file_category', 'file_category.id=file_sub_category.id_category');
        $this->db->select('file_category.name category_name');
        $this->db->order_by('file_type.ordering');
        $this->db->limit($pagesize, $current_records);
        $items = $this->db->get()->result_array();
        $this->json_return($items);
    }

    private function system_add()
    {
        if (isset($this->permissions['action1']) && ($this->permissions['action1'] == 1))
        {
            $data['title'] = "Create New File Type";
            $data['item'] = array
            (
                'id' => 0,
                'name' => '',
                'id_sub_category' => '',
                'id_category' => '',
                'id_class' => '',
                'ordering' => 99,
                'status' => $this->config->item('system_status_active'),
                'remarks' => ''
            );
            $data['categories'] = Query_helper::get_info($this->config->item('table_fms_setup_file_category'), array('id', 'name'), array('status ="' . $this->config->item('system_status_active') . '"'));
            $data['sub_categories'] = array();
            $data['classes'] = array();
            $ajax['status'] = true;
            $ajax['system_content'][] = array("id" => "#system_content", "html" => $this->load->view($this->controller_url . "/add_edit", $data, true));
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

            $this->db->from($this->config->item('table_fms_setup_file_type') . ' file_type');
            $this->db->select('file_type.*');
            $this->db->join($this->config->item('table_fms_setup_file_class') . ' file_class', 'file_class.id = file_type.id_class');
            $this->db->select('file_class.id_sub_category');
            $this->db->join($this->config->item('table_fms_setup_file_sub_category') . ' file_sub_category', 'file_sub_category.id = file_class.id_sub_category');
            $this->db->select('file_sub_category.id_category');
            $this->db->where('file_type.id', $item_id);
            $data['item'] = $this->db->get()->row_array();
            if (!$data['item'])
            {
                System_helper::invalid_try('Edit Not Exists', $item_id);
                $ajax['status'] = false;
                $ajax['system_message'] = 'Invalid File Class.';
                $this->json_return($ajax);
            }

            $data['categories'] = Query_helper::get_info($this->config->item('table_fms_setup_file_category'), array('id', 'name'), array('status ="' . $this->config->item('system_status_active') . '"'));
            $data['sub_categories'] = Query_helper::get_info($this->config->item('table_fms_setup_file_sub_category'), array('id', 'name'), array('id_category=' . $data['item']['id_category'], 'status ="' . $this->config->item('system_status_active') . '"'));
            $data['classes'] = Query_helper::get_info($this->config->item('table_fms_setup_file_class'), array('id', 'name'), array('id_sub_category=' . $data['item']['id_sub_category'], 'status ="' . $this->config->item('system_status_active') . '"'));

            $data['title'] = "Edit File Type :: " . $data['item']['name'];
            $ajax['status'] = true;
            $ajax['system_content'][] = array("id" => "#system_content", "html" => $this->load->view($this->controller_url . "/add_edit", $data, true));
            if ($this->message)
            {
                $ajax['system_message'] = $this->message;
            }
            $ajax['system_page_url'] = site_url($this->controller_url . '/index/edit/' . $item_id);
            $this->json_return($ajax);
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
        $user = User_helper::get_user();
        $time = time();
        $item = $this->input->post('item');

        if ($id > 0) // EDIT
        {
            if (!(isset($this->permissions['action2']) && ($this->permissions['action2'] == 1)))
            {
                $ajax['status'] = false;
                $ajax['system_message'] = $this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->json_return($ajax);
            }

            $result = Query_helper::get_info($this->config->item('table_fms_setup_file_type'), '*', array('id =' . $id, 'status != "' . $this->config->item('system_status_delete') . '"'), 1);
            if (!$result)
            {
                System_helper::invalid_try('Update Not Exists', $id);
                $ajax['status'] = false;
                $ajax['system_message'] = 'Invalid File Type.';
                $this->json_return($ajax);
            }
        }
        else // ADD
        {
            if (!(isset($this->permissions['action1']) && ($this->permissions['action1'] == 1)))
            {
                $ajax['status'] = false;
                $ajax['system_message'] = $this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->json_return($ajax);
            }
        }
        if (!$this->check_validation())
        {
            $ajax['status'] = false;
            $ajax['system_message'] = $this->message;
            $this->json_return($ajax);
        }

        $this->db->trans_start(); //DB Transaction Handle START
        if ($id > 0) // EDIT
        {
            $item['date_updated'] = $time;
            $item['user_updated'] = $user->user_id;
            Query_helper::update($this->config->item('table_fms_setup_file_type'), $item, array('id=' . $id));
        }
        else // ADD
        {
            $item['date_created'] = $time;
            $item['user_created'] = $user->user_id;
            Query_helper::add($this->config->item('table_fms_setup_file_type'), $item);
        }
        $this->db->trans_complete(); //DB Transaction Handle END

        if ($this->db->trans_status() === TRUE)
        {
            $save_and_new = $this->input->post('system_save_new_status');
            $this->message = $this->lang->line("MSG_SAVED_SUCCESS");
            if ($save_and_new == 1)
            {
                $this->system_add();
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

    private function check_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('item[id_class]', $this->lang->line('LABEL_CLASS_NAME'), 'required');
        $this->form_validation->set_rules('item[name]', $this->lang->line('LABEL_TYPE_NAME'), 'required|trim');
        $this->form_validation->set_rules('item[ordering]', $this->lang->line('LABEL_ORDER'), 'required');
        $this->form_validation->set_rules('item[status]', $this->lang->line('LABEL_STATUS'), 'required');
        if ($this->form_validation->run() == FALSE)
        {
            $this->message = validation_errors();
            return false;
        }
        return true;
    }
}