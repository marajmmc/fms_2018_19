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
            $data['date_opening'] = 1;
            $data['file_status'] = 1;
            $data['hc_location'] = 1;
            $data['category_name'] = 1;
            $data['sub_category_name'] = 1;
            $data['class_name'] = 1;
            $data['type_name'] = 1;
            $data['company_name'] = 1;
            $data['department_name'] = 1;
            $data['ordering'] = 1;
            $data['details_button'] = 1;
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
            $data['title']='Files Report';
            $data['item']=array
            (
                'date_from_start_file'=>'',
                'date_to_start_file'=>'',
                'date_from_start_page'=>'',
                'date_to_start_page'=>''
            );
            $data['categories']=Query_helper::get_info($this->config->item('table_fms_setup_file_category'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['sub_categories']=array();
            $data['classes']=array();
            $data['types']=array();
            $data['items']=array();
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
            $reports=$this->input->post('report');
            $date_from_start_file=System_helper::get_time($reports['date_from_start_file']);
            $date_to_start_file=System_helper::get_time($reports['date_to_start_file']);
            $date_from_start_page=System_helper::get_time($reports['date_from_start_page']);
            $date_to_start_page=System_helper::get_time($reports['date_to_start_page']);
            if($date_from_start_file>$date_to_start_file)
            {
                $ajax['status']=false;
                $ajax['system_message']='File Opening From Date should be less than File Opening To Date';
                $this->json_return($ajax);
            }
            if($date_from_start_page>$date_to_start_page)
            {
                $ajax['status']=false;
                $ajax['system_message']='Page Entry From Date should be less than Page Entry To Date';
                $this->json_return($ajax);
            }
            if($reports['id_name']>0)
            {
                $data=array();
                $this->get_file_info($data,$reports['id_name'],$date_from_start_page,$date_to_start_page);
                $data['file_items']=$this->get_file_items($reports['id_name']);
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
                $data['title']='Files Report';
                $data['system_preference_items'] = System_helper::get_preference($user->user_id, $this->controller_url, $method, $this->get_preference_headers($method));
                $data['options']=$reports;
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
        $id_item=$this->input->post('id_item');
        $item_upload=$this->input->post('item_upload');
        $employee_id=$this->input->post('employee_id');
        $id_department=$this->input->post('id_department');
        $id_company=$this->input->post('id_company');
        $date_from_start_file=$this->input->post('date_from_start_file');
        $date_to_start_file=$this->input->post('date_to_start_file');

        $this->db->from($this->config->item('table_fms_setup_file_name') . ' file_name');
        $this->db->select('file_name.id,file_name.name file_name,file_name.date_start date_opening,file_name.status_file file_status,file_name.ordering');

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
        $this->db->where('category.status',$this->config->item('system_status_active'));
        $this->db->where('sub_category.status',$this->config->item('system_status_active'));
        $this->db->where('class.status',$this->config->item('system_status_active'));
        $this->db->where('type.status',$this->config->item('system_status_active'));
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
            $item['date_opening']=System_helper::display_date($item['date_opening']);
        }

        $digital_file=array();
        if($id_item>0)
        {
            if($item_upload)
            {
                $this->db->from($this->config->item('table_fms_tasks_digital_file') . ' digital_file');
                $this->db->select('digital_file.*');
                $this->db->where('digital_file.id_file_item',$id_item);
                $items_digital_file=$this->db->get()->result_array();
                foreach($items_digital_file as $item_digital_file)
                {
                    $digital_file[$item_digital_file['id_file_name']]=$item_digital_file;
                }
                foreach($items as $key=>$item)
                {
                    if($item_upload=='Yes')
                    {
                        if(!(isset($digital_file[$item['id']])))
                        {
                            unset($items[$key]);
                        }
                    }
                    elseif($item_upload=='No')
                    {
                        if(isset($digital_file[$item['id']]))
                        {
                            unset($items[$key]);
                        }
                    }
                }
                $items = array_values($items);
            }
        }
        $this->json_return($items);
    }
    private function system_details($id)
    {
        if(isset($this->permissions['action0'])&&($this->permissions['action0']==1))
        {
            if($id>0)
            {
                $item_id=$id;
            }
            else
            {
                $item_id=$this->input->post('id');
            }
            $html_id=$this->input->post('html_container_id');
            $date_from_start_page=System_helper::get_time($this->input->post('date_from_start_page'));
            $date_to_start_page=System_helper::get_time($this->input->post('date_to_start_page'));

            $data=array();
            $this->get_file_info($data,$item_id,$date_from_start_page,$date_to_start_page);
            $data['file_items']=$this->get_file_items($item_id);
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
            unset($data['stored_files']);
            $data['items_file_record']=$items_file_record;
            $data['users'] = System_helper::get_users_info(array());
            $ajax['system_content'][]=array('id'=>$html_id,'html'=>$this->load->view($this->controller_url.'/details',$data,true));
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
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->json_return($ajax);
        }
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
