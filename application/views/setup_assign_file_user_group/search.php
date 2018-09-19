<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$action_buttons=array();
$action_buttons[]=array(
    'label'=>$CI->lang->line("ACTION_BACK"),
    'href'=>site_url($CI->controller_url)
);
$CI->load->view('action_buttons',array('action_buttons'=>$action_buttons));
?>
<div class="row widget">
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div style="" class="row show-grid">
        <div class="col-xs-4">
            <label for="id_category" class="control-label pull-right">
                <?php echo $CI->lang->line('LABEL_CATEGORY_NAME');?>
                <span style="color:#FF0000">*</span>
            </label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="id_category" class="form-control" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($categories as $category)
                {
                    ?>
                    <option value="<?php echo $category['value']; ?>"><?php echo $category['text']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>

    <div style="display: none;" class="row show-grid" id="sub_category_container">
        <div class="col-xs-4">
            <label for="id_sub_category" class="control-label pull-right">
                <?php echo $CI->lang->line('LABEL_SUB_CATEGORY_NAME');?>
                <span style="color:#FF0000">*</span>
            </label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="id_sub_category" class="form-control" data-id-user-group="<?php echo $item_id; ?>" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        </div>
    </div>
    <br/>
    <div id="edit_form"></div>
</div>

<script>
    jQuery(document).ready(function()
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
        $(document).on("change","#id_sub_category",function()
        {
            var id_sub_category=$('#id_sub_category').val();
            if(id_sub_category>0)
            {
                $.ajax(
                    {
                        url: '<?php echo site_url($CI->controller_url.'/index/edit'); ?>',
                        type: 'POST',
                        datatype: "JSON",
                        data:
                        {
                            id_sub_category:id_sub_category,
                            id_user_group:$(this).attr("data-id-user-group")
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
                $('#edit_form').empty();
            }
        });
        $(document).on('click','.system-prevent-click',function()
        {
            var obj=$(this);
            if(obj.is(':checked'))
            {
                var attr_data_type=obj.attr('data-type');
                $('.'+attr_data_type+obj.attr('data-id')).prop('checked',true);
                if(attr_data_type!='action')
                {
                    $('.'+attr_data_type+obj.attr('data-id')+'.action3').prop('checked',false);
                    $('.'+attr_data_type+obj.attr('data-id')+'.action2').prop('checked',false);
                }
            }
            else
            {
                $('.'+obj.attr('data-type')+obj.attr('data-id')).prop('checked',false);
            }
        });
    });
</script>
