<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MvcController extends CI_Controller
{

    private $cdn;

    public function __construct()
    {
        parent::__construct();
        // $this->load->config('front_end');
        $this->cdn = $this->config->item('im_fe_cdn');
    }

    protected function _view($page, $data = null, $return_flag = FALSE ){
        $class_name = strtolower(get_class($this));
        return $this->load->view("$class_name/$page", $data, $return_flag);
    }

    protected function _inc_view($page, $data = null)
    {
        return $this->load->view("inc/$page", $data, TRUE);
    }

    protected function _base_res($exteds_view = []){
        return array_merge([
            'script_tag' => $this->_cdn_js(),
            'style_tag' => $this->_cdn_css(),
            'inc_common' => $this->_inc_view('inc_common')
        ],
            $exteds_view
        );
    }

    protected function _cdn_js($prefixs = null)
    {
        $scripts = [];

        $js = $this->cdn['js'];
        if (isset($prefixs) && is_array($prefixs)) {
            foreach ($js as $prefix => $value) {
                if (in_array($prefix, $prefixs)) {
                    array_push($scripts, '<script src="' . $value . '" data-prefix="' . $prefix . '"></script>');
                }
            }
        } else {
            foreach ($js as $prefix => $value) {
                array_push($scripts, '<script src="' . $value . '" data-prefix="' . $prefix . '"></script>');
            }
        }
        return implode('', $scripts);
    }

    protected function _cdn_css($prefixs = null)
    {
        $styles = [];

        $js = $this->cdn['css'];
        if (isset($prefixs) && is_array($prefixs)) {
            foreach ($js as $prefix => $value) {
                if (in_array($prefix, $prefixs)) {
                    array_push($styles, '<link type="text/css" rel="stylesheet" href="' . $value . '" data-prefix="' . $prefix . '" media="screen,projection" />');
                }
            }
        } else {
            foreach ($js as $prefix => $value) {
                array_push($styles, '<link type="text/css" rel="stylesheet" href="' . $value . '" data-prefix="' . $prefix . '" media="screen,projection" />');
            }
        }
        return implode('', $styles);
    }

    protected function _no_cache()
    {
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Pragma: no-cache');
        return $this;
    }

    protected function _link($title, $class_list = array(), $href = '#')
    {
        return array_to_object(array(
            'title' => $title,
            'href' => $href,
            'class' => $class_list
        ));
    }

    protected function _js($path, $data)
    {
        $script = "/*[JS] not found view file($path)*/";

        if (is_im_exists_view($path)) {
            $script = str_replace(array('<script>', '</script>'), '', $this->_html($path, $data));
        }
        return $script;
    }

    protected function _css($path, $data)
    {
        $style = "/*[CSS] not found view file($path)*/";

        if (is_im_exists_view($path)) {
            $style = str_replace(array('<style>', '</style>'), '', $this->_html($path, $data));
        }
        return $style;
    }



    // $path 경로에 data 정보가 들어오면,
    // path 정보는 기본적으로 uri를 기준으로 설정됨
    // 예를 들어
    // imfoodspace.com/a/b/c url에 대해서 $this->_html(array());로 호출하는 경우
    // path는 a/b/c가 됨
    protected function _html($path, $data = null)
    {
        if (is_array($path)) {
            $data = $path;
            $path = uri_string();
        }
        if (is_im_exists_view($path)) {
            return $this->view($path, $data, TRUE);
        }

        return "<!-- not found view file($path) -->";
    }

    protected function _redirect($url, $param = array())
    {
        im_redirect($url, $param);
    }
}