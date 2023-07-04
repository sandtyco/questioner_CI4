<?php

namespace App\Models;

use CodeIgniter\Model;

class JawabanModel extends Model
{
    protected $table            = 'tbl_jawaban';
    protected $primaryKey       = 'jawaban_id';
    protected $protectFields    = false;

    private $columnSearch = [
       'tbl_jawaban.user_id',
       'tbl_user.nama',
     
    ];
    private $columnOrder = [
        'tbl_jawaban.user_id',
        'tbl_user.nama',
        'jawaban',
    ];

    private $returnSelect = [
        'count(jawaban_id) as jumlah_jawaban',
        'nama',
        'email',
        'status_mahasiswa',
        'institusi',
        'prodi',
        'wilayah',
        'user_identitas',
        'tbl_jawaban.user_id',
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
        $userAnswer = $this->dt
        ->select($this->returnSelect)
        ->groupBy('tbl_jawaban.user_id')
        ->join('tbl_user', 'tbl_user.user_id = tbl_jawaban.user_id');

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

    public function gets($params = null)
    {
        $userAnswer = $this->builder()
        ->select($this->returnSelect)
        ->groupBy('tbl_jawaban.user_id')
        ->join('tbl_user', 'tbl_user.user_id = tbl_jawaban.user_id')
        ->get()
        ->getResultArray();

        return $userAnswer;
    }
}
