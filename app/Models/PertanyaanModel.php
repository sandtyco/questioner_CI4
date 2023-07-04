<?php

namespace App\Models;

use CodeIgniter\Model;

class PertanyaanModel extends Model
{
    protected $table            = 'tbl_pertanyaan';
    protected $primaryKey       = 'pertanyaan_id';
    protected $protectFields    = false;

    var $order = ['serial_number', 'ASC'];

    private $columnSearch = [
       'serial_number',
       'pertanyaan',
       'pilihan_jawaban',
       'type_jawaban',
       'kode_pertanyaan',
    ];
    private $columnOrder = [
       'serial_number',
       'pertanyaan',
       'pilihan_jawaban',
       'type_jawaban',
       'kode_pertanyaan',
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

        // $order = $this->order;
        $this->dt->orderBy('serial_number', 'ASC');

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
