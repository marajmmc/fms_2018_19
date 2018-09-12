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
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label for="id_category" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CATEGORY_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_category" class="form-control" tabindex="-1">
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
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
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
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
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
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
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
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
                <label for="name" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="item[name]" id="name" class="form-control" value="<?php echo $item['name'];?>"/>
            </div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label for="ordering" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_ORDER');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="item[ordering]" id="ordering" class="form-control float_type_positive" value="<?php echo $item['ordering'] ?>" >
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_REMARKS');?> </label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <textarea name="item[remarks]" id="description" class="form-control" ><?php echo $item['remarks'];?></textarea>
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
        //$(".datepicker").datepicker({dateFormat : display_date_format});
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