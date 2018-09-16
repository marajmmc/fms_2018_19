<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();
$action_buttons = array();
$action_buttons[] = array(
    'type' => 'button',
    'label' => $CI->lang->line("ACTION_SAVE"),
    'id' => 'button_action_save',
    'data-form' => '#save_form'
);
$action_buttons[] = array(
    'type' => 'button',
    'label' => $CI->lang->line("ACTION_SAVE_NEW"),
    'id' => 'button_action_save_new',
    'data-form' => '#save_form'
);
$action_buttons[] = array(
    'type' => 'button',
    'label' => $CI->lang->line("ACTION_CLEAR"),
    'id' => 'button_action_clear',
    'data-form' => '#save_form'
);
$CI->load->view('action_buttons', array('action_buttons' => $action_buttons));
?>
<style>th label{margin-bottom:0}</style>
<form id="save_form" action="<?php echo site_url($CI->controller_url . '/index/save'); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $item_id; ?>">
    <input type="hidden" name="id_sub_category" value="<?php echo $id_sub_category; ?>">
    <input type="hidden" id="system_save_new_status" name="system_save_new_status" value="0">

    <div class="row show-grid">
        <table class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th><?php echo $CI->lang->line('LABEL_CLASS_NAME'); ?></th>
                <th><?php echo $CI->lang->line('LABEL_TYPE_NAME'); ?></th>
                <th>
                    <label><input type="checkbox" class="system-prevent-click" data-type="file" data-id=""> <?php echo $CI->lang->line('LABEL_FILE_NAME'); ?>
                    </label>
                </th>
                <?php
                for ($index = 0; $index < $CI->config->item('system_fms_max_actions'); $index++)
                {
                    ?>
                    <th>
                        <label><input class="system-prevent-click" type="checkbox" data-type="action" data-id="<?php echo $index; ?>"> <?php echo $CI->lang->line('LABEL_ACTION' . $index); ?>
                        </label>
                    </th>
                <?php
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($all_files as $class_id => $class)
            {
                $j = 0;
                foreach ($class['types'] as $type_id => $type)
                {
                    $i = 0;
                    foreach ($type['files'] as $file_id => $file)
                    {
                        ?>
                        <tr>
                            <td>
                                <?php
                                if ($j == 0)
                                {
                                    ?>
                                    <label><input class="system-prevent-click class" type="checkbox" data-type="class" data-id="<?php echo $class_id; ?>"> <?php echo $class['name']; ?>
                                    </label>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($i == 0)
                                {
                                    ?>
                                    <label><input class="system-prevent-click type class<?php echo $class_id; ?>" type="checkbox" data-type="type" data-id="<?php echo $type_id; ?>"> <?php echo $type['name']; ?>
                                    </label>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <label><input class="system-prevent-click file class<?php echo $class_id; ?> type<?php echo $type_id; ?>" type="checkbox" data-type="file" data-id="<?php echo $file_id; ?>"> <?php echo $file['name']; ?>
                                </label></td>
                            <?php
                            for ($index = 0; $index < $CI->config->item('system_fms_max_actions'); $index++)
                            {
                                ?>
                                <td>
                                    <input name="items[<?php echo $file_id; ?>][action<?php echo $index; ?>]" class="action<?php echo $index; ?> file class<?php echo $class_id; ?> type<?php echo $type_id; ?> file<?php echo $file_id; ?>" type="checkbox" <?php if (isset($permitted_files[$file_id]) && ($permitted_files[$file_id]['action' . $index] == 1))
                                    {
                                        echo 'checked';
                                    } ?> title="<?php echo $CI->lang->line('LABEL_ACTION' . $index); ?>">
                                </td>
                            <?php
                            }
                            ?>
                        </tr>
                        <?php
                        $i++;
                        $j++;
                    }
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</form>
