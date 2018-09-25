<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI= & get_instance();
$action_data=array();
if(isset($CI->permissions['action4']) && ($CI->permissions['action4']==1))
{
    $action_buttons[]=array(
        'type'=>'button',
        'label'=>$CI->lang->line("ACTION_PRINT"),
        'class'=>'button_action_download',
        'data-title'=>"Print",
        'data-print'=>true
    );
}
if(isset($CI->permissions['action5']) && ($CI->permissions['action5']==1))
{
    $action_buttons[]=array(
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
        var url="<?php echo site_url($CI->controller_url.'/index/get_items'); ?>";
        var source =
        {
            dataType:"json",
            dataFields: [
                { name: 'id', type: 'int' },
                <?php
                foreach($system_preference_items as $key => $value){ ?>
                { name: '<?php echo $key; ?>', type: 'string' },
                <?php } ?>
            ],
            id: 'id',
            url: url,
            type: 'POST',
            data:JSON.parse('<?php echo json_encode($options);?>')
        };
        var cellsrenderer=function(row,column,value,defaultHtml,columnSettings,record)
        {
            var element=$(defaultHtml);
            element.css({'margin': '0px','width': '100%', 'height': '100%',padding:'5px'});
            if(column=='details_button')
            {
                element.html('<div><button class="btn btn-primary pop_up" data-item-no="'+row+'">Details</button></div>');
            }
            return element[0].outerHTML;
        };
        var tooltiprenderer = function (element) {
            $(element).jqxTooltip({position: 'mouse', content: $(element).text() });
        };

        var dataAdapter=new $.jqx.dataAdapter(source);
        $("#system_jqx_container").jqxGrid(
            {
                width: '100%',
                height:'350px',
                source: dataAdapter,
                sortable: true,
                filterable: true,
                showfilterrow: true,
                columnsresize: true,
                columnsreorder: true,
                altrows: true,
                enabletooltips: true,
                enablebrowserselection: true,
                rowsheight: 45,
                columns:[
                    { text: '<?php echo $CI->lang->line('LABEL_ID'); ?>',pinned:true,dataField: 'id',width:'50',cellsalign: 'right',rendered:tooltiprenderer,hidden: <?php echo $system_preference_items['id']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_FILE_NAME'); ?>',pinned:true,dataField: 'file_name',width:'220',rendered: tooltiprenderer,hidden: <?php echo $system_preference_items['file_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_RESPONSIBLE_EMPLOYEE'); ?>',dataField: 'responsible_employee',width:'180',rendered: tooltiprenderer,hidden: <?php echo $system_preference_items['responsible_employee']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_DATE_OPENING'); ?>',dataField: 'date_opening',width:'100',rendered: tooltiprenderer,hidden: <?php echo $system_preference_items['date_opening']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_FILE_STATUS'); ?>',dataField: 'file_status',width:'80',rendered: tooltiprenderer,hidden:true,filtertype:'list',hidden: <?php echo $system_preference_items['file_status']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_HC_LOCATION'); ?>',dataField: 'hc_location',width:130,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['hc_location']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_CATEGORY_NAME'); ?>',dataField: 'category_name',width:130,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['category_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_SUB_CATEGORY_NAME'); ?>',dataField: 'sub_category_name',width:130,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['sub_category_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_CLASS_NAME'); ?>',dataField: 'class_name',width:150,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['class_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_TYPE_NAME'); ?>',dataField: 'type_name',width:130,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['type_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_COMPANY_NAME'); ?>',dataField: 'company_name',width:200,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['company_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_DEPARTMENT_NAME'); ?>',dataField: 'department_name',width:170,rendered: tooltiprenderer,filtertype:'list',hidden: <?php echo $system_preference_items['department_name']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('LABEL_ORDERING'); ?>',dataField: 'ordering',width:60,cellsalign: 'right',rendered: tooltiprenderer,hidden: <?php echo $system_preference_items['ordering']?0:1;?>},
                    { text: '<?php echo $CI->lang->line('ACTION_DETAILS'); ?>', dataField: 'details_button',width:'85',cellsrenderer:cellsrenderer,hidden: <?php echo $system_preference_items['details_button']?0:1;?>}
                ]
            });
    });
</script>
