<?php

namespace App\Models;

use CodeIgniter\Model;

class WebConfigModel extends Model
{
    protected $table            = 'tbl_web_config';
    protected $primaryKey       = 'config_id';
    protected $protectFields    = false;
}
