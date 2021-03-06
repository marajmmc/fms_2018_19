<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();

$action_buttons = array();
$action_buttons[] = array(
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

<form id="save_form" action="<?php echo site_url($CI->controller_url . '/index/save_new_file'); ?>" method="post">
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
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_COMPANY_NAME'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <?php
                $user_companies = count($companies);
                if ($user_companies < 1)
                {
                    echo 'Not Assigned';
                }
                elseif ($user_companies == 1)
                {
                    echo $companies[0]['text']; ?>
                    <input type="hidden" name="item[id_company]" value="<?php echo $companies[0]['value']; ?>" />
                <?php
                }
                else
                {
                    ?>
                    <select id="id_company" name="item[id_company]" class="form-control">
                        <option value=""><?php echo $this->lang->line('SELECT'); ?></option>
                        <?php foreach ($companies as $company){ ?>
                            <option value="<?php echo $company['value'] ?>"><?php echo $company['text']; ?></option>
                        <?php } ?>
                    </select>
                <?php } ?>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_DEPARTMENT_NAME'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <?php echo $department['text']; ?>
                <input type="hidden" name="item[id_department]" value="<?php echo $department['value']; ?>" />
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_RESPONSIBLE_EMPLOYEE'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <?php echo $employee['text']; ?>
                <input type="hidden" name="item[employee_id]" value="<?php echo $employee['value']; ?>" />
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label for="id_category" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CATEGORY_NAME'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_category" class="form-control" tabindex="-1">
                    <option value=""><?php echo $CI->lang->line('SELECT'); ?></option>
                    <?php foreach ($categories as $category){ ?>
                        <option value="<?php echo $category['value'] ?>"><?php echo $category['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div style="display:none" class="row show-grid" id="sub_category_container">
            <div class="col-xs-4">
                <label for="id_sub_category" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_SUB_CATEGORY_NAME'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_sub_category" class="form-control">
                    <option value=""><?php echo $CI->lang->line('SELECT'); ?></option>
                    <?php foreach ($sub_categories as $sub_category){ ?>
                        <option value="<?php echo $sub_category['value'] ?>"><?php echo $sub_category['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div style="display:none" class="row show-grid" id="class_container">
            <div class="col-xs-4">
                <label for="id_class" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CLASS_NAME'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_class" class="form-control" tabindex="-1">
                    <option value=""><?php echo $CI->lang->line('SELECT'); ?></option>
                    <?php foreach ($classes as $class){ ?>
                        <option value="<?php echo $class['value'] ?>"><?php echo $class['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div style="display:none" class="row show-grid" id="type_container">
            <div class="col-xs-4">
                <label for="id_type" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_type" name="item[id_type]" class="form-control">
                    <option value=""><?php echo $CI->lang->line('SELECT'); ?></option>
                    <?php foreach ($types as $type){ ?>
                        <option value="<?php echo $type['value'] ?>"><?php echo $type['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label for="id_hc_location" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_HC_LOCATION'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="id_hc_location" name="item[id_hc_location]" class="form-control">
                    <option value=""><?php echo $CI->lang->line('SELECT'); ?></option>
                    <?php foreach ($hc_locations as $hc_location){ ?>
                        <option value="<?php echo $hc_location['value'] ?>"><?php echo $hc_location['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label for="name" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_FILE_NAME'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="item[name]" id="name" class="form-control" value="" />
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label for="status_file" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_FILE_STATUS'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="status_file" name="item[status_file]" class="form-control">
                    <option value="<?php echo $CI->config->item('system_status_file_open'); ?>"><?php echo $CI->lang->line('LABEL_STATUS_OPEN'); ?></option>
                    <option value="<?php echo $CI->config->item('system_status_file_close'); ?>"><?php echo $CI->lang->line('LABEL_STATUS_CLOSE'); ?></option>
                </select>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label for="date_start" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DATE_OPENING'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="item[date_start]" id="date_start" class="form-control datepicker" value="<?php echo $item['date_start']; ?>" readonly="readonly" />
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label for="remarks" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_REMARKS'); ?> </label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <textarea name="item[remarks]" id="remarks" class="form-control"></textarea>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right">Ordering <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="item[ordering]" id="ordering" class="form-control float_type_positive " value="<?php echo $item['ordering']; ?>" />
            </div>
        </div>
        
        <div class="row show-grid">
            <div class="col-xs-4">
                <label for="status" class="control-label pull-right"><?php echo $CI->lang->line('LABEL_STATUS'); ?> <span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="status" name="item[status]" class="form-control">
                    <option value="<?php echo $CI->config->item('system_status_active'); ?>" selected><?php echo $CI->lang->line('ACTIVE') ?></option>
                    <option value="<?php echo $CI->config->item('system_status_inactive'); ?>"><?php echo $CI->lang->line('INACTIVE') ?></option>
                </select>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
</form>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".datepicker").datepicker({dateFormat: display_date_format});

        $(document).on("change", "#id_company", function () {
            $("#id_department").val("");
            $("#employee_id").val("");
            var id_company = $('#id_company').val();
            if (id_company > 0) {
                $('#department_container').show();
                $('#employee_id_container').hide();
            }
            else {
                $('#department_container').hide();
                $('#employee_id_container').hide();
            }
        });
        $(document).on("change", "#id_department", function () {
            $("#employee_id").empty();
            var id_company = $('#id_company').val();
            var id_department = $('#id_department').val();
            if (id_department > 0) {
                $('#employee_id_container').show();
                $.ajax(
                    {
                        url: "<?php echo site_url('common_controller/get_employees_by_company_department'); ?>",
                        type: 'POST',
                        datatype: "JSON",
                        data: {
                            html_container_id: '#employee_id',
                            id_department: id_department,
                            id_company: id_company
                        },
                        success: function (data, status) {

                        },
                        error: function (xhr, desc, err) {
                            console.log("error");
                        }
                    });
            }
            else {
                $('#employee_id_container').hide();
            }
        });
        $(document).on("change", "#id_category", function () {
            $("#id_sub_category").html(get_dropdown_with_select(""));
            $("#id_class").html(get_dropdown_with_select(""));
            $("#id_type").html(get_dropdown_with_select(""));

            var id_category = $("#id_category").val();
            $("#sub_category_container").hide();
            $("#class_container").hide();
            $("#type_container").hide();
            if (id_category > 0) {
                $("#sub_category_container").show();
                $("#class_container").hide();
                $("#type_container").hide();
                if (system_sub_categories[id_category] !== undefined) {
                    $('#id_sub_category').html(get_dropdown_with_select(system_sub_categories[id_category]));
                }
            }
        });
        $(document).on("change", "#id_sub_category", function () {
            $('#id_class').html(get_dropdown_with_select(""));
            $("#id_type").html(get_dropdown_with_select(""));

            var id_sub_category = $('#id_sub_category').val();
            $('#class_container').hide();
            $('#type_container').hide();
            if (id_sub_category > 0) {
                $('#class_container').show();
                $('#type_container').hide();
                if (system_class[id_sub_category] !== undefined) {
                    $('#id_class').html(get_dropdown_with_select(system_class[id_sub_category]));
                }
            }
        });
        $(document).on("change", "#id_class", function () {
            $("#id_type").html(get_dropdown_with_select(""));

            var id_class = $('#id_class').val();
            $('#type_container').hide();
            if (id_class > 0) {
                $('#type_container').show();
                if (system_types[id_class] !== undefined) {
                    $('#id_type').html(get_dropdown_with_select(system_types[id_class]));
                }
            }
        });
    });
</script>