<?php
########################################################
#    AutoSearch 1.10 for Opencart 2.3.0.x by AlexDW    #
########################################################
class ControllerExtensionModuleAutosearch extends Controller
{
    public function orfFilter($keywords, $arrow = 0)
    {
        $str[0] = array('й' => 'q', 'ц' => 'w', 'у' => 'e', 'к' => 'r', 'е' => 't', 'н' => 'y', 'г' => 'u', 'ш' => 'i', 'щ' => 'o', 'з' => 'p', 'х' => '[', 'ъ' => ']', 'ф' => 'a', 'ы' => 's', 'в' => 'd', 'а' => 'f', 'п' => 'g', 'р' => 'h', 'о' => 'j', 'л' => 'k', 'д' => 'l', 'ж' => ';', 'э' => '\'', 'я' => 'z', 'ч' => 'x', 'с' => 'c', 'м' => 'v', 'и' => 'b', 'т' => 'n', 'ь' => 'm', 'б' => ',', 'ю' => '.', 'Й' => 'Q', 'Ц' => 'W', 'У' => 'E', 'К' => 'R', 'Е' => 'T', 'Н' => 'Y', 'Г' => 'U', 'Ш' => 'I', 'Щ' => 'O', 'З' => 'P', 'Х' => '[', 'Ъ' => ']', 'Ф' => 'A', 'Ы' => 'S', 'В' => 'D', 'А' => 'F', 'П' => 'G', 'Р' => 'H', 'О' => 'J', 'Л' => 'K', 'Д' => 'L', 'Ж' => ';', 'Э' => '\'', '?' => 'Z', 'ч' => 'X', 'С' => 'C', 'М' => 'V', 'И' => 'B', 'Т' => 'N', 'Ь' => 'M', 'Б' => ',', 'Ю' => '.');

        $str[1] = array('q' => 'й', 'w' => 'ц', 'e' => 'у', 'r' => 'к', 't' => 'е', 'y' => 'н', 'u' => 'г', 'i' => 'ш', 'o' => 'щ', 'p' => 'з', '[' => 'х', ']' => 'ъ', 'a' => 'ф', 's' => 'ы', 'd' => 'в', 'f' => 'а', 'g' => 'п', 'h' => 'р', 'j' => 'о', 'k' => 'л', 'l' => 'д', ';' => 'ж', '\'' => 'э', 'z' => 'я', 'x' => 'ч', 'c' => 'с', 'v' => 'м', 'b' => 'и', 'n' => 'т', 'm' => 'ь', ',' => 'б', '.' => 'ю', 'Q' => 'Й', 'W' => 'Ц', 'E' => 'У', 'R' => 'К', 'T' => 'Е', 'Y' => 'Н', 'U' => 'Г', 'I' => 'Ш', 'O' => 'Щ', 'P' => 'З', '[' => 'Х', ']' => 'Ъ', 'A' => 'Ф', 'S' => 'Ы', 'D' => 'В', 'F' => 'А', 'G' => 'П', 'H' => 'Р', 'J' => 'О', 'K' => 'Л', 'L' => 'Д', ';' => 'Ж', '\'' => 'Э', 'Z' => '?', 'X' => 'ч', 'C' => 'С', 'V' => 'М', 'B' => 'И', 'N' => 'Т', 'M' => 'Ь', ',' => 'Б', '.' => 'Ю');

        $str[2] = array("'" => "", "`" => "", "а" => "a", "А" => "a", "б" => "b", "Б" => "b", "в" => "v", "В" => "v", "г" => "g", "Г" => "g", "д" => "d", "Д" => "d", "е" => "e", "Е" => "e", "ж" => "zh", "Ж" => "zh", "з" => "z", "З" => "z", "и" => "i", "И" => "i", "й" => "y", "Й" => "y", "к" => "k", "К" => "k", "л" => "l", "Л" => "l", "м" => "m", "М" => "m", "н" => "n", "Н" => "n", "о" => "o", "О" => "o", "п" => "p", "П" => "p", "р" => "r", "Р" => "r", "с" => "s", "С" => "s", "т" => "t", "Т" => "t", "у" => "u", "У" => "u", "ф" => "f", "Ф" => "f", "х" => "h", "Х" => "h", "ц" => "c", "Ц" => "c", "ч" => "ch", "Ч" => "ch", "ш" => "sh", "Ш" => "sh", "щ" => "sch", "Щ" => "sch", "ъ" => "", "Ъ" => "", "ы" => "y", "Ы" => "y", "ь" => "", "Ь" => "", "э" => "e", "Э" => "e", "ю" => "yu", "Ю" => "yu", "я" => "ya", "Я" => "ya", "і" => "i", "І" => "i", "ї" => "yi", "Ї" => "yi", "є" => "e", "Є" => "e");

        $str[3] = array("a" => "а", "b" => "б", "v" => "в", "g" => "г", "d" => "д", "e" => "е", "yo" => "ё", "j" => "ж", "z" => "з", "i" => "и", "i" => "й", "k" => "к", "l" => "л", "m" => "м", "n" => "н", "o" => "о", "p" => "п", "r" => "р", "s" => "с", "t" => "т", "y" => "у", "f" => "ф", "h" => "х", "c" => "ц", "ch" => "ч", "sh" => "ш", "sh" => "щ", "i" => "ы", "e" => "е", "u" => "у", "ya" => "я", "A" => "А", "B" => "Б", "V" => "В", "G" => "Г", "D" => "Д", "E" => "Е", "Yo" => "Ё", "J" => "Ж", "Z" => "З", "I" => "И", "I" => "Й", "K" => "К", "L" => "Л", "M" => "М", "N" => "Н", "O" => "О", "P" => "П", "R" => "Р", "S" => "С", "T" => "Т", "Y" => "Ю", "F" => "Ф", "H" => "Х", "C" => "Ц", "Ch" => "Ч", "Sh" => "Ш", "Sh" => "Щ", "I" => "Ы", "E" => "Е", "U" => "У", "Ya" => "Я", "'" => "ь", "'" => "Ь", "''" => "ъ", "''" => "Ъ", "j" => "ї", "i" => "и", "g" => "ґ", "ye" => "є", "J" => "Ї", "I" => "І", "G" => "Ґ", "YE" => "Є");

        return strtr($keywords, isset($str[$arrow]) ? $str[$arrow] : array_search($str[0], $str[1]));
    }

