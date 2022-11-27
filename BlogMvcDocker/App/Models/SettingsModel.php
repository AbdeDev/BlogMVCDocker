<?php

namespace App\Models;

use System\Model;

class SettingsModel extends Model
{
     /**
     * NOm de la table
     *
     * @var string
     */
    protected $table = 'settings';

     /**
     *
     * @var array
     */
    private $settings = [];

     /**
     *
     * @return void
     */
    public function loadAll()
    {
        foreach ($this->all() AS $setting) {
            $this->settings[$setting->key] = $setting->value;
        }
    }

     /**
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return array_get($this->settings, $key);
    }

     /**
     *
     * @return void
     */
    public function updateSettings()
    {

        $keys = ['site_name', 'site_email', 'site_status', 'site_close_msg'];

        foreach ($keys AS $key) {

            $this->where('`key` = ?', $key)->delete($this->table);
            $this->data('key', $key)
                 ->data('value', $this->request->post($key))
                 ->insert($this->table);
        }

    }
}