<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_controller extends Root_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        die();
    }
    public function get_sub_categories_by_category_id()
    {
        $html_container_id='#id_sub_category';
        if($this->input->post('html_container_id'))
        {
            $html_container_id=$this->input->post('html_container_id');
        }
        $id_category=$this->input->post('id_category');
        $data['items']=Query_helper::get_info($this->config->item('table_fms_setup_file_sub_category'),array('id value','name text'),array('id_category='.$id_category,'status="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
        $ajax['system_content'][]=array('id'=>$html_container_id,'html'=>$this->load->view('dropdown_with_select',$data,true));
        $ajax['status']=true;
        $this->json_return($ajax);
    }
    public function get_classes_by_sub_category_id()
    {
        $html_container_id='#id_class';
        if($this->input->post('html_container_id'))
        {
            $html_container_id=$this->input->post('html_container_id');
        }
        $id_sub_category=$this->input->post('id_sub_category');
        $data['items']=Query_helper::get_info($this->config->item('table_fms_setup_file_class'),array('id value','name text'),array('id_sub_category='.$id_sub_category,'status="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
        $ajax['system_content'][]=array('id'=>$html_container_id,'html'=>$this->load->view('dropdown_with_select',$data,true));
        $ajax['status']=true;
        $this->json_return($ajax);
    }
    public function get_types_by_class_id()
    {
        $html_container_id='#id_type';
        if($this->input->post('html_container_id'))
        {
            $html_container_id=$this->input->post('html_container_id');
        }
        $id_class=$this->input->post('id_class');
        $data['items']=Query_helper::get_info($this->config->item('table_fms_setup_file_type'),array('id value','name text'),array('id_class='.$id_class,'status="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
        $ajax['system_content'][]=array('id'=>$html_container_id,'html'=>$this->load->view('dropdown_with_select',$data,true));
        $ajax['status']=true;
        $this->json_return($ajax);
    }
    public function get_names_by_type_id()
    {
        $html_container_id='#id_name';
        if($this->input->post('html_container_id'))
        {
            $html_container_id=$this->input->post('html_container_id');
        }
        $id_type=$this->input->post('id_type');
        $data['items']=Query_helper::get_info($this->config->item('table_fms_setup_file_name'),array('id value','name text'),array('id_type='.$id_type,'status="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
        $ajax['system_content'][]=array('id'=>$html_container_id,'html'=>$this->load->view('dropdown_with_select',$data,true));
        $ajax['status']=true;
        $this->json_return($ajax);
    }
    public function get_employees_by_company_department()
    {
        $html_container_id='#employee_id';
        if($this->input->post('html_container_id'))
        {
            $html_container_id=$this->input->post('html_container_id');
        }
        $id_company=$this->input->post('id_company');
        $id_department=$this->input->post('id_department');

        $this->db->select("u.id value,CONCAT(ui.name,' - ',u.employee_id) AS text");
        $this->db->from($this->config->item('table_login_setup_user').' u');
        $this->db->join($this->config->item('table_login_setup_user_info').' ui','u.id=ui.user_id','INNER');
        $this->db->join($this->config->item('table_login_setup_users_company').' uc','u.id=uc.user_id','INNER');
        $this->db->where('u.status',$this->config->item('system_status_active'));
        $this->db->where('ui.revision',1);
        $this->db->where('uc.revision',1);
        if($id_company>0)
        {
            $this->db->where('uc.company_id',$id_company);
        }
        if($id_department>0)
        {
            $this->db->where('ui.department_id',$id_department);
        }

        $this->db->order_by('u.employee_id');
        $this->db->group_by('u.id');
        $data['items']=$this->db->get()->result_array();
        $ajax['system_content'][]=array('id'=>$html_container_id,'html'=>$this->load->view('dropdown_with_select',$data,true));
        $ajax['status']=true;
        $this->json_return($ajax);
    }
}
