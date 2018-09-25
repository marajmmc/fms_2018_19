<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setup_file_name extends Root_Controller
{
    public $message;
    public $permissions;
    public $controller_url;

    public function __construct()
    {
        parent::__construct();
        $this->message = "";
        $this->permissions = User_helper::get_permission(get_class());
        $this->controller_url = strtolower(get_class());
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
        $data['file_name'] = 1;
        $data['responsible_employee'] = 1;
        $data['date_opening'] = 1;
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
            $data['title'] = $this->lang->line('LABEL_FILE_NAME'). " List";
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
            $pagesize = 100;
        }
        else
        {
            $pagesize = $pagesize * 2;
        }
        $inactive_txt = $this->config->item('system_status_inactive'); // Just a variable for In-active text

        $this->db->from($this->config->item('table_fms_setup_file_name') . ' file_name');
        $this->db->select('file_name.*, file_name.name file_name');

        $this->db->join($this->config->item('table_fms_setup_file_type') . ' type', 'file_name.id_type=type.id', 'INNER');
        $this->db->select("IF( (type.status='{$inactive_txt}'), CONCAT( type.name,' ({$inactive_txt})'), type.name ) AS type_name");

        $this->db->join($this->config->item('table_fms_setup_file_class') . ' class', 'type.id_class=class.id', 'INNER');
        $this->db->select("IF( (class.status='{$inactive_txt}'), CONCAT( class.name,' ({$inactive_txt})'), class.name ) AS class_name");

        $this->db->join($this->config->item('table_fms_setup_file_sub_category') . ' sub_category', 'class.id_sub_category=sub_category.id', 'INNER');
        $this->db->select("IF( (sub_category.status='{$inactive_txt}'), CONCAT( sub_category.name,' ({$inactive_txt})'), sub_category.name ) AS sub_category_name");

        $this->db->join($this->config->item('table_fms_setup_file_category') . ' category', 'sub_category.id_category=category.id', 'INNER');
        $this->db->select("IF( (category.status='{$inactive_txt}'), CONCAT( category.name,' ({$inactive_txt})'), category.name ) AS category_name");

        $this->db->join($this->config->item('table_fms_setup_file_hc_location') . ' location', 'location.id=file_name.id_hc_location', 'INNER');
        $this->db->select("IF( (location.status='{$inactive_txt}'), CONCAT( location.name,' ({$inactive_txt})'), location.name ) AS hc_location");

        $this->db->join($this->config->item('table_login_setup_user_info') . ' ui', 'ui.user_id=file_name.employee_id', 'left');
        $this->db->select('CONCAT(ui.name, " - ", u.employee_id) responsible_employee');
        $this->db->join($this->config->item('table_login_setup_user') . ' u', 'ui.user_id=u.id');

        $this->db->join($this->config->item('table_login_setup_department') . ' department', 'department.id=file_name.id_department', 'left');
        $this->db->select('department.name department_name');

        $this->db->join($this->config->item('table_login_setup_company') . ' company', 'company.id=file_name.id_company', 'left');
        $this->db->select('company.full_name company_name');

        $this->db->join($this->config->item('table_fms_tasks_digital_file') . ' digital_file', 'digital_file.id_file_name=file_name.id', 'left');
        $this->db->select('SUM(CASE WHEN digital_file.status="' . $this->config->item('system_status_active') . '" AND SUBSTRING(digital_file.mime_type,1,5)="image" THEN 1 ELSE 0 END) number_of_page');

        $this->db->where('ui.revision', 1);
        $this->db->order_by('file_name.ordering', 'ASC');
        $this->db->group_by('file_name.id', 'ASC');
        $this->db->limit($pagesize, $current_records);
        $items = $this->db->get()->result_array();
        foreach ($items as &$item)
        {
            $item['date_opening'] = System_helper::display_date($item['date_start']);
        }
        $this->json_return($items);
    }

    private function system_add()
    {
        if (isset($this->permissions['action1']) && ($this->permissions['action1'] == 1))
        {
            $data['item'] = array
            (
                'id' => 0,
                'name' => '',
                'id_category' => '',
                'id_sub_category' => '',
                'id_class' => '',
                'id_type' => '',
                'id_hc_location' => '',
                'date_start' => System_helper::display_date(time()),
                'ordering' => 99,
                'status_file' => $this->config->item('system_status_file_open'),
                'status' => $this->config->item('system_status_active'),
                'remarks' => '',
                'id_company' => '',
                'id_department' => '',
                'employee_id' => ''
            );

            $data['categories'] = Query_helper::get_info($this->config->item('table_fms_setup_file_category'), array('id value', 'name text'), array('status ="' . $this->config->item('system_status_active') . '"'), 0, 0, array('ordering ASC'));
            $data['sub_categories'] = array();
            $data['classes'] = array();
            $data['types'] = array();
            $data['hc_locations'] = Query_helper::get_info($this->config->item('table_fms_setup_file_hc_location'), array('id value', 'name text'), array('status ="' . $this->config->item('system_status_active') . '"'), 0, 0, array('ordering ASC'));

            $data['companies'] = Query_helper::get_info($this->config->item('table_login_setup_company'), array('id value', 'full_name text'), array('status ="' . $this->config->item('system_status_active') . '"'), 0, 0, array('ordering ASC'));
            $data['departments'] = Query_helper::get_info($this->config->item('table_login_setup_department'), array('id value', 'name text'), array('status ="' . $this->config->item('system_status_active') . '"'), 0, 0, array('ordering ASC'));
            $data['employees'] = array();

            $data['title'] = "New " . $this->lang->line('LABEL_FILE_NAME');
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

            $this->db->from($this->config->item('table_fms_setup_file_name') . ' file_name');
            $this->db->select('file_name.*');

            $this->db->join($this->config->item('table_fms_setup_file_type') . ' file_type', 'file_name.id_type=file_type.id');
            $this->db->select('file_type.id_class');

            $this->db->join($this->config->item('table_fms_setup_file_class') . ' class', 'file_type.id_class=class.id');
            $this->db->select('class.id_sub_category');

            $this->db->join($this->config->item('table_fms_setup_file_sub_category') . ' sub_category', 'class.id_sub_category=sub_category.id');
            $this->db->select('sub_category.id_category');

            $this->db->where('file_name.id', $item_id);
            $data['item'] = $this->db->get()->row_array();
            if (!$data['item'])
            {
                System_helper::invalid_try(__FUNCTION__, $item_id, 'Edit Not Exists');
                $ajax['status'] = false;
                $ajax['system_message'] = 'Invalid Try.';
                $this->json_return($ajax);
            }

            $data['item']['date_start'] = System_helper::display_date($data['item']['date_start']);

            $inactive_txt = $this->config->item('system_status_inactive'); // Just a variable for In-active text
            $cat_name_field = "IF( ({$this->config->item('table_fms_setup_file_category')}.status='{$inactive_txt}'), CONCAT( {$this->config->item('table_fms_setup_file_category')}.name,' ({$inactive_txt})'), {$this->config->item('table_fms_setup_file_category')}.name ) AS text";
            $subcat_name_field = "IF( ({$this->config->item('table_fms_setup_file_sub_category')}.status='{$inactive_txt}'), CONCAT( {$this->config->item('table_fms_setup_file_sub_category')}.name,' ({$inactive_txt})'), {$this->config->item('table_fms_setup_file_sub_category')}.name ) AS text";
            $class_name_field = "IF( ({$this->config->item('table_fms_setup_file_class')}.status='{$inactive_txt}'), CONCAT( {$this->config->item('table_fms_setup_file_class')}.name,' ({$inactive_txt})'), {$this->config->item('table_fms_setup_file_class')}.name ) AS text";
            $type_name_field = "IF( ({$this->config->item('table_fms_setup_file_type')}.status='{$inactive_txt}'), CONCAT( {$this->config->item('table_fms_setup_file_type')}.name,' ({$inactive_txt})'), {$this->config->item('table_fms_setup_file_type')}.name ) AS text";
            $hc_location_name_field = "IF( ({$this->config->item('table_fms_setup_file_hc_location')}.status='{$inactive_txt}'), CONCAT( {$this->config->item('table_fms_setup_file_hc_location')}.name,' ({$inactive_txt})'), {$this->config->item('table_fms_setup_file_hc_location')}.name ) AS text";

            $data['categories'] = Query_helper::get_info($this->config->item('table_fms_setup_file_category'), array('id value', $cat_name_field), array(), 0, 0, array('ordering ASC'));
            $data['sub_categories'] = Query_helper::get_info($this->config->item('table_fms_setup_file_sub_category'), array('id value', $subcat_name_field), array('id_category=' . $data['item']['id_category']), 0, 0, array('ordering ASC'));
            $data['classes'] = Query_helper::get_info($this->config->item('table_fms_setup_file_class'), array('id value', $class_name_field), array('id_sub_category=' . $data['item']['id_sub_category']), 0, 0, array('ordering ASC'));
            $data['types'] = Query_helper::get_info($this->config->item('table_fms_setup_file_type'), array('id value', $type_name_field), array('id_class=' . $data['item']['id_class']), 0, 0, array('ordering ASC'));
            $data['hc_locations'] = Query_helper::get_info($this->config->item('table_fms_setup_file_hc_location'), array('id value', $hc_location_name_field), array(), 0, 0, array('ordering ASC'));
            $data['companies'] = Query_helper::get_info($this->config->item('table_login_setup_company'), array('id value', 'full_name text'), array('status ="' . $this->config->item('system_status_active') . '"'), 0, 0, array('ordering ASC'));
            $data['departments'] = Query_helper::get_info($this->config->item('table_login_setup_department'), array('id value', 'name text'), array('status ="' . $this->config->item('system_status_active') . '"'), 0, 0, array('ordering ASC'));

            $this->db->select("u.id value,CONCAT(ui.name,' - ',u.employee_id) AS text");
            $this->db->from($this->config->item('table_login_setup_user') . ' u');
            $this->db->join($this->config->item('table_login_setup_user_info') . ' ui', 'u.id=ui.user_id');
            $this->db->join($this->config->item('table_login_setup_users_company') . ' uc', 'u.id=uc.user_id');
            $this->db->where('u.status', $this->config->item('system_status_active'));
            $this->db->where('ui.revision', 1);
            $this->db->where('uc.company_id', $data['item']['id_company']);
            $this->db->where('uc.revision', 1);
            $this->db->where('ui.department_id', $data['item']['id_department']);
            $this->db->order_by('u.employee_id');
            $this->db->group_by('u.id');
            $data['employees'] = $this->db->get()->result_array();


            $data['title'] = 'Edit ' . $this->lang->line('LABEL_FILE_NAME') . ' :: ' . $data['item']['name'];
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
        $item['date_start'] = System_helper::get_time($item['date_start']);
        if ($id > 0)
        {
            if (!(isset($this->permissions['action2']) && ($this->permissions['action2'] == 1)))
            {
                $ajax['status'] = false;
                $ajax['system_message'] = $this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->json_return($ajax);
            }

            $result = Query_helper::get_info($this->config->item('table_fms_setup_file_name'), '*', array('id =' . $id, 'status != "' . $this->config->item('system_status_delete') . '"'), 1);
            if (!$result)
            {
                System_helper::invalid_try(__FUNCTION__, $id, 'Update Not Exists');
                $ajax['status'] = false;
                $ajax['system_message'] = 'Invalid Try.';
                $this->json_return($ajax);
            }
        }
        else
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

        if ($id > 0)
        {
            $item['date_updated'] = $time;
            $item['user_updated'] = $user->user_id;
            Query_helper::update($this->config->item('table_fms_setup_file_name'), $item, array('id=' . $id));
        }
        else
        {
            $item['date_created'] = $time;
            $item['user_created'] = $user->user_id;
            Query_helper::add($this->config->item('table_fms_setup_file_name'), $item);
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
        $this->form_validation->set_rules('item[id_company]', $this->lang->line('LABEL_COMPANY_NAME'), 'required');
        $this->form_validation->set_rules('item[id_department]', $this->lang->line('LABEL_DEPARTMENT_NAME'), 'required');
        $this->form_validation->set_rules('item[employee_id]', $this->lang->line('LABEL_RESPONSIBLE_EMPLOYEE'), 'required');
        $this->form_validation->set_rules('item[name]', $this->lang->line('LABEL_FILE_NAME'), 'required|trim');
        $this->form_validation->set_rules('item[id_type]', $this->lang->line('LABEL_TYPE_NAME'), 'required');
        $this->form_validation->set_rules('item[id_hc_location]', $this->lang->line('LABEL_HC_LOCATION'), 'required');
        $this->form_validation->set_rules('item[status_file]', $this->lang->line('LABEL_FILE_STATUS'), 'required');
        $this->form_validation->set_rules('item[date_start]', $this->lang->line('LABEL_DATE_OPENING'), 'required');
        if ($this->form_validation->run() == FALSE)
        {
            $this->message = validation_errors();
            return false;
        }
        return true;
    }
}
