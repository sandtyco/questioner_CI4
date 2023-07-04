<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'tbl_user';
    protected $primaryKey       = 'user_id';
    protected $protectFields    = false;
    var $order = ['tb_user.created_at', 'DESC'];

    private $columnSearch = [
       'nama',
       'user_identitas',
       'institusi',
       'prodi',
       'wilayah',
       'email',
    ];
    private $columnOrder = [
        'nama',
        'user_identitas',
        'institusi',
        'prodi',
        'wilayah',
        'email',
    ];

    protected $db;
    protected $dt;
    protected $request;
    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->dt = $this->db->table($this->table);
    }

    private function getDatatablesQuery($params)
    {
        $i = 0;
        foreach ($this->columnSearch as $item) {
            if ($params['searchValue']) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item,$params['searchValue']);
                } else {
                    $this->dt->orLike($item,$params['searchValue']);
                }
                if (count($this->columnSearch) - 1 == $i)
                    $this->dt->groupEnd();
            }
            $i++;
        }

        $where = @$params['where'];


        if($where != null)
        {
            $this->dt->where($params['where']);
        }

        if ($params['order']) {
            $this->dt->orderBy($this->columnOrder[$params['orderColumn']], $params['orderDir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dt->orderBy(key($order), $order[key($order)]);
        }

        return $this->dt;
        
    }

    public function getDatatables($params)
    {
        $this->getDatatablesQuery($params);
        if ($params['length'] != -1)
            $this->dt->limit($params['length'], $params['start']);
        $query = $this->dt->get();
        return $query->getResult('array');
    }

    public function countFiltered($params)
    {
        $this->getDatatablesQuery($params);
        return $this->dt->countAllResults();
    }

    public function countAll()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }
}
