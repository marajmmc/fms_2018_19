<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
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
        <div id="collapse_basic_info" class="panel-collapse collapse in">
            <table class="table table-bordered table-responsive system_table_details_view">
                <thead>
                <tr>
                    <th class="widget-header header_caption"><label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CATEGORY_NAME'); ?></label></th>
                    <th class=" header_value"><label class="control-label"><?php echo $item['category_name'] ?></label></th>
                    <th class="widget-header header_caption"><label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_FILE_NAME'); ?></label></th>
                    <th class=""><label class="control-label"><?php echo $item['name'] ?></label></th>
                </tr>
                <tr>
                    <th class="widget-header header_caption"><label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_SUB_CATEGORY_NAME'); ?></label></th>
                    <th class=" header_value"><label class="control-label"><?php echo $item['sub_category_name'] ?></label></th>
                    <th class="widget-header header_caption"><label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DATE_OPENING'); ?></label></th>
                    <th class=""><label class="control-label"><?php echo System_helper::display_date($item['date_start']); ?></label></th>
                </tr>
                <tr>
                    <th class="widget-header header_caption"><label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CLASS_NAME'); ?></label></th>
                    <th class=" header_value"><label class="control-label"><?php echo $item['class_name'] ?></label></th>
                    <th class="widget-header header_caption"><label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_RESPONSIBLE_EMPLOYEE'); ?></label></th>
                    <th class=""><label class="control-label"><?php echo $item['responsible_employee'] ?></label></th>
                </tr>
                <tr>
                    <th class="widget-header header_caption"><label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME'); ?></label></th>
                    <th class=" header_value"><label class="control-label"><?php echo $item['type_name'] ?></label></th>
                    <th class="widget-header header_caption"><label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_HC_LOCATION'); ?></label></th>
                    <th class=""><label class="control-label"><?php echo $item['hc_location'] ?></label></th>
                </tr>
                <tr>
                    <th class="widget-header header_caption"></th>
                    <th></th>
                    <th class="widget-header header_caption"><label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_FILE_STATUS'); ?></label></th>
                    <th class=""><label class="control-label"><?php echo $item['status_file']; ?></label></th>
                </tr>
                <tr>
                    <th class="widget-header header_caption"></th>
                    <th></th>
                    <th class="widget-header header_caption"><label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DEPARTMENT_NAME'); ?></label></th>
                    <th class=" header_value"><label class="control-label"><?php echo $item['department_name'] ?></label></th>
                </tr>
                <tr>
                    <th class="widget-header header_caption"></th>
                    <th></th>
                    <th class="widget-header header_caption"><label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_COMPANY_NAME'); ?></label></th>
                    <th class=" header_value"><label class="control-label"><?php echo $item['company_name'] ?></label></th>

                </tr>
                <tr>
                    <th class="widget-header header_caption"><label class="control-label pull-right">File Created By</label></th>
                    <th class=""><label class="control-label"><?php echo $users[$item['user_created']]['name'] ?></label></th>
                    <th class="widget-header header_caption"><label class="control-label pull-right">File Created Time</label></th>
                    <th class=" header_value"><label class="control-label"><?php echo System_helper::display_date_time($item['date_created']);?></label></th>
                </tr>
                <?php if($item['date_updated']){?>
                    <tr>
                        <th class="widget-header header_caption"><label class="control-label pull-right">File Updated By</label></th>
                        <th class=""><label class="control-label"><?php echo $users[$item['user_updated']]['name'] ?></label></th>
                        <th class="widget-header header_caption"><label class="control-label pull-right">File Updated Time</label></th>
                        <th class=" header_value"><label class="control-label"><?php echo System_helper::display_date_time($item['date_updated']);?></label></th>
                    </tr>
                <?php } ?>
                </thead>
            </table>
        </div>
    </div>
    <div class="panel panel-default item_panel">
        <div class="panel-heading">
            <h4 class="panel-title">
                <label class=""><a class="external text-success" data-toggle="collapse" data-target="#collapse_item_info" href="#">+ File Item Info</a></label>
            </h4>
        </div>
        <div id="collapse_item_info" class="panel-collapse collapse in item_panel_content">
            <div class="panel-group" id="accordion">
                <?php
                $location=$CI->config->item('system_base_url_picture');
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
                                    <a class="external" data-toggle="collapse" href="#collapse_<?php echo $file_item['id']; ?>"><?php echo '+ '.$file_item['name']. ' ('.$items_file_record[$file_item['id']].' records)'; ?></a>
                                </h4>
                            </div>
                            <div id="collapse_<?php echo $file_item['id']; ?>" class="panel-collapse collapse <?php if($items_file_record[$file_item['id']]>0){echo 'in';}?>">
                                <div class="panel-body">
                                    <div style="overflow-x: auto;" class="row show-grid">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th width="110">Entry Date</th>
                                                <th width="280">Picture/Thumbnail</th>
                                                <th width="350">Create And Update Info</th>

                                                <th><?php echo $CI->lang->line('LABEL_REMARKS'); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if(!isset($item_files[$file_item['id']]))
                                            {
                                                $item_files[$file_item['id']]=array();
                                            }
                                            foreach($item_files[$file_item['id']] as $file)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?php echo System_helper::display_date($file['date_entry']); ?></td>
                                                    <td>
                                                        <?php
                                                        if(substr($file['mime_type'],0,5)=='image')
                                                        {
                                                            ?>
                                                            <a href="<?php echo $location.$file['file_location']; ?>" class="external" target="_blank"><img class="img img-thumbnail img-responsive" style="max-width: 250px;max-height:150px" src="<?php echo $location.$file['file_location']; ?>" title="<?php echo $file['file_name']; ?>"></a>
                                                        <?php
                                                        }
                                                        else
                                                        {
                                                            $extension=pathinfo($file['file_name'],PATHINFO_EXTENSION);
                                                            if(strtolower($extension)=='pdf')
                                                            {
                                                                $href_text='Read the PDF File';
                                                            }
                                                            else
                                                            {
                                                                $href_text='Download the '.strtoupper($extension).' File';
                                                            }
                                                            ?>
                                                            <a href="<?php echo $location.$file['file_location']; ?>" class="btn btn-success external" target="_blank" title="<?php echo $file['file_name'];?>"><?php echo $href_text; ?></a>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <i>File Created By</i><b> <i>:: </i></b><i><?php echo $users[$file['user_created']]['name'] ?></i><br/>
                                                        <i>File Created Time</i><b> <i>:: </i></b><i><?php echo System_helper::display_date_time($file['date_created']);?></i><br/>
                                                        <?php if($file['user_updated']){?>
                                                            <i>File Updated By</i><b> <i>:: </i></b><i><?php echo $users[$file['user_updated']]['name'] ?></i><br/>
                                                            <i>File Updated Time</i><b> <i>:: </i></b><i><?php echo System_helper::display_date_time($file['date_updated']);?></i>
                                                        <?php } ?>

                                                    </td>

                                                    <td><?php echo nl2br($file['remarks']); ?></td>
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
    </div>
</div>

