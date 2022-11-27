<?php

namespace App\Controllers\Admin;

use System\Controller;

class PostsController extends Controller
{
    /**
    *
    * @return mixed
    */
    public function index()
    {
        $this->html->setTitle('Posts');

        $data['posts'] = $this->load->model('Posts')->all();

        $data['success'] = $this->session->has('success') ? $this->session->pull('success') : null;

        $view = $this->view->render('admin/posts/list', $data);

        return $this->adminLayout->render($view);
    }

    /**
    *
    * @return string
    */
    public function add()
    {
        return $this->form();
    }

    /**
    *
    * @return string | json
    */
    public function submit()
    {
        $json = [];

        if ($this->isValid()) {

            $this->load->model('Posts')->create();

            $json['success'] = 'Post a été crée avec succès';

            $json['redirectTo'] = $this->url->link('/admin/posts');
        } else {

            $json['errors'] = $this->validator->flattenMessages();
        }

        return $this->json($json);
    }

     /**
     *
     * @param int $id
     * @return string
     */
    public function edit($id)
    {
        $postsModel = $this->load->model('Posts');

        if (! $postsModel->exists($id)) {
            return $this->url->redirectTo('/404');
        }

        $post = $postsModel->get($id);

        return $this->form($post);
    }

     /**
     *
     * @param \stdClass $post
     */
    private function form($post = null)
    {
        if ($post) {

            $data['target'] = 'edit-post-' . $post->id;

            $data['action'] = $this->url->link('/admin/posts/save/' . $post->id);

            $data['heading'] = 'Edit ' . $post->title;
        } else {

            $data['target'] = 'add-post-form';

            $data['action'] = $this->url->link('/admin/posts/submit');

            $data['heading'] = 'Add New Post';
        }

        $post = (array) $post;

        $data['title'] = array_get($post, 'title');
        $data['category_id'] = array_get($post, 'category_id');
        $data['status'] = array_get($post, 'status', 'enabled');
        $data['details'] = array_get($post, 'details');
        $data['tags'] = array_get($post, 'tags');
        $data['id'] = array_get($post, 'id');

        $data['image'] = '';
        $data['related_posts'] = [];

        if ($post['related_posts']) {
            $data['related_posts'] = explode(',', $post['related_posts']);
        }

        if (! empty($post['image'])) {

            $data['image'] = $this->url->link('public/images/' . $post['image']);
        }

        $data['categories'] = $this->load->model('Categories')->all();

        $data['posts'] = $this->load->model('Posts')->all();

        return $this->view->render('admin/posts/form', $data);
    }

    /**
    *
    * @return string | json
    */
    public function save($id)
    {
        $json = [];

        if ($this->isValid($id)) {

            $this->load->model('Posts')->update($id);

            $json['success'] = 'Posts a été crée avec succès';

            $json['redirectTo'] = $this->url->link('/admin/posts');
        } else {

            $json['errors'] = $this->validator->flattenMessages();
        }

        return $this->json($json);
    }

     /**
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        $postsModel = $this->load->model('Posts');

        if (! $postsModel->exists($id)) {
            return $this->url->redirectTo('/404');
        }

        $postsModel->delete($id);

        $json['success'] = 'Post a été crée avec succès';

        return $this->json($json);
    }

     /**
     *
     * @param int $id
     * @return bool
     */
    private function isValid($id = null)
    {
        $this->validator->required('title');
        $this->validator->required('details');
        $this->validator->required('tags');

        if (is_null($id)) {
            $this->validator->requiredFile('image')->image('image');
        } else {
            $this->validator->image('image');
        }

        return $this->validator->passes();
    }
}