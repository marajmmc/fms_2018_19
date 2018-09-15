<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$action_buttons=array();
$action_buttons[]=array(
    'label'=>$CI->lang->line("ACTION_BACK"),
    'href'=>site_url($CI->controller_url)
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("ACTION_SAVE"),
    'id'=>'button_action_save',
    'data-form'=>'#save_form'
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("ACTION_SAVE_NEW"),
    'id'=>'button_action_save_new',
    'data-form'=>'#save_form'
);
$action_buttons[]=array(
    'type'=>'button',
    'label'=>$CI->lang->line("ACTION_CLEAR"),
    'id'=>'button_action_clear',
    'data-form'=>'#save_form'
);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<form id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save_new_file');?>" method="post">
<input type="hidden" id="system_save_new_status" name="system_save_new_status" value="0">
<div class="row widget">
<div class="widget-header">
    <div class="title">
        <?php echo $title; ?>
    </div>
    <div class="clearfix"></div>
</div>

<div style="" class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_COMPANY_NAME'); ?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <?php
        $user_companies=count($companies);
        if($user_companies<1)
        {
            echo 'Not Assigned';
        }
        elseif($user_companies==1)
        {
            echo $companies[0]['text'];
            ?>
            <input type="hidden" name="item[id_company]" value="<?php echo $companies[0]['value']; ?>">
        <?php
        }
        else
        {
            ?>
            <select id="id_company" name="item[id_company]" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($companies as $company)
                {?>
                    <option value="<?php echo $company['value']?>"><?php echo $company['text']; ?></option>
                <?php
                }
                ?>
            </select>
        <?php
        }
        ?>
    </div>
</div>

<div style="" class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DEPARTMENT_NAME'); ?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <?php echo $department['text']; ?>
        <input type="hidden" name="item[id_department]" value="<?php echo $department['value']; ?>">
    </div>
</div>

<div style="" class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_RESPONSIBLE_EMPLOYEE'); ?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <?php echo $employee['text']; ?>
        <input type="hidden" name="item[employee_id]" value="<?php echo $employee['value']; ?>">
    </div>
</div>

<div style="" class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CATEGORY_NAME');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <?php
        $category_counter=count($categories);
        if($category_counter==1)
        {
            $category=current($categories);
            echo $category['text'];
        }
        else
        {
            ?>
            <select id="id_category" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($categories as $category)
                {?>
                    <option value="<?php echo $category['value']?>"><?php echo $category['text']; ?></option>
                <?php
                }
                ?>
            </select>
        <?php
        }
        ?>
    </div>
</div>

<div style="<?php if($category_counter>1){echo 'display:none';} ?>" class="row show-grid" id="sub_category_container">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_SUB_CATEGORY_NAME');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <?php
        if($category_counter==1 && $sub_category_counter==1)
        {
            $sub_category=current(current($sub_categories));
            echo $sub_category['text'];
        }
        elseif($category_counter==1 && $sub_category_counter>1)
        {
            ?>
            <select id="id_sub_category" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                $sub_categories_filter=current($sub_categories);
                foreach($sub_categories_filter as $sub_category)
                {?>
                    <option value="<?php echo $sub_category['value']?>"><?php echo $sub_category['text'];?></option>
                <?php
                }
                ?>
            </select>
        <?php
        }
        else
        {
            ?>
            <select id="id_sub_category" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        <?php
        }
        ?>
    </div>
</div>

<div style="<?php if($sub_category_counter>1){echo 'display:none';} ?>" class="row show-grid" id="class_container">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CLASS_NAME');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <?php
        if($sub_category_counter==1 && $class_counter==1)
        {
            $class=current(current($classes));
            echo $class['text'];
        }
        elseif($sub_category_counter==1 && $class_counter>1)
        {
            ?>
            <select id="id_class" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                $classes_filter=current($classes);
                foreach($classes_filter as $class)
                {?>
                    <option value="<?php echo $class['value']?>"><?php echo $class['text'];?></option>
                <?php
                }
                ?>
            </select>
        <?php
        }
        else
        {
            ?>
            <select id="id_class" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        <?php
        }
        ?>
    </div>
</div>

<div style="<?php if($class_counter>1){echo 'display:none';} ?>" class="row show-grid" id="type_container">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <?php
        if($class_counter==1 && $type_counter==1)
        {
            $type=current(current($types));
            echo $type['text'];
            ?>
            <input type="hidden" name="item[id_type]" value="<?php echo $type['value']; ?>">
        <?php
        }
        elseif($class_counter==1 && $type_counter>1)
        {
            ?>
            <select id="id_type" name="item[id_type]" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                $types_filter=current($types);
                foreach($types_filter as $type)
                {?>
                    <option value="<?php echo $type['value']?>"><?php echo $type['text']; ?></option>
                <?php
                }
                ?>
            </select>
        <?php
        }
        else
        {
            ?>
            <select id="id_type" name="item[id_type]" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        <?php
        }
        ?>
    </div>
</div>

<div class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_HC_LOCATION');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <select id="id_hc_location" name="item[id_hc_location]" class="form-control">
            <option value=""><?php echo $CI->lang->line('SELECT');?></option>
            <?php
            foreach($hc_locations as $hc_location)
            {?>
                <option value="<?php echo $hc_location['value']?>"><?php echo $hc_location['text']; ?></option>
            <?php
            }
            ?>
        </select>
    </div>
</div>

<div class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_NAME');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <input type="text" name="item[name]" class="form-control" value=""/>
    </div>
</div>

<div class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_FILE_STATUS');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <select id="status_file" name="item[status_file]" class="form-control">
            <option value="<?php echo $CI->config->item('system_status_file_open'); ?>"><?php echo $CI->lang->line('LABEL_STATUS_OPEN'); ?></option>
            <option value="<?php echo $CI->config->item('system_status_file_close'); ?>"><?php echo $CI->lang->line('LABEL_STATUS_CLOSE'); ?></option>
        </select>
    </div>
</div>

<div style="" class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_ORDER');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <input type="text" name="item[ordering]" class="form-control" value="99">
    </div>
</div>

<div style="" class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DATE_START'); ?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <input type="text" name="item[date_start]" class="form-control datepicker" value="<?php echo System_helper::display_date(time()); ?>">
    </div>
</div>

<div style="" class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_REMARKS'); ?></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <textarea name="item[remarks]" class="form-control"></textarea>
    </div>
</div>
</div>
<div class="clearfix"></div>
</form>
<script type="text/javascript">
    jQuery(document).ready(function()
    {
        system_off_events();
        $(".datepicker").datepicker({dateFormat : display_date_format});
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
