<?php
class ControllerNewsNews extends Controller {
    public function index() {

        $this->load->model('news/news');
        $this->load->language('news/news');

        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
        } else {
            $filter = '';
        }

        if (isset($this->request->get['filter_year'])) {
            $filter_year = $this->request->get['filter_year'];
            $data['year'] = $this->request->get['filter_year'];
        } else {
            $filter_year = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = $this->config->get('easy_blog_global_article_limit');
        }

        $status = $this->config->get('easy_blog_global_status');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => "Главная",
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->config->get('easy_news_home_page_name'),
            'href' => $this->url->link('news/news')
        );



        if ($status) {
            $this->document->setTitle($this->config->get('easy_blog_home_page_meta_title'));
            $this->document->setDescription($this->config->get('easy_blog_home_page_meta_description'));
            $this->document->setKeywords($this->config->get('easy_blog_home_page_meta_keyword'));
            $this->document->addLink($this->url->link('news/news'),'');

            $data['heading_title'] = $this->config->get('easy_blog_home_page_meta_title');

            // Set the last category breadcrumb
            if (isset($this->request->get['filter_year'])) {
                $data['breadcrumbs'][] = array(
                    'text' => $this->config->get('easy_news_home_page_name') . " за " . $this->request->get['filter_year'] ,
                    'href' => ''
                );
            }

            $data['name'] = $this->config->get('easy_news_home_page_name');

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['articles'] = array();

            $filter_data = array(
                'filter_filter'      => $filter,
                'filter_year'        => $filter_year,
                'sort'               => $sort,
                'order'              => $order,
                'start'              => ($page - 1) * $limit,
                'limit'              => $limit
            );

            $article_total = $this->model_news_news->getTotalArticles($filter_data);

            $years = $results = $this->model_news_news->getArticlesYears();
            $data['years'] = [];

            foreach ($years as $year){
                $data['years'][] = [
                    'text' => $year,
                    'href' => $this->url->link('news/news&filter_year='.$year),
                ] ;
            }


            $results = $this->model_news_news->getArticles($filter_data);


            foreach ($results as $result) {

                $data['articles'][] = array(
                    'article_id'  => $result['news_id'],
                    'name'        => $result['name'],
                    'date_modified'  => date($this->language->get('date_format'), strtotime($result['date_modified'])),
                    'intro_text' => html_entity_decode($result['intro_text'], ENT_QUOTES, 'UTF-8'),
                    'href'        => $this->url->link('news/news_single', 'news_id=' . $result['news_id'] . $url)
                );
            }



            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['sorts'] = array();

            $data['sorts'][] = array(
                'text'  => $this->language->get('text_default'),
                'value' => 'p.sort_order-ASC',
                'href'  => $this->url->link('blog/blog', '&sort=p.sort_order&order=ASC' . $url)
            );

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $data['limits'] = array();

            $limits = array_unique(array($this->config->get('easy_blog_global_article_limit'), 50, 75, 100));

            sort($limits);

            foreach($limits as $value) {
                $data['limits'][] = array(
                    'text'  => $value,
                    'value' => $value,
                    'href'  => $this->url->link('news/news', $url . '&limit=' . $value)
                );
            }

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $pagination = new Pagination();
            $pagination->total = $article_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = $this->url->link('news/news', $url . '&page={page}');

            $data['pagination'] = $pagination->render(1);

            $data['button_read_more'] = $this->language->get('button_read_more');
            $data['text_empty'] = $this->language->get('text_empty');
            $data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($article_total - $limit)) ? $article_total : ((($page - 1) * $limit) + $limit), $article_total, ceil($article_total / $limit));

            $data['sort'] = $sort;
            $data['order'] = $order;
            $data['limit'] = $limit;




            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['search'] = $this->load->controller('common/search');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            if(isset($_SERVER["HTTP_REFERER"])){
                $data['back'] = $_SERVER["HTTP_REFERER"];
            }else{
                $data['back'] = $this->url->link('common/home');
            }

            $this->response->setOutput($this->load->view('news/news', $data));

        } else {

            $url = '';

            if (isset($this->request->get['filter'])) {
                $url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('news/news', $url)
            );

            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header', array('headerss'=>'false'));
            $data['search'] = $this->load->controller('common/search');


            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }

}