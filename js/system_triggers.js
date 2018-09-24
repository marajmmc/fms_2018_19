function system_preset(params={})
{
    system_resized_image_files=[];
    if(params.controller!==undefined)
    {
        // controller condition code
    }
}

function system_off_events()
{
    /*Common*/
    $(document).off('change','#warehouse_id');
    $(document).off('change','#warehouse_id_source');
    $(document).off('change','#warehouse_id_destination');
    $(document).off('change','#crop_id');
    $(document).off('change','#crop_type_id');
    $(document).off('change','#variety_id');
    $(document).off('change','#pack_size_id');
    $(document).off("change","#fiscal_year_id");

    $(document).off("change",".warehouse_id");
    $(document).off('change','.warehouse_id_source');
    $(document).off('change','.warehouse_id_destination');
    $(document).off("change",".crop_id");
    $(document).off("change",".crop_type_id");
    $(document).off("change",".variety_id");
    $(document).off("change",".pack_size_id");

    $(document).off("change","#items_container .crop_id");
    $(document).off("change","#items_container .crop_type_id");
    $(document).off('change','#items_container .variety_id');
    $(document).off('change','#items_container .pack_size_id');

    $(document).off('change', '#division_id');
    $(document).off('change', '#zone_id');
    $(document).off('change', '#territory_id');
    $(document).off('change', '#district_id');

    $(document).off('change', '.division_id');
    $(document).off('change', '.zone_id');
    $(document).off('change', '.territory_id');
    $(document).off('change', '.district_id');

    $(document).off('change', '#customer_id');

    $(document).off('change', '#outlet_id');
    $(document).off('change', '#outlet_id_source');
    $(document).off('change', '#outlet_id_destination');

    $(document).off("click", ".system_button_add_more");
    $(document).off('click','.system_button_add_delete');
    $(document).off("click", ".pop_up");

    $(document).off('change','#purpose');

    $(document).off("click", ".task_action_all");
    $(document).off("click", ".task_header_all");

    $(document).off('input','.amount');

    $(document).off('input', '#items_container .quantity_approve');
    $(document).off('input', '#items_container .quantity_request');

    /*FMS */
    $(document).off("change","#id_company");
    $(document).off("change","#id_department");
    $(document).off("change","#employee_id");
    $(document).off("change","#id_category");
    $(document).off("change","#id_sub_category");
    $(document).off("change","#id_class");
    $(document).off("change","#id_type");
    $(document).off("change","#id_hc_location");

    $(document).off("click",".system-prevent-click");
    $(document).off("click",".system_button_add_more");
    $(document).off("click",".system_button_add_delete");
    $(document).off("click",".fms_tasks_edit");

    $(document).off("click",".id_name");
    $(document).off("click",".button_action_report");
}