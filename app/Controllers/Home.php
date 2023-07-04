<?php

namespace App\Controllers;

use App\Models\JawabanModel;
use App\Models\PertanyaanModel;
use App\Models\WebConfigModel;

class Home extends BaseController
{
    protected  $pertanyaanModel;
    protected  $webConfigModel;
    protected  $jawabanModel;
    public function __construct()
    {
        $this->pertanyaanModel = new PertanyaanModel();
        $this->jawabanModel = new JawabanModel();
        $this->webConfigModel = new WebConfigModel();
    }
    public function index()
    {
        $data = [
            'title' => name_app().''
        ];
        return view('responden/index', $data);
    }
    
    public function kuesioner()
    {
        $config = $this->webConfigModel->find(1);
        $data = [
            'title' => name_app().'',
            'lock'  => $config['lock'] == '1' ? true : false
        ];
        return view('responden/kuesioner', $data);
    }

    public function evaluasi()
    {
        $data = [
            'title' => name_app().''
        ];
        return view('responden/evaluasi', $data);
    }

    public function reloadPertanyaan()
    {
        if($this->request->isAJAX())
        {
            $allPertanyaan = $this->pertanyaanModel
            
            ->orderBy('serial_number')->find();

            $pertanyaan = [];
            foreach($allPertanyaan as $p)
            {
                $user_id = session()->user_id;
                $where = [
                    'user_id'       => $user_id,
                    'pertanyaan_id' => $p['pertanyaan_id']
                ];
                $checkAvaliable = $this->jawabanModel->where($where)->find();
                
                $rest_temp = $p;
                if(!empty($checkAvaliable))
                {
                    $jawaban = $checkAvaliable[0]['jawaban'];
                }else{
                    $jawaban = '';
                }

                switch($p['type_jawaban'])
                {
                    case 'short' :
                        $data['jawaban'] = $jawaban;
                        $data['pertanyaan_id'] = $p['pertanyaan_id'];
                        $viewResponse = view('responden/assets/short', $data);
                    break;
                    case 'long' :
                        $data['jawaban'] = $jawaban;
                        $data['pertanyaan_id'] = $p['pertanyaan_id'];
                        $viewResponse = view('responden/assets/long', $data);
                    break;
                    case 'optional' :
                        $data['jawaban'] = $jawaban;
                        $data['pilihan_jawaban'] = explode(',', $p['pilihan_jawaban'] );
                        $data['pertanyaan_id'] = $p['pertanyaan_id'];
                        $viewResponse = view('responden/assets/optional', $data);
                    break;
                    case 'range' :
                        $data['jawaban'] = $jawaban;
                        $data['pilihan_jawaban'] = explode(',', $p['pilihan_jawaban'] );
                        $data['pertanyaan_id'] = $p['pertanyaan_id'];
                        $viewResponse = view('responden/assets/range', $data);
                    break;
                    case 'checklist' :
                        $data['jawaban'] = explode(',', $jawaban);
                        $data['pilihan_jawaban'] = explode(',', $p['pilihan_jawaban'] );
                        $data['pertanyaan_id'] = $p['pertanyaan_id'];
                        $viewResponse = view('responden/assets/checklist', $data);
                    break;
                }

                $rest_temp['jawaban'] = $jawaban;
                $rest_temp['viewResponse'] = $viewResponse;
                $pertanyaan[] = $rest_temp;
            }

            $data['pertanyaan'] = $pertanyaan;
            $msg = [
                'success' => [
                    'view' => view('responden/assets/view-pertanyaan', $data )
                ]
            ];
            return json_encode($msg);
        }
    }

    public function saveJawaban($pertanyaan_id)
    {
        if($this->request->isAJAX())
        {
            $val = $this->request->getPost('val');
            $user_id = session()->user_id;

            $where = [
                'pertanyaan_id' => $pertanyaan_id,
                'user_id' => $user_id
            ];
            $checkAvaliable = $this->jawabanModel->where($where)->find();

            if(empty($checkAvaliable))
            {
                $data = [
                    'pertanyaan_id' => $pertanyaan_id,
                    'user_id'       => $user_id,
                    'jawaban'       => $val
                ];
                $this->jawabanModel->insert($data);
                return json_encode(['insert']);
            }else{
                $data = [
                    'jawaban'       => $val
                ];
                $update = $this->jawabanModel->update($checkAvaliable[0]['jawaban_id'], $data);
                return json_encode($update);
            }

            return json_encode(['success']);

          
        }
    }
}