    public function elasticSearch()
    {

        $keyword = mb_strtolower(($this->request->get['keyword']), 'UTF-8');
        $keyword =  $part_check = $this->orfFilter($keyword, 1);
        $keyword = mb_strtolower($keyword);


        $params = [
            'query' => [
                'match_phrase_prefix' => [
                    'title' => $keyword
                ]
            ],
        'size' => 200

        ];



        $data_string = json_encode($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, HTTP_SERVER_ELASTIC."_search");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($response, true);
        $valid_data = [];




//        print_r($res);


        if (!empty($res)) {
            foreach ($res['hits']['hits'] as $hit) {

                $valid_data[$hit['_id']]['tag'] = $hit['_source']['tag'];
                $valid_data[$hit['_id']]['name'] = $hit['_source']['title'];
                $valid_data[$hit['_id']]['href'] = $hit['_source']['href'];
                $valid_data[$hit['_id']]['id'] = $hit['_source']['id'];


            }
        } else {
            echo 'response is empty';
        }

        $this->cache->delete('search_result_' . $this->session->getId());

        $search_result = [];
        foreach ($valid_data as $item) {

            if ($item['tag'] == 'product') {
                $search_result[] = $item['id'];
            }
        }

        $this->cache->set('search_result_' . $this->session->getId(), $search_result);


        echo json_encode($valid_data);

    }


}

//php oc_cli.php catalog extension/module/import/
?>