<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI=& get_instance();
$action_buttons=array();
if(isset($CI->permissions['action1']) && ($CI->permissions['action1']==1))
{
    $action_buttons[]=array
    (
        'label'=>$CI->lang->line("ACTION_NEW"),
        'href'=>site_url($CI->controller_url.'/index/add')
    );
}
if(isset($CI->permissions['action2']) && ($CI->permissions['action2']==1))
{
    $action_buttons[]=array
    (
        'type'=>'button',
        'label'=>$CI->lang->line("ACTION_EDIT"),
        'class'=>'button_jqx_action',
        'data-action-link'=>site_url($CI->controller_url.'/index/edit')
    );
}
if(isset($CI->permissions['action0']) && ($CI->permissions['action0']==1))
{
    $action_buttons[]=array
    (
        'type'=>'button',
        'label'=>$CI->lang->line("ACTION_DETAILS"),
        'class'=>'button_jqx_action',
        'data-action-link'=>site_url($CI->controller_url.'/index/details')
    );
}
if(isset($CI->permissions['action4']) && ($CI->permissions['action4']==1))
{
    $action_buttons[]=array
    (
        'type'=>'button',
        'label'=>$CI->lang->line("ACTION_PRINT"),
        'class'=>'button_action_download',
        'data-title'=>"Print",
        'data-print'=>true
    );
}
if(isset($CI->permissions['action5']) && ($CI->permissions['action5']==1))
{
    $action_buttons[]=array
    (
        'type'=>'button',
        'label'=>$CI->lang->line("ACTION_DOWNLOAD"),
        'class'=>'button_action_download',
        'data-title'=>"Download"
    );
}
if(isset($CI->permissions['action6']) && ($CI->permissions['action6']==1))
{
    $action_buttons[]=array
    (
        'label'=>'Preference',
        'href'=>site_url($CI->controller_url.'/index/set_preference')
    );
}
$action_buttons[]=array
(
    'label'=>$CI->lang->line("ACTION_REFRESH"),
    'href'=>site_url($CI->controller_url.'/index/list')

);
$action_buttons[]=array
(
    'type'=>'button',
    'label'=>$CI->lang->line("ACTION_LOAD_MORE"),
    'id'=>'button_jqx_load_more'
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
    <?php
    if(isset($CI->permissions['action6']) && ($CI->permissions['action6']==1))
    {
        $CI->load->view('preference',array('system_preference_items'=>$system_preference_items));
    }
    ?>
    <div class="col-xs-12" id="system_jqx_container">

    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
    $(document).ready(function()
    {
        system_preset({controller:'<?php echo $CI->router->class; ?>'});
        var url="<?php echo site_url($CI->controller_url.'/index/get_items');?>";
        var source =
        {
            dataType: "json",
            dataFields: [
                { name: 'id', type: 'int' },
                <?php
                foreach($system_preference_items as $key => $value){ ?>
                { name: '<?php echo $key; ?>', type: 'string' },
                <?php } ?>
            ],
            id: 'id',
            type: 'POST',
            url: url
        };
        var tooltiprenderer = function (element) {
            $(element).jqxTooltip({position: 'mouse', content: $(element).text() });
        };

        var dataAdapter = new $.jqx.dataAdapter(source);
        // create jqxgrid.
        $("#system_jqx_container").jqxGrid(
            {
                width: '100%',
                source: dataAdapter,
                pageable: true,
                filterable: true,
                sortable: true,
                showfilterrow: true,
                columnsresize: true,
                pagesize:50,
                pagesizeoptions: ['50', '100', '200','300','500','1000','3000','5000'],
                selectionmode: 'singlerow',
                altrows: true,
                height: '350px',
                enablebrowserselection:true,
                columnsreorder: true,
                columns:[
                    { text: '<?php echo $CI->lang->line('LABEL_ID'); ?>',pinned:true,dataField: 'id',width:'50',cellsalign: 'right',rendered:tooltiprenderer,hidden: <?php echo $system_preference_items['id']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_FILE_NAME'); ?>',pinned:true,dataField: 'file_name',width:'220',rendered: tooltiprenderer,hidden: <?php echo $system_preference_items['file_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_RESPONSIBLE_EMPLOYEE'); ?>',dataField: 'responsible_employee',width:'180',rendered: tooltiprenderer,hidden: <?php echo $system_preference_items['responsible_employee']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_DATE_OPENING'); ?>',dataField: 'date_opening',width:'100',rendered: tooltiprenderer,hidden: <?php echo $system_preference_items['date_opening']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_FILE_STATUS'); ?>',dataField: 'file_status',width:'80',rendered: tooltiprenderer,hidden:true,filtertype:'list',hidden: <?php echo $system_preference_items['file_status']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_NUMBER_OF_PAGE'); ?>',dataField: 'number_of_page',width:'50',cellsalign: 'right',rendered: tooltiprenderer,hidden: <?php echo $system_preference_items['number_of_page']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_HC_LOCATION'); ?>',dataField: 'hc_location',width:130,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['hc_location']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_CATEGORY_NAME'); ?>',dataField: 'category_name',width:130,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['category_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_SUB_CATEGORY_NAME'); ?>',dataField: 'sub_category_name',width:130,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['sub_category_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_CLASS_NAME'); ?>',dataField: 'class_name',width:150,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['class_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_TYPE_NAME'); ?>',dataField: 'type_name',width:130,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['type_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_COMPANY_NAME'); ?>',dataField: 'company_name',width:200,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['company_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_DEPARTMENT_NAME'); ?>',dataField: 'department_name',width:170,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['department_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_ORDERING'); ?>',dataField: 'ordering',width:60,cellsalign: 'right',rendered: tooltiprenderer,hidden: <?php echo $system_preference_items['ordering']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_STATUS');?>', dataField: 'status',filtertype: 'list', width: 100, hidden: <?php echo $system_preference_items['status']?0:1;?>}
                ]
            });
    });
</script>