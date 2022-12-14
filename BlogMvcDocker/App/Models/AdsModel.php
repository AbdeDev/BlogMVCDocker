<?php

namespace App\Models;

use System\Model;

class AdsModel extends Model
{
     /**
     * nom de la Table bdd
     *
     * @var string
     */
    protected $table = 'ads';

     /**
     *
     * @return array
     */
     public function enabled()
     {
         $currentRoute = $this->route->getCurrentRouteUrl();

         $now = time();

         return $this->where('status=? AND page=? AND start_at <= ? AND end_at >= ?', 'enabled', $currentRoute, $now, $now)->fetchAll($this->table);
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

        $this->data('name', $this->request->post('name'))
             ->data('link', $this->request->post('link'))
             ->data('start_at', strtotime($this->request->post('start_at')))
             ->data('end_at', strtotime($this->request->post('end_at')))
             ->data('status', $this->request->post('status'))
             ->data('page', $this->request->post('page'))
             ->data('created', time())
             ->insert('ads');
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

        $this->data('name', $this->request->post('name'))
             ->data('link', $this->request->post('link'))
             ->data('start_at', strtotime($this->request->post('start_at')))
             ->data('end_at', strtotime($this->request->post('end_at')))
             ->data('status', $this->request->post('status'))
             ->data('page', $this->request->post('page'))
             ->where('id=?' , $id)
             ->update('ads');
    }
}