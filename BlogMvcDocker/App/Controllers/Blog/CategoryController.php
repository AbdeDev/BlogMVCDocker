<?php

namespace App\Controllers\Blog;

use System\Controller;

class CategoryController extends Controller
{
     /**
     *
     * @param string name
     * @param int $id
     * @return mixed
     */
    public function index($title, $id)
    {
        $category = $this->load->model('Categories')->getCategoryWithPosts($id);

        if (! $category) {
            return $this->url->redirectTo('/404');
        }

        $this->html->setTitle($category->name);

        if ($category->posts) {
            $category->posts = array_chunk($category->posts, 2);
        } else {
            if ($this->pagination->page() != 1) {

                return $this->url->redirectTo("/category/$title/$id");
            }
        }

        $data['category'] = $category;

        $postController = $this->load->controller('Blog/Post');

        $data['post_box'] = function ($post) use ($postController) {
            return $postController->box($post);
        };
        $data['url'] = $this->url->link('/category/' . seo($category->name) . '/' . $category->id . '?page=');

        $data['pagination'] = $this->pagination->paginate();

        $view = $this->view->render('blog/category', $data);

        return $this->blogLayout->render($view);
    }
}