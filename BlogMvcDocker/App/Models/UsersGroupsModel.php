<?php

namespace App\Models;

use System\Model;

class UsersGroupsModel extends Model
{
     /**
     *
     * @var string
     */
    protected $table = 'users_groups';

    /**
    *
    * @return mixed
    */
    public function get($id)
    {
        $usersGroup = parent::get($id);

        if ($usersGroup) {
            $pages = $this->select('page')->where('users_group_id = ?' , $usersGroup->id)->fetchAll('users_group_permissions');

            $usersGroup->pages = [];

            if ($pages) {
                foreach ($pages AS $page) {
                    $usersGroup->pages[] = $page->page;
                }
            }
        }

        return $usersGroup;
    }

     /**
     *
     * @return void
     */
    public function create()
    {
        $usersGroupId = $this->data('name', $this->request->post('name'))
                             //->data('status', $this->request->post('status'))
                             ->insert($this->table)->lastId();

        $pages = array_filter($this->request->post('pages'));

        foreach ($pages AS $page) {
            $this->data('users_group_id', $usersGroupId)
                 ->data('page', $page)
                 ->insert('users_group_permissions');
        }

    }

     /**
     *
     * @param int $id
     * @return void
     */
    public function update($id)
    {
        $this->data('name', $this->request->post('name'))
             //->data('status', $this->request->post('status'))
             ->where('id=?', $id)
             ->update($this->table);



        $this->where('users_group_id = ?', $id)->delete('users_group_permissions');

        $pages = array_filter($this->request->post('pages'));

        foreach ($pages AS $page) {
            $this->data('users_group_id', $id)
                 ->data('page', $page)
                 ->insert('users_group_permissions');
        }

    }
}