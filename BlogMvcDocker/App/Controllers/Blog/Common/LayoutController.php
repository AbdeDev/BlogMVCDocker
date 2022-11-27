<?php

namespace App\Controllers\Blog\Common;

use System\Controller;
use System\View\ViewInterface;

class LayoutController extends Controller
{
     /**
     *
     * @var array
     */
    private $disabledSections = [];

    /**
    *
    * @param \System\View\ViewInterface $view
    */
    public function render(ViewInterface $view)
    {
        $data['content'] = $view;

        $sections = ['header', 'sidebar', 'footer'];

        foreach ($sections AS $section) {
            $data[$section] = in_array($section, $this->disabledSections) ? '' : $this->load->controller('Blog/Common/' . ucfirst($section))->index();
        }

        return $this->view->render('blog/common/layout', $data);
    }

    /**
    *
    * @oaram string $section
    * @return $this
    */
    public function disable($section)
    {
        $this->disabledSections[] = $section;

        return $this;
    }

     /**
     *
     * @param string $title
     * @return void
     */
    public function title($title)
    {
        $this->html->setTitle($title . ' | ' . $this->settings->get('site_name'));
    }
}