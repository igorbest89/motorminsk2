<?php
class ControllerExtensionModuleImport extends Controller
{
    public function importCategories()
    {
        $this->load->model('catalog/category');

        $lines = file(DIR_UPLOAD . 'rubrics.csv');
        foreach ($lines as $key => $line) {
            $data = str_getcsv($line, "\n");
            foreach ($data as &$row) {
                $row = str_getcsv($row, ";");
            }
            $lines[$key] = $data;
        }
        $count = 0;
        $data_column = $lines[0];
        $data_column = $data_column[0];

        unset($lines[0]);



        foreach ($lines as $dat) {
            $dat = $dat[0];

            if(trim($dat[4]) != ''){
                $array_of_properties = explode('^',$dat[4]);
                $properties = [];
                foreach ($array_of_properties as $key => $value){
                    $properties[$key]['name'] = trim(explode(':', $value)[0]);
                    $properties[$key]['text'] = trim(explode(':', $value)[1]);
                }
                $html = '';
                $html .= '<ul>';
                foreach ($properties as $property){
                    $html .= '<li>' . $property['name'] . ': <span style="font-weight: bold;">' . $property['text'] . '</span>' . '</li>';
                }
                $html .= '</ul>';
            }else{
                $html = '';
            }

                 $str = str_replace("<img src=\"/i/photo/", "<img src=\"/image/catalog/categories/", "$dat[12]");
                 $str = str_replace("¶", "", $str);

                $description[1] = [
                    'name' => $dat[2],
                    'meta_title' => $dat[8],
                    'meta_keyword' => $dat[10],
                    'meta_description' => $dat[11],
                    'description' => $str,
                    'properties' => $html,
                ];

                if ($dat[3] != '') {
                    $image = 'catalog/category/' . $dat[3];
                } else {
                    $image = '';
                }
                if ($dat[1] != '') {
                    $parent = $dat[1];
                    $top = 0;
                } else {
                    $parent = 0;
                    $top = 1;
                }
                $category_store = [];
                $category_store[] = 0;
                $category_layout = [];
                $category_layout[0] = 0;


                $category = [
                    'category_id' => $dat[0],
                    'parent_id' => $parent,
                    'top' => $top,
                    'image' => $image,
                    'sort_order' => 0,
                    'status' => 1,
                    'column' => 1,
                    'category_layout' => $category_layout,
                    'category_store' => $category_store,
                    'category_description' => $description,
                    'keyword' => $this->str2url($dat[2]),
                ];

                $category_check = $this->model_catalog_category->getCategory($dat[0]);

                if(!$category_check){
                    $this->model_catalog_category->addCategory($category);
                }else{
                    $this->model_catalog_category->editCategory($category_check['category_id'], $category);
                }

                $count++;

        }

        echo "Add :" . $count . " category \n";
    }


