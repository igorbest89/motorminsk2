<?php
class ModelNewsNews extends Model {

    public function addNews($data) {

        $this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_news SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW()");

        $news_id = $this->db->getLastId();

        foreach ($data['news_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', intro_text = '" . $this->db->escape($value['intro_text']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->cache->delete('news');

        return $news_id;
    }

    public function editNews($news_id, $data) {

        $this->db->query("UPDATE " . DB_PREFIX . "easy_blog_news SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE news_id = '" . (int)$news_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_news_description WHERE news_id = '" . (int)$news_id . "'");

        foreach ($data['news_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', intro_text = '" . $this->db->escape($value['intro_text']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->cache->delete('news');
    }

    public function copyArticle($news_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "easy_blog_news a LEFT JOIN " . DB_PREFIX . "easy_blog_news_description ad ON (a.news_id = ad.news_id) WHERE a.news_id = '" . (int)$news_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        if ($query->num_rows) {
            $data = array();

            $data = $query->row;
            $data['status'] = '0';
            $data = array_merge($data, array('news_description' => $this->getNewsDescriptions($news_id)));


            $this->addNews($data);
        }
    }

    public function deleteNews($news_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_news WHERE news_id = '" . (int)$news_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_news_description WHERE news_id = '" . (int)$news_id . "'");
        $this->cache->delete('news');
    }

    public function getNewsSingle($news_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "easy_blog_news a LEFT JOIN " . DB_PREFIX . "easy_blog_news_description ad ON (a.news_id = ad.news_id) WHERE a.news_id = '" . (int)$news_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getNews($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "easy_blog_news a LEFT JOIN " . DB_PREFIX . "easy_blog_news_description ad ON (a.news_id = ad.news_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND a.status = '" . (int)$data['filter_status'] . "'";
        }

        $sql .= " GROUP BY a.news_id";

        $sql .= " ORDER BY a.sort_order ASC, a.date_modified DESC";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getNewsDescriptions($news_id) {
        $article_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "easy_blog_news_description WHERE news_id = '" . (int)$news_id . "'");

        foreach ($query->rows as $result) {
            $article_description_data[$result['language_id']] = array(
                'name'             => $result['name'],
                'description'      => $result['description'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword'],
                'intro_text'       => $result['intro_text']
            );
        }

        return $article_description_data;
    }

    public function getTotalNews($data = array()) {
        $sql = "SELECT COUNT(DISTINCT a.news_id) AS total FROM " . DB_PREFIX . "easy_blog_news a LEFT JOIN " . DB_PREFIX . "easy_blog_news_description ad ON (a.news_id = ad.news_id)";

        $sql .= " WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND a.status = '" . (int)$data['filter_status'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

}