<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI = & get_instance();

$system_categories=Query_helper::get_info($this->config->item('table_fms_setup_file_category'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
$results=Query_helper::get_info($this->config->item('table_fms_setup_file_sub_category'),array('id value','name text, id_category'),array('status="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
$system_sub_categories=array();
foreach($results as $result)
{
    $system_sub_categories[$result['id_category']][]=$result;
}
$results=Query_helper::get_info($this->config->item('table_fms_setup_file_class'),array('id value','name text, id_sub_category'),array('status="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
$system_class=array();
foreach($results as $result)
{
    $system_class[$result['id_sub_category']][]=$result;
}
$results=Query_helper::get_info($this->config->item('table_fms_setup_file_type'),array('id value','name text, id_class'),array('status="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
$system_types=array();
foreach($results as $result)
{
    $system_types[$result['id_class']][]=$result;
}

$menu_odd_color='#fee3b4';
$result=Query_helper::get_info($this->config->item('table_login_setup_system_configures'),array('config_value'),array('purpose ="' .$CI->config->item('system_purpose_fms_menu_odd_color').'"','status ="'.$CI->config->item('system_status_active').'"'),1);
if($result)
{
    $menu_odd_color=$result['config_value'];
}
$menu_even_color='#e0dff6';
$result=Query_helper::get_info($this->config->item('table_login_setup_system_configures'),array('config_value'),array('purpose ="' .$CI->config->item('system_purpose_fms_menu_even_color').'"','status ="'.$CI->config->item('system_status_active').'"'),1);
if($result)
{
    $menu_even_color=$result['config_value'];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>FMS 2018_19.2</title>
        <link rel="icon" type="image/ico" href="http://malikseeds.com/favicon.ico"/>
        <meta charset="utf-8">

        <link rel="stylesheet" href="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('css/bootstrap.min.css'));?>">
        <link rel="stylesheet" href="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('css/style.css?version='.time()));?>">
        <link rel="stylesheet" href="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('css/jquery-ui/jquery-ui.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('css/jquery-ui/jquery-ui.theme.css')); ?>">

        <link rel="stylesheet" href="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('css/jqx/jqx.base.css')); ?>">
        <link rel="stylesheet" href="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('css/print.css')); ?>">
        <style>
            .navbar-nav > li {
                background-color: <?php echo $menu_odd_color ?>;
            }
            .navbar-nav > li:nth-child(even){
                background-color: <?php echo $menu_even_color ?>;
            }
        </style>
    </head>
    <body>
        <script src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jquery-2.1.1.js')); ?>"></script>
        <script src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/bootstrap.min.js')); ?>"></script>
        <script src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/bootstrap-filestyle.min.js')); ?>"></script>
        <script src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jquery-ui.min.js')); ?>"></script>

        <!--    for jqx grid finish-->
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxcore.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxgrid.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxscrollbar.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxgrid.edit.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxgrid.sort.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxgrid.pager.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxbuttons.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxcheckbox.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxlistbox.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxdropdownlist.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxmenu.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxgrid.filter.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxgrid.selection.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxgrid.columnsresize.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxdata.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxdatatable.js')); ?>"></script>
        <!--    only for color picker-->
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxcolorpicker.js')); ?>"></script>
        <!--    For column reorder-->
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxgrid.columnsreorder.js')); ?>"></script>
        <!--    For print-->
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxdata.export.js')); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxgrid.export.js')); ?>"></script>
        <!--        for footer sum-->
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxgrid.aggregates.js')); ?>"></script>
        <!-- for header tool tip-->
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxtooltip.js')); ?>"></script>
        <!-- popup-->
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/jqx/jqxwindow.js')); ?>"></script>

        <!--    for jqx grid end-->

        <script type="text/javascript">
            var base_url = "<?php echo base_url(); ?>";
            var display_date_format = "dd-M-yy";
            var SELECT_ONE_ITEM = "<?php echo $CI->lang->line('SELECT_ONE_ITEM'); ?>";
            var DELETE_CONFIRM = "<?php echo $CI->lang->line('DELETE_CONFIRM'); ?>";
            var SYSTEM_IMAGE_SIZE_TO_RESIZE=1372022;//1372022=1.3mb,409600=400KB
            var SYSTEM_IMAGE_MAX_WIDTH=800;
            var SYSTEM_IMAGE_MAX_HEIGHT=600;
            var resized_image_files=[];
            var system_categories=JSON.parse('<?php echo json_encode($system_categories);?>');
            var system_sub_categories=JSON.parse('<?php echo json_encode($system_sub_categories);?>');
            var system_class=JSON.parse('<?php echo json_encode($system_class);?>');
            var system_types=JSON.parse('<?php echo json_encode($system_types);?>');
            var system_report_color_grand='#AEC2DD';
            var system_report_color_crop='#0CA2C5';
            var system_report_color_type='#6CAB44';
        </script>
        <header class="hidden-print">

            <img alt="Logo" height="40" class="site_logo pull-left" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('images/logo.png'));?>">
            <div class="site_title pull-left">A. R. MALIK SEEDS (PVT) LTD.</div>

        </header>

        <div class="container-fluid" style="margin-bottom: 40px;">
            <div id="system_menus">
                <?php
                $CI->load->view('menu');
                ?>
            </div>

            <div class="row dashboard-wrapper">
                <div class="col-sm-12" id="system_content">

                </div>
            </div>
        </div>
        <div id="system_loading"><img src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('images/spinner.gif'));?>"></div>
        <div id="system_message" class="hidden-print"></div>
        <div id="popup_window">
            <div id="popup_window_title">Details</div>
            <div id="popup_content" style="overflow: auto;">
            </div>
        </div>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/system_common.js?version='.time())); ?>"></script>
        <script type="text/javascript" src="<?php echo str_replace('fms_2018_19','login_2018_19',base_url('js/system_triggers.js?version='.time())); ?>"></script>
    </body>
</html>