    public function importProducts()
    {

        $this->load->model('catalog/product');


        $lines = file(DIR_UPLOAD . 'products.csv');
        foreach ($lines as $key => $line) {
            $data = str_getcsv($line, "\n");
            foreach ($data as &$row) {
                $row = str_getcsv($row, ";");
            }
            $lines[$key] = $data;
        }

        $count = 0;
        $data_column = $lines[0];
        $data_column = $data_column[0];




        unset($lines[0]);




        foreach ($lines as $key => $line) {



                $line = $line[0];

                $description[1] = [
                    'name' => $line[2],
                    'description' => $line[10],
                    'tag' => '',
                    'meta_title' => $line[2],
                    'meta_description' => '',
                    'meta_keyword' => '',

                ];

                $product_attributes = [];

                $exec_array = [7, 10, 24, 26, 29, 30];
                for($z = 0, $x = 5; $x < 106; $x++,  $z++){

                    if(in_array($x, $exec_array)){
                        continue;
                    }

                    if(!isset($line[$x])){
                        $line[$x] = '';
                    }

                    if($line[$x] != ''){
                        $attribute = $this->model_catalog_product->findAttribute(trim($data_column[$x]));
                        if($attribute){
                            $attribute_id = $attribute['attribute_id'];
                        }else{
                            $attribute_id = $this->model_catalog_product->addAttribute($data_column[$x], 1);
                        }

                        $product_attributes[] = [
                            'attribute_id' => $attribute_id,
                            'product_attribute_description' => [1 => ['text' => $line[$x]]],
                        ];
                    }
                }

                if ($line[4] != '') {
                    $image = 'catalog/products/' . $line[4];
                } else {
                    $image = '';
                }
                $this->load->model('catalog/manufacturer');
                $product_store = [];
                $product_store[] = 0;
                $product_category = explode(',', $line[1]);

                if ($line[26] != '') {
                    $manufacture = $this->model_catalog_product->getManufacturer($line[26]);
                    if ($manufacture) {
                        $manufacture_id = $manufacture['manufacturer_id'];
                    } else {
                        $data_manufacturer = [
                            'name' => $line[26],
                            'sort_order' => 0,
                            'keyword' => $this->str2url($line[26]),
                        ];

                        $manufacture_id = $this->model_catalog_manufacturer->addManufacturer($data_manufacturer);

                    }
                } else {
                    $manufacture_id = 0;
                }





            ini_set("soap.wsdl_cache_enabled", "0");

            $client = new SoapClient("https://1cws.bereg.by/1C/ws/GetTheAmountOfGoods?wsdl" , array('login' => "Test", 'password' => "f,hfrflf,hf", 'exceptions' => 1, 'cache_wsdl' => 0));

            $sku = $line[0];
            $a = $client->GetTheAmount(array('ID' => 'ИдентификаторПользователяНаСайте', 'Goods' => $sku . ',100000'));

            $quantity = explode(',', iconv("UTF-8", "WINDOWS-1251", $a->return));


            $product = [
                    'model' => '',
                    'sku' =>  $line[0],
                    'upc' => '',
                    'ean' => '',
                    'jan' => '',
                    'isbn' => '',
                    'mpn' => '',
                    'location' => '',
                    'quantity' => $quantity[1],
                    'minimum' => 1,
                    'subtract' => 0,
                    'stock_status_id' => 7,
                    'date_available' => date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))),
                    'manufacturer_id' => $manufacture_id,
                    'shipping' => 1,
                    'price' => str_replace(',','.', $line[3]),
                    'points' => 0,
                    'weight' => $line[7],
                    'weight_class_id' => 1,
                    'length' => $line[29],
                    'width' => $line[30],
                    'height' => $line[24],
                    'length_class_id' => 2,
                    'status' => 1,
                    'tax_class_id' => 0,
                    'sort_order' => 0,
                    'image' => $image,
                    'product_attribute' => $product_attributes,
                    'product_description' => $description,
                    'keyword' => $this->str2url($line[2]) . '-' . $line[0],
                    'product_store' => $product_store,
                    'product_category' => $product_category,
                ];

                $product_check = $this->model_catalog_product->getProductBySku($line[0]);

                if(!$product_check){
                    $this->model_catalog_product->addProduct($product);
                }else{
                    $this->model_catalog_product->editProduct($product_check['product_id'], $product);
                }

                $count++;


        }

        echo "Add :" . $count . " products \n";

    }

    public function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',    'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }
    public function str2url($str) {
        // переводим в транслит
        $str = $this->rus2translit($str);
        // в нижний регистр
        $str = strtolower($str);
        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        // удаляем начальные и конечные '-'
        $str = trim($str, "-");
        return $str;
    }

//    public function addAliasForManufacturers(){
//        $this->load->model('catalog/manufacturer');
//        $data = [];
//        $manufacturers = $this->model_catalog_manufacturer->getManufacturers($data);
//        foreach ($manufacturers as $manufacturer){
//            $data = [
//                'manufacturer_id'=> $manufacturer['manufacturer_id'],
//                'keyword'=> $this->str2url($manufacturer['name']),
//            ];
//            $this->model_catalog_manufacturer->addAliasForManufacturer($data);
//        }
//
//
//    }
}
