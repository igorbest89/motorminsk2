<?php

class ControllerExtensionModuleIntentRequisites extends Controller
{
    public function index($setting)
    {
        $this->load->model('tool/image');
        $module_name = array_shift($setting);
        $module_id = array_pop($setting);
        foreach($setting as $key => $item){
            foreach($item as $numb => $location_attribute){
                $setting[$numb][$key] = $location_attribute;
            }
            unset($setting[$key]);
        }

        foreach ($setting as $key => $requisite){
            if (isset($requisite['image']) && is_file(DIR_IMAGE . $requisite['image'])) {
                $setting[$key]['thumb'] = $this->model_tool_image->resize($requisite['image'], 32, 32);
            } else {
                $setting[$key]['thumb'] = $this->model_tool_image->resize('no_image.png', 132, 32);
            }
        }
        $data['requisites'] = $setting;
        $data['module_name'] = $module_name;
        $data['unp'] = $this->config->get('config_unp');
        $data['okpo'] = $this->config->get('config_okpo');
        $temp = $this->config->get('config_address');
        $temp = explode(',', $temp);
        $data['republic']= array_pop($temp);
        $data['address']= implode(',', $temp);
        $data['store_name'] = $this->config->get('config_name');
        $data['module_id'] = $module_id;

        return $this->load->view('extension/module/intent_requisites', $data);
    }
}