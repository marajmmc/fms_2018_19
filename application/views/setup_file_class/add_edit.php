<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();
$action_buttons = array();
$action_buttons[] = array
(
    'label' => $CI->lang->line("ACTION_BACK"),
    'href' => site_url($CI->controller_url)
);
if ((isset($CI->permissions['action1']) && ($CI->permissions['action1'] == 1)) || (isset($CI->permissions['action2']) && ($CI->permissions['action2'] == 1)))
{
    $action_buttons[] = array
    (
        'type' => 'button',
        'label' => $CI->lang->line("ACTION_SAVE"),
        'id' => 'button_action_save',
        'data-form' => '#save_form'
    );
    $action_buttons[] = array
    (
        'type' => 'button',
        'label' => $CI->lang->line("ACTION_SAVE_NEW"),
        'id' => 'button_action_save_new',
        'data-form' => '#save_form'
    );
}
$action_buttons[] = array(
    'type' => 'button',
    'label' => $CI->lang->line("ACTION_CLEAR"),
    'id' => 'button_action_clear',
    'data-form' => '#save_form'
);
$CI->load->view('action_buttons', array('action_buttons' => $action_buttons));

?>
<form id="save_form" action="<?php echo site_url($CI->controller_url . '/index/save'); ?>" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $item['id'] ?>"/>
    <input type="hidden" id="system_save_new_status" name="system_save_new_status" value="0"/>

    <div class="row widget">
        <div class="widget-header">
            <div class="title">
                <?php echo $title; ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CATEGORY_NAME'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_category" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php foreach($categories as $category) { ?>
                        <option value="<?php echo $category['id']?>" <?php if($category['id']==$item['id_category']){ echo 'selected';}?>><?php echo $category['name'];?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row show-grid" id="sub_category_container" style="<?php echo (!($item['id_sub_category']>0))? 'display:none':''; ?>">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_SUB_CATEGORY_NAME'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_sub_category" name="item[id_sub_category]" class="form-control">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php foreach($sub_categories as $sub_category){ ?>
                        <option value="<?php echo $sub_category['id']?>" <?php if($sub_category['id']==$item['id_sub_category']){echo "selected";}?>><?php echo $sub_category['name'];?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CLASS_NAME'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="item[name]" id="name" class="form-control " value="<?php echo $item['name']; ?>"/>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_ORDER'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="item[ordering]" id="ordering" class="form-control float_type_positive " value="<?php echo $item['ordering']; ?>"/>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS'); ?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <textarea name="item[remarks]" id="remarks" class="form-control"><?php echo $item['remarks'] ?></textarea>
            </div>
        </div>

        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label for="status" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_STATUS'); ?>
                    <span style="color:#FF0000">*</span>
                </label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="status" name="item[status]" class="form-control">
                    <option value="<?php echo $CI->config->item('system_status_active'); ?>" <?php echo ($item['status'] == $CI->config->item('system_status_active')) ? "selected" : ""; ?> >
                        <?php echo $CI->lang->line('ACTIVE') ?>
                    </option>
                    <option value="<?php echo $CI->config->item('system_status_inactive'); ?>" <?php echo ($item['status'] == $CI->config->item('system_status_inactive')) ? "selected" : ""; ?> >
                        <?php echo $CI->lang->line('INACTIVE') ?>
                    </option>
                </select>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</form>

<script>
    jQuery(document).ready(function($)
    {
        $(document).on("change","#id_category",function()
        {
            $("#id_sub_category").html(get_dropdown_with_select(""));
            $("#id_class").html(get_dropdown_with_select(""));
            $("#id_type").html(get_dropdown_with_select(""));

            var id_category=$("#id_category").val();
            $("#sub_category_container").hide();
            $("#class_container").hide();
            $("#type_container").hide();
            if(id_category>0)
            {
                $("#sub_category_container").show();
                $("#class_container").hide();
                $("#type_container").hide();
                if(system_sub_categories[id_category]!==undefined)
                {
                    $('#id_sub_category').html(get_dropdown_with_select(system_sub_categories[id_category]));
                }
            }
        });
    });
</script>
