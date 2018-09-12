<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$action_buttons=array();
$action_buttons[]=array
(
    'label'=>$CI->lang->line("ACTION_BACK"),
    'href'=>site_url($CI->controller_url)
);
if((isset($CI->permissions['action1']) && ($CI->permissions['action1']==1)) || (isset($CI->permissions['action2']) && ($CI->permissions['action2']==1)))
{
    $action_buttons[]=array
    (
        'type'=>'button',
        'label'=>$CI->lang->line("ACTION_SAVE"),
        'id'=>'button_action_save',
        'data-form'=>'#save_form'
    );
    $action_buttons[]=array
    (
        'type'=>'button',
        'label'=>$CI->lang->line("ACTION_SAVE_NEW"),
        'id'=>'button_action_save_new',
        'data-form'=>'#save_form'
    );
}
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("ACTION_CLEAR"),
    'id'=>'button_action_clear',
    'data-form'=>'#save_form'
);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));

?>
<form id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $item['id']?>" />
    <input type="hidden" id="system_save_new_status" name="system_save_new_status" value="0" />
    <div class="row widget">
        <div class="widget-header">
            <div class="title">
                <?php echo $title; ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_COMPANY_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_company" name="item[id_company]" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($companies as $company)
                    {?>
                        <option value="<?php echo $company['value']?>" <?php if($company['value']==$item['id_company']){ echo 'selected';}?>><?php echo $company['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($item['id_department']>0)){echo 'display:none';} ?>" class="row show-grid" id="department_container">
            <div class="col-xs-4">
                <label for="id_department" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DEPARTMENT_NAME'); ?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_department" name="item[id_department]" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($departments as $department)
                    {?>
                        <option value="<?php echo $department['value']?>" <?php if($department['value']==$item['id_department']){ echo 'selected';}?>><?php echo $department['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($item['employee_id']>0)){echo 'display: none';} ?>" class="row show-grid" id="employee_id_container">
            <div class="col-xs-4">
                <label for="employee_id" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_RESPONSIBLE_EMPLOYEE'); ?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="employee_id" name="item[employee_id]" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($employees as $employee)
                    {?>
                        <option value="<?php echo $employee['value']?>" <?php if($employee['value']==$item['employee_id']){ echo 'selected';}?>><?php echo $employee['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label for="id_category" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CATEGORY_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_category" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($categories as $category)
                    {?>
                        <option value="<?php echo $category['value']?>" <?php if($category['value']==$item['id_category']){ echo 'selected';}?>><?php echo $category['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($item['id_sub_category']>0)){echo 'display:none';} ?>" class="row show-grid" id="sub_category_container">
            <div class="col-xs-4">
                <label for="id_sub_category" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_SUB_CATEGORY_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_sub_category" class="form-control">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($sub_categories as $sub_category)
                    {?>
                        <option value="<?php echo $sub_category['value']?>" <?php if($sub_category['value']==$item['id_sub_category']){echo "selected";}?>><?php echo $sub_category['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($item['id_class']>0)){echo 'display:none';} ?>" class="row show-grid" id="class_container">
            <div class="col-xs-4">
                <label for="id_class" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CLASS_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_class" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($classes as $class)
                    {?>
                        <option value="<?php echo $class['value']?>" <?php if($class['value']==$item['id_class']){ echo 'selected';}?>><?php echo $class['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($item['id_type']>0)){echo 'display:none';} ?>" class="row show-grid" id="type_container">
            <div class="col-xs-4">
                <label for="id_type" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_type" name="item[id_type]" class="form-control">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($types as $type)
                    {?>
                        <option value="<?php echo $type['value']?>" <?php if($type['value']==$item['id_type']){echo "selected";}?>><?php echo $type['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label for="id_hc_location" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_HC_LOCATION');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_hc_location" name="item[id_hc_location]" class="form-control">
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
                    <?php
                    foreach($hc_locations as $hc_location)
                    {?>
                        <option value="<?php echo $hc_location['value']?>" <?php if($hc_location['value']==$item['id_hc_location']){echo "selected";}?>><?php echo $hc_location['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label for="name" class="control-label pull-right"><?php echo $this->lang->line('LABEL_FILE_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="item[name]" id="name" class="form-control" value="<?php echo $item['name'];?>"/>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label for="status_file" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_FILE_STATUS');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="status_file" name="item[status_file]" class="form-control">
                    <option value="<?php echo $CI->config->item('system_status_file_open'); ?>" <?php if($CI->config->item('system_status_file_open')==$item['status_file']){echo "selected";}?>><?php echo $CI->lang->line('LABEL_STATUS_OPEN'); ?></option>
                    <option value="<?php echo $CI->config->item('system_status_file_close'); ?>" <?php if($CI->config->item('system_status_file_close')==$item['status_file']){echo "selected";}?>><?php echo $CI->lang->line('LABEL_STATUS_CLOSE'); ?></option>
                </select>
            </div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label for="date_start" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DATE_OPENING'); ?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="item[date_start]" id="date_start" class="form-control datepicker" value="<?php echo $item['date_start'] ?>" readonly="readonly">
            </div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label for="remarks" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_REMARKS'); ?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <textarea name="item[remarks]" id="remarks" class="form-control"><?php echo $item['remarks'] ?></textarea>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right">Ordering<span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="item[ordering]" id="ordering" class="form-control float_type_positive " value="<?php echo $item['ordering'];?>"/>
            </div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label for="status" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_STATUS');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="status" name="item[status]" class="form-control">
                    <!--<option value=""></option>-->
                    <option value="<?php echo $CI->config->item('system_status_active'); ?>" <?php if ($item['status'] == $CI->config->item('system_status_active')) { echo "selected='selected'"; } ?> ><?php echo $CI->lang->line('ACTIVE') ?></option>
                    <option value="<?php echo $CI->config->item('system_status_inactive'); ?>" <?php if ($item['status'] == $CI->config->item('system_status_inactive')) { echo "selected='selected'"; } ?> ><?php echo $CI->lang->line('INACTIVE') ?></option>
                </select>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</form>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        $(".datepicker").datepicker({dateFormat : display_date_format});

        $(document).on("change","#id_company",function()
        {
            $("#id_department").val("");
            $("#employee_id").val("");
            var id_company=$('#id_company').val();
            if(id_company>0)
            {
                $('#department_container').show();
                $('#employee_id_container').hide();
            }
            else
            {
                $('#department_container').hide();
                $('#employee_id_container').hide();
            }
        });
        $(document).on("change","#id_department",function()
        {
            $("#employee_id").empty();
            var id_company=$('#id_company').val();
            var id_department=$('#id_department').val();
            if(id_department>0)
            {
                $('#employee_id_container').show();
                $.ajax(
                    {
                        url: "<?php echo site_url('common_controller/get_employees_by_company_department'); ?>",
                        type: 'POST',
                        datatype: "JSON",
                        data:
                        {
                            html_container_id:'#employee_id',
                            id_department:id_department,
                            id_company:id_company
                        },
                        success: function (data, status)
                        {

                        },
                        error: function (xhr, desc, err)
                        {
                            console.log("error");
                        }
                    });
            }
            else
            {
                $('#employee_id_container').hide();
            }
        });
        $(document).on("change","#id_category",function()
        {
            $("#id_sub_category").val("");
            $("#id_class").val("");
            $("#id_type").val("");
            var id_category=$("#id_category").val();
            if(id_category>0)
            {
                $("#sub_category_container").show();
                $("#class_container").hide();
                $("#type_container").hide();
                $.ajax(
                    {
                        url: '<?php echo site_url('common_controller/get_sub_categories_by_category_id'); ?>',
                        type: 'POST',
                        datatype: "JSON",
                        data:
                        {
                            html_container_id:'#id_sub_category',
                            id_category:id_category
                        },
                        success: function (data, status)
                        {

                        },
                        error: function (xhr, desc, err)
                        {
                            console.log("error");
                        }
                    });
            }
            else
            {
                $("#sub_category_container").hide();
                $("#class_container").hide();
                $("#type_container").hide();
            }
        });
        $(document).on("change","#id_sub_category",function()
        {
            $("#id_class").val("");
            $("#id_type").val("");
            var id_sub_category=$('#id_sub_category').val();
            if(id_sub_category>0)
            {
                $('#class_container').show();
                $('#type_container').hide();
                $.ajax(
                    {
                        url: '<?php echo site_url('common_controller/get_classes_by_sub_category_id'); ?>',
                        type: 'POST',
                        datatype: "JSON",
                        data:
                        {
                            html_container_id:'#id_class',
                            id_sub_category:id_sub_category
                        },
                        success: function (data, status)
                        {

                        },
                        error: function (xhr, desc, err)
                        {
                            console.log("error");
                        }
                    });
            }
            else
            {
                $('#class_container').hide();
                $('#type_container').hide();
            }
        });
        $(document).on("change","#id_class",function()
        {
            $("#id_type").val("");
            var id_class=$('#id_class').val();
            if(id_class>0)
            {
                $('#type_container').show();
                $.ajax(
                    {
                        url: '<?php echo site_url('common_controller/get_types_by_class_id'); ?>',
                        type: 'POST',
                        datatype: "JSON",
                        data:
                        {
                            html_container_id:'#id_type',
                            id_class:id_class
                        },
                        success: function (data, status)
                        {

                        },
                        error: function (xhr, desc, err)
                        {
                            console.log("error");
                        }
                    });
            }
            else
            {
                $('#type_container').hide();
            }
        });
    });
</script>