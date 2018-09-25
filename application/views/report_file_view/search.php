<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI= & get_instance();
?>
<form id="save_form" action="<?php echo site_url($CI->controller_url.'/index/list');?>" method="post">
<div class="row widget">
<div class="widget-header">
    <div class="title">
        <?php echo $title; ?>
    </div>
    <div class="clearfix"></div>
</div>

<div class="row">
    <div class="col-xs-6">
        <div style="" class="row show-grid">
            <div class="col-xs-6">
                <label for="id_category" class="control-label pull-right">
                    <?php echo $CI->lang->line('LABEL_CATEGORY_NAME');?>
                </label>
            </div>
            <div class="col-xs-6">
                <select name="report[id_category]" id="id_category" class="form-control" tabindex="-1">
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
                    <?php
                    foreach($categories as $category)
                    {?>
                        <option value="<?php echo $category['value']?>"><?php echo $category['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div style="<?php if(!($item['id_sub_category']>0)){echo 'display:none';} ?>" class="row show-grid" id="sub_category_container">
            <div class="col-xs-6">
                <label for="id_sub_category" class="control-label pull-right">
                    <?php echo $CI->lang->line('LABEL_SUB_CATEGORY_NAME');?>
                </label>
            </div>
            <div class="col-xs-6">
                <select name="report[id_sub_category]" id="id_sub_category" class="form-control" tabindex="-1">
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
                    <?php
                    foreach($sub_categories as $sub_category)
                    {?>
                        <option value="<?php echo $sub_category['value']?>"><?php echo $sub_category['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div style="<?php if(!($item['id_class']>0)){echo 'display:none';} ?>" class="row show-grid" id="class_container">
            <div class="col-xs-6">
                <label for="id_class" class="control-label pull-right">
                    <?php echo $CI->lang->line('LABEL_CLASS_NAME');?>
                </label>
            </div>
            <div class="col-xs-6">
                <select name="report[id_class]" id="id_class" class="form-control" tabindex="-1">
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
                    <?php
                    foreach($classes as $class)
                    {?>
                        <option value="<?php echo $class['value']?>"><?php echo $class['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div style="<?php if(!($item['id_type']>0)){echo 'display:none';} ?>" class="row show-grid" id="type_container">
            <div class="col-xs-6">
                <label for="id_type" class="control-label pull-right">
                    <?php echo $CI->lang->line('LABEL_TYPE_NAME');?>
                </label>
            </div>
            <div class="col-xs-6">
                <select name="report[id_type]" id="id_type" class="form-control">
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
                    <?php
                    foreach($types as $type)
                    {?>
                        <option value="<?php echo $type['value']?>"><?php echo $type['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div style="<?php if(!($item['id_name']>0)){echo 'display:none';} ?>" class="row show-grid" id="name_container">
            <div class="col-xs-6">
                <label for="id_name" class="control-label pull-right">
                    <?php echo $CI->lang->line('LABEL_FILE_NAME');?>
                </label>
            </div>
            <div class="col-xs-6">
                <select name="report[id_name]" id="id_name" class="form-control">
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
                    <?php
                    foreach($names as $name)
                    {?>
                        <option value="<?php echo $name['value']?>"><?php echo $name['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="col-xs-6">
        <div style="" class="row show-grid">
            <div class="col-xs-6">
                <select name="report[id_company]" id="id_company" class="form-control" tabindex="-1">
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
                    <?php
                    foreach($companies as $company)
                    {?>
                        <option value="<?php echo $company['value']?>"><?php echo $company['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-xs-6">
                <label for="id_company" class="control-label pull-left">
                    <?php echo $CI->lang->line('LABEL_COMPANY_NAME'); ?>
                </label>
            </div>
        </div>

        <div style="" class="row show-grid">
            <div class="col-xs-6">
                <select name="report[id_department]" id="id_department" class="form-control" tabindex="-1">
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
                    <?php
                    foreach($departments as $department)
                    {?>
                        <option value="<?php echo $department['value']?>"><?php echo $department['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-xs-6">
                <label for="id_department" class="control-label pull-left">
                    <?php echo $CI->lang->line('LABEL_DEPARTMENT_NAME'); ?>
                </label>
            </div>
        </div>

        <div style="<?php if(!($item['employee_id']>0)){echo 'display: none';} ?>" class="row show-grid" id="employee_id_container">
            <div class="col-xs-6">
                <select name="report[employee_id]" id="employee_id" class="form-control" tabindex="-1">
                    <option value=""><?php echo $CI->lang->line('SELECT');?></option>
                    <?php
                    foreach($employees as $employee)
                    {?>
                        <option value="<?php echo $employee['value']?>"><?php echo $employee['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-xs-6">
                <label for="employee_id" class="control-label pull-left">
                    <?php echo $CI->lang->line('LABEL_RESPONSIBLE_EMPLOYEE'); ?>
            </div>
        </div>
    </div>
</div>

<div style="" class="row show-grid" id="file_opening_container">
    <div class="col-xs-3">
        <label class="control-label pull-right">
            File Opening Date
        </label>
    </div>
    <div class="col-xs-6">
        <div class="col-xs-6">
            <div class="input-group">
                        <span class="input-group-addon" id="sizing-addon2">
                            <label for="date_from_start_file">From:</label>
                        </span>
                <input name="report[date_from_start_file]" type="text" id="date_from_start_file" class="form-control datepicker" value="<?php echo $item['date_from_start_file'] ?>" placeholder="File Opening From Date">
            </div>
        </div>
        <div class="col-xs-6">
            <div class="input-group">
                        <span class="input-group-addon" id="sizing-addon2">
                            <label for="date_to_start_file">To:</label>
                        </span>
                <input name="report[date_to_start_file]" type="text" id="date_to_start_file" class="form-control datepicker" value="<?php echo $item['date_to_start_file'] ?>" placeholder="File Opening To Date">
            </div>
        </div>
    </div>
    <div class="col-xs-3"></div>
</div>

<div style="<?php if(!($item['id_name']>0)){echo 'display:none';} ?>" class="row show-grid" id="page_entry_container">
    <div class="col-xs-3">
        <label class="control-label pull-right">
            Page Entry Date
        </label>
    </div>
    <div class="col-xs-6">
        <div class="col-xs-6">
            <div class="input-group">
                        <span class="input-group-addon" id="sizing-addon2">
                            <label for="date_from_start_page">From:</label>
                        </span>
                <input name="report[date_from_start_page]" type="text" id="date_from_start_page" class="form-control datepicker" value="<?php echo $item['date_from_start_page'] ?>" placeholder="Page Entry From Date">
            </div>
        </div>
        <div class="col-xs-6">
            <div class="input-group">
                        <span class="input-group-addon" id="sizing-addon2">
                            <label for="date_to_start_page">To:</label>
                        </span>
                <input name="report[date_to_start_page]" type="text" id="date_to_start_page" class="form-control datepicker" value="<?php echo $item['date_to_start_page'] ?>" placeholder="Page Entry To Date">
            </div>
        </div>
    </div>
    <div class="col-xs-3"></div>
</div>

<div class="row show-grid">
    <div class="col-xs-7"></div>
    <div class="col-xs-5">
        <button class="btn btn-success" id="button_action_report" data-form="#save_form">
            View Report
        </button>
    </div>
</div>
</div>
<div class="clearfix"></div>
</form>
<div id="system_report_container">

</div>
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
        $("#id_name").val("");
        $('#sub_category_container').hide();
        $('#class_container').hide();
        $('#type_container').hide();
        $('#name_container').hide();
        $('#page_entry_container').hide();
        $('#file_opening_container').show();
        var id_category=$('#id_category').val();
        if(id_category>0)
        {
            if (system_sub_categories[id_category] !== undefined)
            {
                $('#sub_category_container').show();
                $('#id_sub_category').html(get_dropdown_with_select(system_sub_categories[id_category]));
            }
            else
            {
                $('#sub_category_container').hide();
            }
        }
    });
    $(document).on("change","#id_sub_category",function()
    {
        $("#id_class").val("");
        $("#id_type").val("");
        $("#id_name").val("");
        $('#class_container').hide();
        $('#type_container').hide();
        $('#name_container').hide();
        $('#page_entry_container').hide();
        $('#file_opening_container').show();
        var id_sub_category=$('#id_sub_category').val();
        if(id_sub_category>0)
        {
            if (system_class[id_sub_category] !== undefined)
            {
                $('#class_container').show();
                $('#id_class').html(get_dropdown_with_select(system_class[id_sub_category]));
            }
            else
            {
                $('#class_container').hide();
            }
        }
    });
    $(document).on("change","#id_class",function()
    {
        $("#id_type").val("");
        $("#id_name").val("");
        $('#type_container').hide();
        $('#name_container').hide();
        $('#page_entry_container').hide();
        $('#file_opening_container').show();
        var id_class=$('#id_class').val();
        if(id_class>0)
        {
            if (system_types[id_class] !== undefined)
            {
                $('#type_container').show();
                $('#id_type').html(get_dropdown_with_select(system_types[id_class]));
            }
            else
            {
                $('#type_container').hide();
            }
        }
    });
    $(document).on("change","#id_type",function()
    {
        $("#id_name").val("");
        $('#name_container').hide();
        $('#page_entry_container').hide();
        $('#file_opening_container').show();
        var id_type=$('#id_type').val();
        if(id_type>0)
        {
            $('#name_container').show();
            $.ajax({
                url: '<?php echo site_url('common_controller/get_names_by_type_id'); ?>',
                type: 'POST',
                datatype: "JSON",
                data:
                {
                    html_container_id:'#id_name',
                    id_type:id_type
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
    });
    $(document).on("change","#id_name",function()
    {
        $('#page_entry_container').hide();
        $('#file_opening_container').show();
        var id_name=$('#id_name').val();
        if(id_name>0)
        {
            $('#page_entry_container').show();
            $('#file_opening_container').hide();
        }
    });


    $(document).on("change","#id_company,#id_department",function()
    {
        $("#employee_id").val("");
        $('#employee_id_container').hide();
        var id_department=$('#id_department').val();
        var id_company=$('#id_company').val();
        if(id_company>0 || id_department>0)
        {
            $('#employee_id_container').show();
            $.ajax(
                {
                    url: "<?php echo site_url('common_controller/get_employees_by_company_department'); ?>",
                    type: 'POST',
                    datatype: "JSON",
                    data:
                    {
                        html_container_id:'#employee_id',
                        id_department:id_department,
                        id_company:id_company
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
    });

    $(document).on("click", ".pop_up", function(event)
    {
        var left=((($(window).width()-450)/2)+$(window).scrollLeft());
        var top=((($(window).height()-550)/2)+$(window).scrollTop());
        $("#popup_window").jqxWindow({width: 1200,height:550,position:{x:left,y:top}}); //to change position always
        var row=$(this).attr('data-item-no');
        var id=$("#system_jqx_container").jqxGrid('getrowdata',row).id;
        $.ajax(
            {
                url: "<?php echo site_url($CI->controller_url.'/index/details') ?>",
                type: 'POST',
                datatype: "JSON",
                data:
                {
                    html_container_id:'#popup_content',
                    id:id,
                    date_from_start_page: $('#date_from_start_page').val(),
                    date_to_start_page: $('#date_to_start_page').val()
                },
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");
                }
            });
        $("#popup_window").jqxWindow('open');
    });
});
</script>
