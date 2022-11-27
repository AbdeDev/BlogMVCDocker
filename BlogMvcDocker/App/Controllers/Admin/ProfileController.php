<?php

namespace App\Controllers\Admin;

use System\Controller;

class ProfileController extends Controller
{
    /**
    *
    * @return string | json
    */
    public function update()
    {
        $json = [];


        $user = $this->load->model('Login')->user();

        if ($this->isValid($user->id)) {

            $this->load->model('Users')->update($user->id, $user->users_group_id);

            $json['success'] = 'User a été crée avec succès';

            $json['redirectTo'] = $this->request->referer() ?: $this->url->link('/admin');
        } else {

            $json['errors'] = $this->validator->flattenMessages();
        }

        return $this->json($json);
    }

     /**
     *
     * @param int $id
     * @return bool
     */
    private function isValid($id = null)
    {
        $this->validator->required('first_name', 'First Name is Required');
        $this->validator->required('last_name', 'Last Name is Required');
        $this->validator->required('email')->email('email');

        if ($this->request->post('password')) {
            $this->validator->required('password')->minLen('password', 8)->match('password', 'confirm_password', 'Confirm Password Should Match Password');
        }

        $this->validator->image('image');

        return $this->validator->passes();
    }
}