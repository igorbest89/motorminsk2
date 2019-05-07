<?php

class Pagination
{
    public $total = 0;
    public $page = 1;
    public $limit = 20;
    public $num_links = 8;
    public $url = '';
    public $text_first = '|&lt;';
    public $text_last = '&gt;|';
    public $text_next = '&gt;';
    public $text_prev = '&lt;';

    public function render($front = 0)
    {

        $total = $this->total;

        if ($this->page < 1) {
            $page = 1;
        } else {
            $page = $this->page;
        }

        if (!(int)$this->limit) {
            $limit = 10;
        } else {
            $limit = $this->limit;
        }


        $num_pages = ceil($total / $limit);

        $this->url = str_replace('%7Bpage%7D', '{page}', $this->url);

        $output = '<div class="pagination"><ul class="clearfix pagination_ul">';


        if($front == 0){
            $num_links = $this->num_links;
            if ($page > 1) {

                    $output .= '<li><a href="' . str_replace(array('&amp;page={page}', '&page={page}'), '', $this->url) . '">' . $this->text_first . '</a></li>';



                    if ($page - 1 === 1) {
                        $output .= '<li class="prev"><a href="' . str_replace(array('&amp;page={page}', '&page={page}'), '', $this->url) . '"><i class="icon icon-pagination-l"></i>
								<span>Пред</span></a></li>';
                    } else {
                        $output .= '<li class="prev"><a href="' . str_replace('{page}', $page - 1, $this->url) . '"><i class="icon icon-pagination-l"></i>
								<span>Пред</span></a></li>';
                    }

            }

            if ($num_pages > 1) {
                if ($num_pages <= $num_links) {
                    $start = 1;
                    $end = $num_pages;
                } else {
                    $start = $page - floor($num_links / 2);
                    $end = $page + floor($num_links / 2);

                    if ($start < 1) {
                        $end += abs($start) + 1;
                        $start = 1;
                    }

                    if ($end > $num_pages) {
                        $start -= ($end - $num_pages);
                        $end = $num_pages;
                    }
                }

                for ($i = $start; $i <= $end; $i++) {
                    if ($page == $i) {
                        $output .= '<li class="active"><a>' . $i . '</a></li>';
                    } else {
                        if ($i === 1) {
                            $output .= '<li><a href="' . str_replace(array('&amp;page={page}', '&page={page}'), '', $this->url) . '">' . $i . '</a></li>';
                        } else {
                            $output .= '<li><a href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a></li>';
                        }
                    }
                }
            }

            if ($page < $num_pages) {

                    $output .= '<li class="next"><a href="' . str_replace('{page}', $page + 1, $this->url) . '">
                            <span>След</span><i class="icon icon-pagination-r"></i></a></li>';
                    $output .= '<li><a href="' . str_replace('{page}', $num_pages, $this->url) . '">' . $this->text_last . '</a></li>';
            }

            $output .= '</ul>';

            if ($num_pages > 1) {
                return $output;
            } else {
                return '';
            }
        } else {
            $num_links = 4;
            if ($page > 1) {
                    $output .= '<li class="first"><a href="' . str_replace(array('&amp;page={page}', '&page={page}'), '', $this->url) . '"><span>Перв.</span></a></li>';
                    if ($page - 1 === 1) {
                        $output .= '<li class="prev"><a href="' . str_replace(array('&amp;page={page}', '&page={page}'), '', $this->url) . '"><i class="icon icon-pagination-l"></i>
								</a></li>';
                    } else {
                        $output .= '<li class="prev"><a href="' . str_replace('{page}', $page - 1, $this->url) . '"><i class="icon icon-pagination-l"></i>
								</a></li>';
                    }
            }

            if ($num_pages > 1) {
                if ($num_pages <= $num_links) {
                    $start = 1;
                    $end = $num_pages;
                } else {
                    $start = $page - floor($num_links / 2);
                    $end = $page + floor($num_links / 2);

                    if ($start < 1) {
                        $end += abs($start) + 1;
                        $start = 1;
                    }

                    if ($end > $num_pages) {
                        $start -= ($end - $num_pages);
                        $end = $num_pages;
                    }
                }

                for ($i = $start; $i <= $end; $i++) {
                    if ($page == $i) {
                        $output .= '<li class="active"><a>' . $i . '</a></li>';
                    } else {
                        if ($i === 1) {
                            $output .= '<li><a href="' . str_replace(array('&amp;page={page}', '&page={page}'), '', $this->url) . '">' . $i . '</a></li>';
                        } else {
                            $output .= '<li><a href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a></li>';
                        }
                    }
                }
            }

            if ($page < $num_pages) {
                    $output .= '<li class="next"><a href="' . str_replace('{page}', $page + 1, $this->url) . '">
                            <i class="icon icon-pagination-r"></i></a></li>';
                    $output .= '<li class="last"><a href="' . str_replace('{page}', $num_pages, $this->url) . '"><span>Посл.</span></a></li>';
            }

            $output .= '</ul>';

            if ($num_pages > 1) {
                return $output;
            } else {
                return '';
            }
        }

    }

}