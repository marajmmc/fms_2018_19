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

// ------Counting Category, Subcategory, Class & Type Id's------
$cat_count = $sub_cat_count = $class_count = $type_count = array();
if (!empty($all_files))
{
    foreach ($all_files as $value)
    {
        $cat_count[] = $value['category_id'];
        $sub_cat_count[] = $value['sub_category_id'];
        $class_count[] = $value['class_id'];
        $type_count[] = $value['type_id'];
    }
    $cat_count = array_count_values($cat_count);
    $sub_cat_count = array_count_values($sub_cat_count);
    $class_count = array_count_values($class_count);
    $type_count = array_count_values($type_count);
}
$current_cat = $current_sub_cat = $current_class = $current_type = -1;

//-------------------------------------------------------------
?>
<style> .normal {font-weight: normal !important} </style>

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
            if (!empty($all_files))
            {
                foreach ($all_files as $file)
                {
                    ?>
                    <tr>
                        <?php if ($current_cat != $file['category_id'])
                        {
                            ?>
                            <td rowspan="<?php echo $cat_count[$file['category_id']]; ?>">
                                <label class="normal"><?php echo $file['category_name']; ?></label>
                            </td>
                            <?php
                            $current_cat = $file['category_id'];
                        }
                        if ($current_sub_cat != $file['sub_category_id'])
                        {
                            ?>
                            <td rowspan="<?php echo $sub_cat_count[$file['sub_category_id']]; ?>">
                                <label class="normal"><?php echo $file['sub_category_name']; ?></label>
                            </td>
                            <?php
                            $current_sub_cat = $file['sub_category_id'];
                        }
                        if ($current_class != $file['class_id'])
                        {
                            ?>
                            <td rowspan="<?php echo $class_count[$file['class_id']]; ?>">
                                <label class="normal"><?php echo $file['class_name']; ?></label>
                            </td>
                            <?php
                            $current_class = $file['class_id'];
                        }
                        if ($current_type != $file['type_id'])
                        {
                            ?>
                            <td rowspan="<?php echo $type_count[$file['type_id']]; ?>">
                                <label class="normal"><?php echo $file['type_name']; ?></label>
                            </td>
                            <?php
                            $current_type = $file['type_id'];
                        } ?>
                        <td>
                            <label class="normal"><?php echo $file['file_name']; ?></label>
                        </td>
                        <?php for ($i = 0; $i < $CI->config->item('system_fms_max_actions'); $i++){ ?>
                            <td title="<?php echo $CI->lang->line('LABEL_ACTION' . $i); ?>">
                                <span class="glyphicon <?php echo ($file['action' . $i]) ? 'glyphicon-ok text-primary' : 'glyphicon-remove text-danger'; ?>"></span>
                            </td>
                        <?php } ?>
                    </tr>
                <?php
                }
            }
            else
            {
                ?>
                <tr style="text-align:center">
                    <td colspan="10">- NO RECORD FOUND -</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
