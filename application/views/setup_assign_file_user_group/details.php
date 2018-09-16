<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();

$action_buttons = array();
$action_buttons[] = array(
    'label' => $CI->lang->line("ACTION_BACK"),
    'href' => site_url($CI->controller_url)
);
if (isset($CI->permissions['action2']) && ($CI->permissions['action2'] == 1))
{
    $action_buttons[] = array(
        'label' => $CI->lang->line("ACTION_EDIT"),
        'href' => site_url($CI->controller_url . '/index/search/' . $item_id)
    );
}
$CI->load->view('action_buttons', array('action_buttons' => $action_buttons));

?>
<style>
    .panel {
        border: none
    }

    .normal {
        font-weight: normal !important
    }

    .right-align {
        text-align: right !important
    }

    .center-align {
        text-align: center !important
    }

    span.text-danger {
        font-style: italic;
        color: #FF0000
    }

    .summary-wrap .show-grid {
        margin: 0;
    }

    .summary-wrap .show-grid:nth-child(2) > div {
        padding-top: 5px;
    }
</style>

<div class="row widget">
    <div class="widget-header" style="margin:0">
        <div class="title"><?php echo $title; ?></div>
        <div class="clearfix"></div>
    </div>

    <div class="row show-grid">
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th><?php echo $CI->lang->line('LABEL_CATEGORY_NAME'); ?></th>
                    <th><?php echo $CI->lang->line('LABEL_SUB_CATEGORY_NAME'); ?></th>
                    <th><?php echo $CI->lang->line('LABEL_CLASS_NAME'); ?></th>
                    <th><?php echo $CI->lang->line('LABEL_TYPE_NAME'); ?></th>
                    <th><?php echo $CI->lang->line('LABEL_FILE_NAME'); ?></th>
                    <?php for ($index = 0; $index < $CI->config->item('system_fms_max_actions'); $index++){ ?>
                        <th><?php echo $CI->lang->line('LABEL_ACTION' . $index); ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $check_array = array();
                foreach ($all_files as $file)
                {
                    $is_first_category = false;
                    $is_first_sub_category = false;
                    $is_first_class = false;
                    $is_first_type = false;
                    if (isset($check_array['category'][$file['category_id']]))
                    {
                        $check_array['category'][$file['category_id']] += 1;
                    }
                    else
                    {
                        $is_first_category = '_first';
                        $check_array['category'][$file['category_id']] = 1;
                    }
                    if (isset($check_array['sub_category'][$file['sub_category_id']]))
                    {
                        $check_array['sub_category'][$file['sub_category_id']] += 1;
                    }
                    else
                    {
                        $is_first_sub_category = '_first';
                        $check_array['sub_category'][$file['sub_category_id']] = 1;
                    }
                    if (isset($check_array['class'][$file['class_id']]))
                    {
                        $check_array['class'][$file['class_id']] += 1;
                    }
                    else
                    {
                        $is_first_class = '_first';
                        $check_array['class'][$file['class_id']] = 1;
                    }
                    if (isset($check_array['type'][$file['type_id']]))
                    {
                        $check_array['type'][$file['type_id']] += 1;
                    }
                    else
                    {
                        $is_first_type = '_first';
                        $check_array['type'][$file['type_id']] = 1;
                    }

                    for ($i = 0; $i < $CI->config->item('system_fms_max_actions'); $i++)
                    {
                        $temp_variable_name = 'action' . $i;
                        if ($file[$temp_variable_name] == 1)
                        {
                            $$temp_variable_name = 'ok';
                        }
                        else
                        {
                            $$temp_variable_name = 'remove';
                        }
                    }
                    ?>
                    <tr>
                        <td class="category-<?php echo $file['category_id'] . $is_first_category; ?>">
                            <label><?php echo $file['category_name']; ?></label>
                        </td>
                        <td class="sub_category-<?php echo $file['sub_category_id'] . $is_first_sub_category; ?>">
                            <label><?php echo $file['sub_category_name']; ?></label>
                        </td>
                        <td class="class-<?php echo $file['class_id'] . $is_first_class; ?>">
                            <label><?php echo $file['class_name']; ?></label>
                        </td>
                        <td class="type-<?php echo $file['type_id'] . $is_first_type; ?>">
                            <label><?php echo $file['type_name']; ?></label>
                        </td>
                        <td>
                            <label><?php echo $file['file_name']; ?></label>
                        </td>
                        <?php for ($i = 0; $i < $CI->config->item('system_fms_max_actions'); $i++){
                            $temp_variable_name = 'action' . $i;
                            ?>
                            <td>
                                <span class="glyphicon glyphicon-<?php echo $$temp_variable_name; ?>" aria-hidden="true"></span>
                            </td>
                        <?php } ?>
                    </tr>
                <?php
                }
                if (!isset($check_array['category']))
                {
                    $check_array['category'] = array();
                    $check_array['class'] = array();
                    $check_array['type'] = array();
                    echo '<tr style="text-align: center;font-size: 18px;"><td colspan="9">No data to display.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
