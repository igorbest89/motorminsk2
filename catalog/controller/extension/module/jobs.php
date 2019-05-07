<?php

class ControllerExtensionModuleJobs extends Controller
{
    public function index($setting)
    {
        $module_name = array_shift($setting);
        $module_description = array_shift($setting);
        $module_id = array_pop($setting);
        foreach($setting as $key => $item){
            foreach($item as $numb => $location_attribute){
                $setting[$numb][$key] =  html_entity_decode($location_attribute, ENT_QUOTES, 'UTF-8');
            }
            unset($setting[$key]);
        }

        $data['module_name'] = $module_name;
        $data['module_description'] = html_entity_decode($module_description, ENT_QUOTES, 'UTF-8');
        $data['jobs'] = $setting;
        $data['module_id'] = $module_id;
        if(isset($_SERVER["HTTP_REFERER"])){
            $data['back'] = $_SERVER["HTTP_REFERER"];
        }else{
            $data['back'] = $this->url->link('common/home');
        }
        $data['home'] = $this->url->link('common/home');
        $data['search'] = $this->load->controller('common/search');

        return $this->load->view('extension/module/jobs', $data);
    }
}