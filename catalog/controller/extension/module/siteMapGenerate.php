<?php

class ControllerExtensionModuleSiteMapGenerate extends Controller
{

    // Included files for main site map.
    private $includedFiles = [];

    // Set current site URL.
    private $siteUrl = HTTP_SERVER;

    /**
     * Start generate site maps for site (product && categories).
     *
     * @throws Exception
     */
    public function index()
    {
        $this->load->model('extension/module/siteMapGenerate');
        $this->includedFiles = [];
        $this->deleteSiteMap();
        $this->generateSiteMapCategories();
        $this->generateSiteMapProducts();
        $this->generateSiteMapMain();
    }

    /**
     * Delete all siteMaps in root directory.
     *
     * @return void
     */
    private function deleteSiteMap()
    {
        foreach (glob(DIR_MAIN . '*.xml') as $file) {
            if (strpos($file, 'sitemap')) {
                unlink($file);
            }
        }
    }

    /**
     * Generate site maps for categories.
     *
     * @return void
     */
    private function generateSiteMapCategories()
    {
        $max 		= $this->model_extension_module_siteMapGenerate->countCategories();
        $step  		= 50000;
        $count 		= 0;
        $fileNumber = 1;

        while ($count <= $max) {
            $output     = '<?xml version="1.0" encoding="UTF-8"?>';
            $output    .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            $categories	= $this->model_extension_module_siteMapGenerate->getCategories($step, $count);

            foreach ($categories as $category) {
                $outputCategory = $this->generateUrlCategory($category['category_id'], $category['parent_id']);
                $output .= '<url>';
                $output .= '<loc>' . $outputCategory . '</loc>';
                $output .= '<changefreq>weekly</changefreq>';
                $output .= '<priority>1.0</priority>';
                $output .= '</url>';
            }

            $output 	.= '</urlset>';
            $this->response->addHeader('Content-Type: application/xml');

            $siteMapName = (!file_exists(DIR_MAIN . 'sitemap-categories.xml')) ? 'sitemap-categories' : 'sitemap-categories-' . $fileNumber;
            $siteMapXmlUrl = DIR_MAIN . $siteMapName . '.xml';
            $openedFile = fopen($siteMapXmlUrl, 'w');
            chmod($siteMapXmlUrl, 0775);
            file_put_contents($siteMapXmlUrl, $output);
            fclose($openedFile);

            array_push($this->includedFiles, $siteMapXmlUrl);

            if (file_exists(DIR_MAIN . 'sitemap-categories-' . $fileNumber . '.xml')) $fileNumber++;
            $count = $count + $step;
        }
    }

    /**
     * Generate site map files for products.
     *
     * @return void
     */
    public function generateSiteMapProducts()
    {
        $this->load->model('extension/module/siteMapGenerate');
        $max   		= $this->model_extension_module_siteMapGenerate->countProducts();
        $step  		= 50000;
        $count 		= 0;
        $fileNumber = 0;

        while ($count <= $max) {
            $products = $this->model_extension_module_siteMapGenerate->getProducts($step, $count);

            $output     	= '<?xml version="1.0" encoding="UTF-8"?>';
            $output		   .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach ($products as $product) {
                $productAlias  	= $this->model_extension_module_siteMapGenerate->getProductAlias($product['product_id']);
                $categoryInfo  	= $this->model_extension_module_siteMapGenerate->getCategoryInfo($product['category_id']);
                if (isset($categoryInfo) && !empty($categoryInfo)) {
                    $categoryUrl   	= $this->generateUrlCategory($categoryInfo['category_id'], $categoryInfo['parent_id']);
                    $fullProductUrl = $categoryUrl . '/' . $productAlias;

                    $output		   .= '<url>';
                    $output 	   .= '<loc>' . $fullProductUrl . '</loc>';
                    $output 	   .= '<changefreq>weekly</changefreq>';
                    $output 	   .= '<priority>1.0</priority>';
                    $output 	   .= '</url>';
                }
            }
            $output 	   .= '</urlset>';
            $this->response->addHeader('Content-Type: application/xml');

            $siteMapName = (!file_exists(DIR_MAIN . 'sitemap-products.xml')) ? 'sitemap-products' : 'sitemap-products-' . $fileNumber;
            $siteMapXmlUrl = DIR_MAIN . $siteMapName . '.xml';
            $openedFile = fopen($siteMapXmlUrl, 'w');
            chmod($siteMapXmlUrl, 0775);
            file_put_contents($siteMapXmlUrl, $output);
            fclose($openedFile);

            array_push($this->includedFiles, $siteMapXmlUrl);
            $fileNumber++;
            $count = $count + $step;
        }
    }

    /**
     * Generate URL for category.
     *
     * @param $categoryId
     * @param $parentId
     * @param string $categoryUrl
     * @return string
     */
    private function generateUrlCategory($categoryId, $parentId, $categoryUrl = '')
    {
        $this->load->model('extension/module/siteMapGenerate');
        $urlAlias = $this->model_extension_module_siteMapGenerate->getCategoryAlias($categoryId);

        if ($parentId == 0) {
            $categoryUrl     =  $urlAlias . ($categoryUrl != '' ? '/' . $categoryUrl : '');
            $fullCategoryUrl = $this->siteUrl . $categoryUrl;

            return rtrim($fullCategoryUrl, '/');
        } else {
            $category = $this->db->query("
				SELECT category_id, parent_id
				FROM " . DB_PREFIX . "category
				WHERE category_id = " . $parentId . "
			")->row;

            return $this->generateUrlCategory($category['category_id'], $category['parent_id'], $urlAlias . '/' . $categoryUrl);
        }
    }

    /**
     * Generate main site map with all included files.
     *
     * @return void
     */
    private function generateSiteMapMain()
    {
        $this->load->model('extension/module/siteMapGenerate');
        $output     = '<?xml version="1.0" encoding="UTF-8"?>';
        $output    .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($this->includedFiles as $file) {
            $removeFromUrl = mb_substr($file, 0, mb_strrpos($file, '/'));;
            $clearFileUrl  = str_replace($removeFromUrl . '/', '', $file);
            $output .= '<sitemap>';
            $output .= '<loc>' . $this->siteUrl . $clearFileUrl . '</loc>';
            $output .= '</sitemap>';
        }

        $output 	.= '</sitemapindex>';
        $this->response->addHeader('Content-Type: application/xml');

        $siteMapXmlUrl = DIR_MAIN . 'sitemap_custom.xml';
        $openedFile = fopen($siteMapXmlUrl, 'w');
        chmod($siteMapXmlUrl, 0775);
        file_put_contents($siteMapXmlUrl, $output);
        fclose($openedFile);
    }

}