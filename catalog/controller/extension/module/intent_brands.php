<?php

class ControllerExtensionModuleIntentBrands extends Controller
{
    public function index($setting)
    {

        $this->load->model('catalog/category');
        $this->load->model('tool/image');


        foreach ($setting['category'] as $category) {
            if (!empty($category['image'])) {
                $thumb = $this->model_tool_image->resize($category['image'], 50, 50);
            } else {
                $thumb = $this->model_tool_image->resize('no_image.png', 50, 50);
            }
            $data['categories'][] = array(
                'name' => $category['name'],
                'href' => $category['href'],
                'thumb' => $thumb,
                'sub_categories' => $category['sub_categories']
            );

        }

        $data['catalog'] = $this->url->link('product/catalog', '', true);
        $data['all_manuf'] = $this->url->link('product/manufacturer', '', true);

        return $this->load->view('extension/module/intent_brands', $data);
    }
}