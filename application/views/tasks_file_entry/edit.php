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
    'label'=>$CI->lang->line("ACTION_REFRESH"),
    'href'=>site_url($CI->controller_url.'/index/edit/'.$item['id'])
);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<style>
    .item_panel
    {
        margin: 10px;
    }
    .item_panel_content
    {
        padding-top: 15px;
    }
</style>
<form id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
<input type="hidden" name="id" value="<?php echo $item['id']; ?>">
<input type="hidden" name="file_open_time_for_edit" value="<?php echo time(); ?>">
<div class="row widget">
    <div class="widget-header">
        <div class="title">
            <?php echo $title;?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="panel panel-default item_panel">
        <div class="panel-heading">
            <h4 class="panel-title">
                <label class=""><a class="external text-danger" data-toggle="collapse" data-target="#collapse_basic_info" href="#">+ Basic Info</a></label>
            </h4>
        </div>
        <div id="collapse_basic_info" class="panel-collapse collapse in item_panel_content">
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_FILE_NAME'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $item['name'] ?></label>
                </div>
            </div>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_RESPONSIBLE_EMPLOYEE'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $item['responsible_employee'] ?></label>
                </div>
            </div>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DATE_OPENING'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo System_helper::display_date($item['date_start']); ?></label>
                </div>
            </div>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CATEGORY_NAME'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $item['category_name'] ?></label>
                </div>
            </div>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_SUB_CATEGORY_NAME'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $item['sub_category_name'] ?></label>
                </div>
            </div>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CLASS_NAME'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $item['class_name'] ?></label>
                </div>
            </div>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $item['type_name'] ?></label>
                </div>
            </div>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_COMPANY_NAME'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $item['company_name'] ?></label>
                </div>
            </div>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DEPARTMENT_NAME'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $item['department_name'] ?></label>
                </div>
            </div>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_NUMBER_OF_PAGE'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $item['number_of_page'] ?></label>
                </div>
            </div>
            <?php
            if($file_permissions['action1']==1 || $file_permissions['action2']==1 || $file_permissions['action3']==1)
            {
                ?>
                <div class="row show-grid">
                    <div class="col-xs-4">
                        <label class="control-label pull-right" for="id_hc_location"><?php echo $CI->lang->line('LABEL_HC_LOCATION'); ?></label>
                    </div>
                    <div class="col-sm-4 col-xs-8">
                        <label><?php echo $item['hc_location']; ?></label>
                        <button type="button" class="btn btn-primary btn-sm fms_tasks_edit">Change</button>
                        <div style="display: none;">
                            <select data-name="id_hc_location" id="id_hc_location" class="form-control">
                                <option value=""><?php echo $CI->lang->line('SELECT'); ?></option>
                                <?php
                                foreach($hc_locations as $hc_location)
                                {
                                    ?>
                                    <option value="<?php echo $hc_location['value']; ?>" <?php if($hc_location['value']==$item['id_hc_location']){echo ' selected';} ?>><?php echo $hc_location['text']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row show-grid">
                    <div class="col-xs-4">
                        <label class="control-label pull-right" for="status_file"><?php echo $CI->lang->line('LABEL_FILE_STATUS'); ?></label>
                    </div>
                    <div class="col-sm-4 col-xs-8">
                        <label><?php echo $item['status_file']; ?></label>
                        <?php
                        if($item['status_file']==$CI->config->item('system_status_file_open'))
                        {
                            ?>
                            <button type="button" class="btn btn-primary btn-sm fms_tasks_edit">Change</button>
                            <div style="display: none;">
                                <select data-name="status_file" id="status_file" class="form-control">
                                    <option value="<?php echo $CI->config->item('system_status_file_open'); ?>" <?php if($CI->config->item('system_status_file_open')==$item['status_file']){echo ' selected';} ?>><?php echo $CI->lang->line('LABEL_STATUS_OPEN'); ?></option>
                                    <option value="<?php echo $CI->config->item('system_status_file_close'); ?>" <?php if($CI->config->item('system_status_file_close')==$item['status_file']){echo ' selected';} ?>><?php echo $CI->lang->line('LABEL_STATUS_CLOSE'); ?></option>
                                </select>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            else
            {
                ?>
                <div class="row show-grid">
                    <div class="col-xs-4">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_HC_LOCATION'); ?></label>
                    </div>
                    <div class="col-sm-4 col-xs-8">
                        <label><?php echo $item['hc_location'] ?></label>
                    </div>
                </div>
                <div class="row show-grid">
                    <div class="col-xs-4">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_FILE_STATUS'); ?></label>
                    </div>
                    <div class="col-sm-4 col-xs-8">
                        <label><?php echo $item['status_file']; ?></label>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right">File Created By</label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $users[$item['user_created']]['name'] ?></label>
                </div>
            </div>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right">File Created Time</label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo System_helper::display_date_time($item['date_created']);?></label>
                </div>
            </div>
            <?php if($item['date_updated']){?>
                <div class="row show-grid">
                    <div class="col-xs-4">
                        <label class="control-label pull-right">File Updated By</label>
                    </div>
                    <div class="col-sm-4 col-xs-8">
                        <label><?php echo $users[$item['user_updated']]['name'] ?></label>
                    </div>
                </div>
                <div class="row show-grid">
                    <div class="col-xs-4">
                        <label class="control-label pull-right">File Updated Time</label>
                    </div>
                    <div class="col-sm-4 col-xs-8">
                        <label><?php echo System_helper::display_date_time($item['date_updated']);?></label>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="panel panel-default item_panel">
        <div class="panel-heading">
            <h4 class="panel-title">
                <label class=""><a class="external text-success" data-toggle="collapse" data-target="#collapse_item_info" href="#">+ File Item Info</a></label>
            </h4>
        </div>
        <div id="collapse_item_info" class="panel-collapse collapse in item_panel_content">
            <div id="files_container" data-current-id="0">
                <div class="panel-group" id="accordion">
                    <?php
                    $location=$CI->config->item('system_image_base_url');
                    foreach($file_items as $file_item)
                    {
                        $show_item=true;
                        $active_add_more=true;
                        if($file_item['status']==$CI->config->item('system_status_active'))
                        {
                            $show_item=true;
                            $active_add_more=true;
                        }
                        elseif(isset($stored_files[$file_item['id']]))
                        {
                            $show_item=true;
                            $active_add_more=false;
                        }
                        else
                        {
                            $show_item=false;
                            $active_add_more=false;
                        }
                        if($show_item)
                        {
                            ?>
                            <div class="panel panel-success item_panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="external" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $file_item['id']; ?>"><?php echo '+ '.$file_item['name']; ?></a>
                                    </h4>
                                </div>
                                <div id="collapse_<?php echo $file_item['id']; ?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div style="overflow-x: auto;" class="row show-grid">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th style="min-width: 250px;">File/Picture</th>
                                                    <th style="min-width: 50px;">Upload</th>
                                                    <th style="min-width: 50px;">Entry Date</th>
                                                    <th style="min-width: 100px;"><?php echo $CI->lang->line('LABEL_REMARKS'); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if(!isset($stored_files[$file_item['id']]))
                                                {
                                                    $stored_files[$file_item['id']]=array();
                                                }
                                                foreach($stored_files[$file_item['id']] as $file)
                                                {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <div class="preview_container_file" id="preview_container_file_old_<?php echo $file['id']; ?>">
                                                                <?php
                                                                if(substr($file['mime_type'],0,5)=='image')
                                                                {
                                                                    ?>
                                                                    <img style="max-width: 250px;" src="<?php echo $location.$file['file_path']; ?>">
                                                                <?php
                                                                }
                                                                else
                                                                {
                                                                    ?>
                                                                    <a href="<?php echo $location.$file['file_path']; ?>" class="external" target="_blank"><?php echo $file['name']; ?></a>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if($file_permissions['action2']==1 && $file_permissions['editable'])
                                                            {
                                                                ?>
                                                                <input type="file" name="file_old_<?php echo $file['id']; ?>" data-current-id="<?php echo $file['id']; ?>" data-preview-container="#preview_container_file_old_<?php echo $file['id']; ?>" class="browse_button_old"><br>
                                                            <?php
                                                            }
                                                            if($file_permissions['action3']==1 && $file_permissions['editable'])
                                                            {
                                                                ?>
                                                                <button type="button" class="btn btn-danger system_button_add_delete"><?php echo $CI->lang->line('DELETE'); ?></button>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if($file_permissions['action2']==1 && $file_permissions['editable'])
                                                            {
                                                                ?>
                                                                <input type="text" name="items_old[<?php echo $file['id']; ?>][date_entry]" class="form-control datepicker_old" value="<?php echo System_helper::display_date($file['date_entry']); ?>" readonly>
                                                            <?php
                                                            }
                                                            elseif($file_permissions['action3']==1 && $file_permissions['editable'])
                                                            {
                                                                ?>
                                                                <input type="hidden" name="items_old[<?php echo $file['id']; ?>][date_entry]" value="<?php echo System_helper::display_date($file['date_entry']); ?>">
                                                            <?php
                                                            }
                                                            else
                                                            {
                                                                echo System_helper::display_date($file['date_entry']);
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if($file_permissions['action2']==1 && $file_permissions['editable'])
                                                            {
                                                                ?>
                                                                <textarea  name="items_old[<?php echo $file['id']; ?>][remarks]" class="form-control"><?php echo $file['remarks']; ?></textarea>
                                                            <?php
                                                            }
                                                            elseif($file_permissions['action3']==1 && $file_permissions['editable'])
                                                            {
                                                                ?>
                                                                <input type="hidden" name="items_old[<?php echo $file['id']; ?>][remarks]" value="<?php echo $file['remarks']; ?>">
                                                            <?php
                                                            }
                                                            else
                                                            {
                                                                echo $file['remarks'];
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <?php
                                        if($file_permissions['action1']==1 && $active_add_more && $file_permissions['editable'])
                                        {
                                            ?>
                                            <div class="row show-grid">
                                                <div class="col-xs-4"></div>
                                                <div class="col-xs-4">
                                                    <button type="button" class="btn btn-warning system_button_add_more" data-file-item-id="<?php echo $file_item['id']; ?>">
                                                        <?php echo $CI->lang->line('LABEL_ADD_MORE'); ?>
                                                    </button>
                                                </div>
                                                <div class="col-xs-4"></div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
</form>

<div id="system_content_add_more" style="display: none;">
    <table>
        <tbody>
        <tr>
            <td>
                <div class="preview_container_file">
                </div>
            </td>
            <td>
                <input type="file" class="browse_button_new"><br>
                <button type="button" class="btn btn-danger system_button_add_delete"><?php echo $CI->lang->line('DELETE'); ?></button>
                <input type="hidden" class="file_item">
            </td>
            <td>
                <input type="text" class="form-control datepicker date_entry" value="<?php echo System_helper::display_date(time()); ?>" readonly>
            </td>
            <td>
                <textarea class="form-control remarks"></textarea>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    jQuery(document).ready(function()
    {
        system_off_events();
        $('.datepicker_old').datepicker({dateFormat : display_date_format});
        $('.browse_button_old').filestyle({input: false,icon: false,buttonText: "Edit",buttonName: "btn-primary"});
        $(document).on("click", ".system_button_add_more", function(event)
        {
            var current_id=parseInt($('#files_container').attr('data-current-id'))+1;
            $('#files_container').attr('data-current-id',current_id);
            var content_id='#system_content_add_more table tbody';

            $(content_id+' .preview_container_file').attr('id','preview_container_file_'+current_id);

            $(content_id+' .browse_button_new').attr('data-preview-container','#preview_container_file_'+current_id);
            $(content_id+' .browse_button_new').attr('name','file_new_'+current_id);
            $(content_id+' .browse_button_new').attr('id','file_new_'+current_id);
            $(content_id+' .browse_button_new').attr('data-current-id',current_id);

            $(content_id+' .file_item').attr('name','items_new['+current_id+'][id_file_item]');
            $(content_id+' .file_item').attr('value',$(this).attr("data-file-item-id"));
            $(content_id+' .date_entry').attr('name','items_new['+current_id+'][date_entry]');
            $(content_id+' .date_entry').attr('id','date_entry_'+current_id);

            $(content_id+' .remarks').attr('name','items_new['+current_id+'][remarks]');

            var html=$(content_id).html();
            $(this).closest('.panel-collapse').find('tbody').append(html);

            $(content_id+' .preview_container_file').removeAttr('id');

            $(content_id+' .browse_button_new').removeAttr('data-preview-container');
            $(content_id+' .browse_button_new').removeAttr('name');
            $(content_id+' .browse_button_new').removeAttr('id');
            $(content_id+' .browse_button_new').removeAttr('data-current-id');

            $(content_id+' .file_item').removeAttr('name');
            $(content_id+' .file_item').removeAttr('value');
            $(content_id+' .date_entry').removeAttr('name');
            $(content_id+' .date_entry').removeAttr('id');
            $(content_id+' .remarks').removeAttr('name');

            $('#file_new_'+current_id).filestyle({input: false,icon: false,buttonText: "Upload",buttonName: "btn-primary"});
            $('#date_entry_'+current_id).datepicker({dateFormat : display_date_format});
        });
        $(document).on("click", ".system_button_add_delete", function(event)
        {
            $(this).closest('tr').remove();
        });
        $(document).on("click",".fms_tasks_edit",function(event)
        {
            var div_to_change=$(this).closest("div");
            div_to_change.html(div_to_change.find("div").html());
            var select_to_change=div_to_change.find("select");
            select_to_change.attr("name",select_to_change.attr("data-name"));
        });
    });
</script>
