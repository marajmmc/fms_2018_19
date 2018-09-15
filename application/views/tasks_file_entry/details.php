<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI=& get_instance();
$action_buttons=array();
$action_buttons[]=array(
    'label'=>$CI->lang->line("ACTION_BACK"),
    'href'=>site_url($CI->controller_url)
);
if($file_permissions['action2']==1)
{
    $action_buttons[]=array(
        'label'=>$CI->lang->line("ACTION_EDIT"),
        'href'=>site_url($CI->controller_url.'/index/edit/'.$item['id'])
    );
}
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
<div class="row widget">
    <div class="widget-header">
        <div class="title">
            Details for <?php echo $item['name']; ?>
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
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_HC_LOCATION'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $item['hc_location'] ?></label>
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
                    <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_FILE_STATUS'); ?></label>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <label><?php echo $item['status_file']; ?></label>
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
    <div class="panel-group" id="accordion">
        <?php
        $location=$CI->config->item('system_image_base_url');
        foreach($file_items as $file_item)
        {
            $show_item=true;
            if(!isset($stored_files[$file_item['id']]) && $file_item['status']!=$CI->config->item('system_status_active'))
            {
                $show_item=false;
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
                                        <th>File Name</th>
                                        <th>Picture/Thumbnail</th>
                                        <th>Entry Date</th>
                                        <th><?php echo $CI->lang->line('LABEL_REMARKS'); ?></th>
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
                                            <td><?php echo $file['name']; ?></td>
                                            <td>
                                                <?php
                                                if(substr($file['mime_type'],0,5)=='image')
                                                {
                                                    ?>
                                                    <img src="<?php echo $location.$file['file_path']; ?>" style="max-width: 250px;max-height:150px">
                                                <?php
                                                }
                                                else
                                                {
                                                    $extension=pathinfo($file['name'],PATHINFO_EXTENSION);
                                                    if(strtolower($extension)=='pdf')
                                                    {
                                                        $href_text='Read the PDF File';
                                                    }
                                                    else
                                                    {
                                                        $href_text='Download the '.strtoupper($extension).' File';
                                                    }
                                                    ?>
                                                    <a href="<?php echo $location.$file['file_path']; ?>" class="btn btn-success external" target="_blank"><?php echo $href_text; ?></a>
                                                <?php
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo System_helper::display_date($file['date_entry']); ?></td>
                                            <td><?php echo $file['remarks']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
        }
        ?>
    </div>
</div>
