<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_file_view extends Root_Controller
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
    public function index($action='search',$id=0)
    {
        if($action=='search')
        {
            $this->system_search();
        }
        elseif($action=='list')
        {
            $this->system_list();
        }
        elseif($action=='get_items')
        {
            $this->system_get_items();
        }
        elseif($action=='details')
        {
            $this->system_details($id);
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
            $this->system_search();
        }
    }
    private function get_preference_headers($method)
    {
        if ($method == 'search')
        {
            $data['id'] = 1;
            $data['file_name'] = 1;
            $data['responsible_employee'] = 1;
            $data['date_start'] = 1;
            $data['file_status'] = 1;
            $data['hc_location'] = 1;
            $data['category_name'] = 1;
            $data['sub_category_name'] = 1;
            $data['class_name'] = 1;
            $data['type_name'] = 1;
            $data['company_name'] = 1;
            $data['department_name'] = 1;
            $data['ordering'] = 1;
        }
        else
        {
            $data = array();
        }

        return $data;
    }
    private function system_search()
    {
        if(isset($this->permissions['action0']) && ($this->permissions['action0']==1))
        {
            $data['title']='Report View';
            $data['item']=array
            (
                'id_category'=>'',
                'id_sub_category'=>'',
                'id_class'=>'',
                'id_type'=>'',
                'id_name'=>'',
                'date_from_start_file'=>'',
                'date_to_start_file'=>'',
                'date_from_start_page'=>'',
                'date_to_start_page'=>'',
                'id_company'=>'',
                'id_department'=>'',
                'employee_id'=>''
            );
            $data['categories']=Query_helper::get_info($this->config->item('table_fms_setup_file_category'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['sub_categories']=array();
            $data['classes']=array();
            $data['types']=array();
            $data['names']=array();

            $data['companies']=Query_helper::get_info($this->config->item('table_login_setup_company'),array('id value','full_name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['departments']=Query_helper::get_info($this->config->item('table_login_setup_department'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['employees']=array();
            $ajax['system_page_url']=site_url($this->controller_url.'/index/search');
            $ajax['system_content'][]=array('id'=>'#system_content','html'=>$this->load->view($this->controller_url.'/search',$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['status']=true;
            $this->json_return($ajax);
        }
        else
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line('YOU_DONT_HAVE_ACCESS');
            $this->json_return($ajax);
        }
    }
    private function system_list()
    {
        $user = User_helper::get_user();
        $method = 'search';
        if(isset($this->permissions['action0']) && ($this->permissions['action0']==1))
        {
            $item=$this->input->post('item');
            if($item['id_name']>0)
            {
                $data=array();
                $this->get_file_info($data,$item['id_name'],System_helper::get_time($item['date_from_start_page']),System_helper::get_time($item['date_to_start_page']));
                $data['file_items']=$this->get_file_items($item['id_name']);
                $data['item_files']=array();
                $items_file_record=array();
                foreach($data['file_items'] as $file_item)
                {
                    $items_file_record[$file_item['id']]=0;
                }
                foreach($data['stored_files'] as $file)
                {
                    $data['item_files'][$file['id_file_item']][]=$file;
                    if(isset($items_file_record[$file['id_file_item']]))
                    {
                        $items_file_record[$file['id_file_item']]=$items_file_record[$file['id_file_item']]+1;
                    }
                }
                $data['items_file_record']=$items_file_record;
                $data['users'] = System_helper::get_users_info(array());
                $ajax['system_content'][]=array('id'=>'#system_report_container','html'=>$this->load->view($this->controller_url.'/details',$data,true));
            }
            else
            {
                $data['title']='Report for '.$this->lang->line('LABEL_FILE_NAME').' List';
                $data['system_preference_items'] = System_helper::get_preference($user->user_id, $this->controller_url, $method, $this->get_preference_headers($method));
                $ajax_post='';
                foreach($item as $key=>$val)
                {
                    $ajax_post.=$key.':"'.$val.'",';
                }
                $data['ajax_post']=substr($ajax_post,0,-1);
                $ajax['system_content'][]=array('id'=>'#system_report_container','html'=>$this->load->view($this->controller_url.'/list',$data,true));
            }
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/search');
            $ajax['status']=true;
            $this->json_return($ajax);
        }
        else
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line('YOU_DONT_HAVE_ACCESS');
            $this->json_return($ajax);
        }
    }
    private function get_file_items($file_id)
    {
        $this->db->select('file_item.id,file_item.name,file_item.status');
        $this->db->from($this->config->item('table_fms_setup_file_item').' file_item');
        $this->db->join($this->config->item('table_fms_setup_file_type').' file_type','file_type.id=file_item.id_type');
        $this->db->join($this->config->item('table_fms_setup_file_name').' file_name','file_name.id_type=file_type.id');
        $this->db->where('file_name.id',$file_id);
        $this->db->where('file_item.status', $this->config->item('system_status_active'));
        $this->db->where('file_name.status', $this->config->item('system_status_active'));
        $this->db->order_by('file_item.ordering');
        $results=$this->db->get()->result_array();
        return $results;
    }
    private function get_file_info(&$data,$id_file_name,$date_from_start_page,$date_to_start_page)
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

        $this->db->where('user_info.revision', 1);
        $this->db->where('file_name.id', $id_file_name);
        $this->db->where('file_name.status', $this->config->item('system_status_active'));
        $data['item']=$this->db->get()->row_array();

        $this->db->select('*');
        $this->db->from($this->config->item('table_fms_tasks_digital_file'));
        $this->db->where('id_file_name',$id_file_name);
        $this->db->where('status',$this->config->item('system_status_active'));
        $this->two_date_between_query_generator($date_from_start_page,$date_to_start_page,'date_entry');
        $data['stored_files']=$this->db->get()->result_array();
    }
    private function system_get_items()
    {
        $id_category=$this->input->post('id_category');
        $id_sub_category=$this->input->post('id_sub_category');
        $id_class=$this->input->post('id_class');
        $id_type=$this->input->post('id_type');
        $employee_id=$this->input->post('employee_id');
        $id_department=$this->input->post('id_department');
        $id_company=$this->input->post('id_company');
        $date_from_start_file=$this->input->post('date_from_start_file');
        $date_to_start_file=$this->input->post('date_to_start_file');

        $this->db->from($this->config->item('table_fms_setup_file_name') . ' file_name');
        $this->db->select('file_name.id,file_name.name file_name,file_name.date_start,file_name.status_file file_status,file_name.ordering');

        $this->db->join($this->config->item('table_fms_setup_file_hc_location') . ' hc_location', 'hc_location.id=file_name.id_hc_location');
        $this->db->select('hc_location.name hc_location');

        $this->db->join($this->config->item('table_fms_setup_file_type') . ' type', 'type.id=file_name.id_type');
        $this->db->select('type.name type_name');

        $this->db->join($this->config->item('table_fms_setup_file_class') . ' class', 'class.id=type.id_class');
        $this->db->select('class.name class_name');

        $this->db->join($this->config->item('table_fms_setup_file_sub_category') . ' sub_category', 'sub_category.id=class.id_sub_category');
        $this->db->select('sub_category.name sub_category_name');

        $this->db->join($this->config->item('table_fms_setup_file_category') . ' category', 'category.id=sub_category.id_category');
        $this->db->select('category.name category_name');

        $this->db->join($this->config->item('table_login_setup_user_info') . ' user_info', 'user_info.user_id=file_name.employee_id', 'left');
        $this->db->join($this->config->item('table_login_setup_user') . ' user', 'user.id=user_info.user_id');
        $this->db->select('CONCAT(user_info.name," - ",user.employee_id) responsible_employee');

        $this->db->join($this->config->item('table_login_setup_department') . ' department', 'department.id=file_name.id_department', 'left');
        $this->db->select('department.name department_name');

        $this->db->join($this->config->item('table_login_setup_company') . ' company', 'company.id=file_name.id_company', 'left');
        $this->db->select('company.full_name company_name');
        $this->db->where('user_info.revision',1);
        $where_in=false;
        if($id_type>0)
        {
            $this->db->where('file_name.id_type',$id_type);
        }
        elseif($id_class>0)
        {
            $where_in='SELECT id FROM '.$this->config->item('table_fms_setup_file_type').' WHERE id_class='.$id_class;
        }
        elseif($id_sub_category>0)
        {
            $where_in='SELECT id FROM '.$this->config->item('table_fms_setup_file_type').' WHERE id_class IN (SELECT id FROM '.$this->config->item('table_fms_setup_file_class').' WHERE id_sub_category='.$id_sub_category.')';
        }
        elseif($id_category>0)
        {
            $where_in='SELECT id FROM '.$this->config->item('table_fms_setup_file_type').' WHERE id_class IN (SELECT id FROM '.$this->config->item('table_fms_setup_file_class').' WHERE id_sub_category IN (SELECT id FROM '.$this->config->item('table_fms_setup_file_sub_category').' WHERE id_category='.$id_category.'))';
        }

        if($where_in!==false)
        {
            $this->db->where_in('file_name.id_type',$where_in,false);
        }

        if($employee_id>0)
        {
            $this->db->where('file_name.employee_id',$employee_id);
        }
        else
        {
            if($id_company>0)
            {
                $this->db->where('file_name.id_company',$id_company);
            }
            if($id_department>0)
            {
                $this->db->where('file_name.id_department',$id_department);
            }
        }

        $this->two_date_between_query_generator(System_helper::get_time($date_from_start_file),System_helper::get_time($date_to_start_file),'file_name.date_start');
        $this->db->where('file_name.status',$this->config->item('system_status_active'));
        $this->db->group_by('file_name.id');
        $this->db->order_by('category.ordering');
        $this->db->order_by('sub_category.ordering');
        $this->db->order_by('class.ordering');
        $this->db->order_by('type.ordering');
        $this->db->order_by('file_name.ordering');
        $items=$this->db->get()->result_array();
        foreach($items as &$item)
        {
            $item['date_start']=System_helper::display_date($item['date_start']);
        }
//        print_r($items);
//        exit;
        $this->json_return($items);
    }
    private function system_details($id)
    {
        $id=$this->input->post('id');
        $html_id=$this->input->post('html_container_id');
        $date_from_start_page=System_helper::get_time($this->input->post('date_from_start_page'));
        $date_to_start_page=System_helper::get_time($this->input->post('date_to_start_page'));

        $data=array();
        $this->get_file_info($data,$id,$date_from_start_page,$date_to_start_page);
        $data['file_items']=$this->get_file_items($id);
        $data['item_files']=array();
        foreach($data['stored_files'] as $file)
        {
            $data['item_files'][$file['id_file_item']][]=$file;
        }
        unset($data['stored_files']);
        $ajax['system_content'][]=array('id'=>$html_id,'html'=>$this->load->view($this->controller_url.'/details',$data,true));
        if($this->message)
        {
            $ajax['system_message']=$this->message;
        }
        $ajax['status']=true;
        $this->json_return($ajax);
    }
    private function two_date_between_query_generator($start_date,$end_date,$field)
    {
        if($start_date>0)
        {
            $this->db->where($field.'>=',$start_date);
        }
        if($end_date>0)
        {
            $this->db->where($field.'<=',$end_date);
        }
    }
    private function system_set_preference()
    {
        $user = User_helper::get_user();
        $method = 'search';
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
}
