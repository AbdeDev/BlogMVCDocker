<?php

namespace App\Models;

use System\Model;

class PostsModel extends Model
{
     /**
     * Nom de la table bdd
     *
     * @var string
     */
    protected $table = 'posts';

    /**
    * avoir tous les posts
    *
    * @return array
    */
    public function all()
    {
        return $this->select('p.*', 'c.name AS `category`', 'u.first_name', 'u.last_name')
                    ->from('posts p')
                    ->join('LEFT JOIN categories c ON p.category_id=c.id')
                    ->join('LEFT JOIN users u ON p.user_id=u.id')
                    ->fetchAll();
    }

     /**
     * avoir les posts + comments
     *
     * @param int $id
     * @return mixed
     */
    public function getPostWithComments($id)
    {
        $post = $this->select('p.*', 'c.name AS `category`', 'u.first_name', 'u.last_name', 'u.image AS userImage')
                     ->from('posts p')
                     ->join('LEFT JOIN categories c ON p.category_id=c.id')
                     ->join('LEFT JOIN users u ON p.user_id=u.id')
                     ->where('p.id=? AND p.status=?', $id, 'enabled')
                     ->fetch();

        if (! $post) return null;

        $post->comments = $this->select('c.*', 'u.first_name', 'u.last_name', 'u.image AS userImage')
                               ->from('comments c')
                               ->join('LEFT JOIN users u ON c.user_id=u.id')
                               ->where('c.post_id=?', $id)
                               ->fetchAll();

        return $post;
    }

     /**
     * Avoir les derniers posts
     *
     * @return array
     */
    public function latest()
    {
        // retourner le dernier post mis
        return $this->select('p.*', 'c.name AS `category`', 'u.first_name', 'u.last_name')
                    ->select('(SELECT COUNT(co.id) FROM comments co WHERE co.post_id=p.id) AS total_comments')
                    ->from('posts p')
                    ->join('LEFT JOIN categories c ON p.category_id=c.id')
                    ->join('LEFT JOIN users u ON p.user_id=u.id')
                    ->where('p.status=?', 'enabled')
                    ->orderBy('p.id', 'DESC')
                    ->fetchAll();
    }

     /**
     *
     * @return void
     */
    public function create()
    {
        $image = $this->uploadImage();

        if ($image) {
            $this->data('image', $image);
        }

        $user = $this->load->model('Login')->user();

        $this->data('title', $this->request->post('title'))
             ->data('details', $this->request->post('details'))
             ->data('category_id', $this->request->post('category_id'))
             ->data('user_id', $user->id)
             ->data('tags', $this->request->post('tags'))
             ->data('related_posts', implode(',', array_filter((array) $this->request->post('related_posts') , 'is_numeric')))
             ->data('status', $this->request->post('status'))
             ->data('created', $now = time())
             ->insert('posts');
    }

     /**
     *
     * @return string
     */
     private function uploadImage()
     {
         $image = $this->request->file('image');

         if (! $image->exists()) {
             return '';
         }

         return $image->moveTo($this->app->file->toPublic('images'));
     }

     /**
     *
     * @param int $id
     * @return void
     */
    public function update($id)
    {
        $image = $this->uploadImage();

        if ($image) {
            $this->data('image', $image);
        }

        $this->data('title', $this->request->post('title'))
             ->data('details', $this->request->post('details'))
             ->data('category_id', $this->request->post('category_id'))
             ->data('tags', $this->request->post('tags'))
             ->data('status', $this->request->post('status'))
             ->data('related_posts', implode(',', array_filter((array) $this->request->post('related_posts') , 'is_numeric')))
             ->where('id=?' , $id)
             ->update('posts');
    }

     /**
     *
     * @param int $postId
     * @param string $comment
     * @param int $userId
     * @return void
     */
    public function addNewComment($id, $comment, $userId)
    {
        $this->data('post_id', $id)
             ->data('comment', $comment)
             ->data('status', 'enabled')
             ->data('created', time())
             ->data('user_id', $userId)
             ->insert('comments');
    }
}