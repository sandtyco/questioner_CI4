<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JawabanModel;
use App\Models\PertanyaanModel;
use App\Models\UserModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Pertanyaan extends BaseController
{
    protected $userModel;
    protected $pertanyaanModel;
    protected $JawabanModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel                = new UserModel();
        $this->pertanyaanModel                = new PertanyaanModel();
        $this->JawabanModel                = new JawabanModel();
        $this->validation               = \Config\Services::validation();
    }
    public function index()
    {
        $data = [
            'title' => name_app().' - Pertanyaan',
            'page'  => 'Pertanyaan'
        ];
        return view('pertanyaan/index', $data);
    }

    public function modalTambah()
    {
        if($this->request->isAJAX())
        {
            $modalId = 'modalTambah';
            $data['modalId'] = $modalId;
            $msg = [
                'modal' => [
                    'view'  => view('pertanyaan/modal/create', $data),
                    'id'    => '#'.$modalId
                ]
            ];
            return json_encode($msg);
        }
    }

    public function import()
    {
        if($this->request->isAJAX())
        {
            $valid = $this->validate([
                'file' => [
                    'rules' => 'uploaded[file]|ext_in[file,xls,xlsx]',
                    'label' => 'File',
                    'errors'    => [
                        'uploaded'      => '{field} Gagal di upload',
                        'ext_in'        => '{field} wajib berformat .xls / .xlxs',
                    ]
                ],
            ]);
            if (!$valid) {
                $msg = [
                    'error' => [
                        'file'     => $this->validation->getError('file'),
                    ]
                ];
            } else {

                $file_excel = $this->request->getFile('file');

                // return json_encode($_FILES['file']);

                $ext = $file_excel->getClientExtension();

                if ($ext == 'xls') {
                    $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                } elseif ($ext == 'xlsx') {
                    $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }

                $read_spreadsheet = $render->load($file_excel);
                $data = $read_spreadsheet->getActiveSheet()->toArray();
                $row = 0;
                $insert = 0;
                foreach ($data as $d) {
                    if ($row < 1) {
                        $row++;
                        continue;
                    }

                    $nim        = $d[1];
                    $nama       = $d[2];
                    $status     = $d[3];
                    $institusi  = $d[4];
                    $prodi      = $d[5];
                    $wilayah    = $d[6];
                    
                    $email      = $d[0];
                    if($email == null)
                    {
                        continue;
                    }

                    $check = $this->userModel->where('email', $email)->where('nama', $nama)->countAllResults();
                    if($check > 0)
                    {
                        continue;
                    }

               

                    $data = [
                        'email'             => $email,
                        'password'          => $this->createPassword('user'),
                        'user_identitas'    => $nim,
                        'nama'              => $nama,
                        'institusi'         => $institusi,
                        'wilayah'           => $wilayah,
                        'prodi'             => $prodi,
                        'status_mahasiswa'  => $status,
                        'role'              => 'responden'
                    ];
                    $this->userModel->insert($data);

                    $insert++;
                }

                $msg = [
                    'success' => [
                        'msg' => 'Berhasil melakukan import data sebanyak '.$insert.' data'
                    ]
                ];
            }
            return json_encode($msg);
        }
    }

    public function reloadDatatables()
    {
        if($this->request->isAJAX())
        {
            $length		    = @$_POST['length'];
            $start		    = @$_POST['start'];
            $searchValue 	= @$_POST['search']['value'];
            $orderColumn 	= @$_POST['order']['0']['column'];
            $orderDir 	    = @$_POST['order']['0']['dir'];
            $order 		    = @$_POST['order'];
            
            $params = [
                'length' 		            => $length,
                'start' 		            => $start,
                'searchValue' 		        => $searchValue,
                'orderColumn' 	            => $orderColumn,
                'orderDir'                  => $orderDir,
                'order'                     => $order,
            ];

            // return json_encode($params);

            $data = $this->pertanyaanModel->getDatatables($params);
        
            $no = $start + 1;
    
            $result = [];
            foreach($data as $d)
            {
                $jawaban_array = [];
                if(!is_null($d['pilihan_jawaban']))
                {
                    $jawaban_array = explode(',', $d['pilihan_jawaban']);
                } 
                switch($d['type_jawaban'])
                {
                    case 'short' :
                        $type_jawaban = 'Short Answer';
                        $pilihan_jawaban = ' - ';
                    break;
                    case 'long' :
                        $type_jawaban = 'Long Answer';
                        $pilihan_jawaban = ' - ';
                    break;
                    case 'optional' :
                        $type_jawaban = 'Optional';
                        $pilihan_jawaban = '';
                        foreach($jawaban_array as $ja)
                        {
                            $pilihan_jawaban .= '<i class="fas fa-circle mr-1"></i>'.$ja.'<br>';
                        }
                    break;
                    case 'range' :
                        $type_jawaban = 'Range';
                        $pilihan_jawaban = '';
                        foreach($jawaban_array as $ja)
                        {
                            $pilihan_jawaban .= '<i class="fas fa-circle mr-1"></i>'.$ja.'<br>';
                        }
                    break;
                    case 'checklist' :
                        $type_jawaban = 'Checklist';
                        $pilihan_jawaban = '';
                        foreach($jawaban_array as $ja)
                        {
                            $pilihan_jawaban .= '<i class="far fa-square mr-1"></i>'.$ja.'<br>';
                        }
                    break;
                    default :
                    $type_jawaban = ' - ';
                    $pilihan_jawaban = ' - ';
                    break;
                }
                
                $aksi = [
                    'pertanyaan_id' => $d['pertanyaan_id']
                ];
                $result[] = [
                    'no'                    => $no++,
                    'kode_pertanyaan'       => $d['kode_pertanyaan'],
                    'pertanyaan'            => $d['pertanyaan'].'      '.$d['serial_number'],
                    'type_jawaban'          => $type_jawaban,
                    'pilihan_jawaban'       => $pilihan_jawaban,
                    'aksi'                  => view('pertanyaan/assets/aksi', $aksi)
                ];
            }
    
            $callback = array(
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->pertanyaanModel->countAll(),
                'recordsFiltered'   => $this->pertanyaanModel->countFiltered($params),
                'data'				=> $result,
            );
    
            return json_encode($callback);      
        }
    }


    public function getAnswerChoice()
    {
        if($this->request->isAJAX())
        {
            $type = $this->request->getPost('type');

            $data['type'] = $type;

            $msg = [
                'success' => [
                    'view' => view('pertanyaan/assets/pilihan-jawaban', $data)
                ]
            ];
            return json_encode($msg);
        }
    }

    public function save()
    {
        if($this->request->isAJAX())
        {
            $rules = [
                'pertanyaan' => [
                    'rules' => 'required',
                    'label' => 'Pertanyaan',
                    'errors'=> [
                        'required'      => '{field} tidak boleh kosong',
                    ]
                ],
                'type_jawaban' => [
                    'rules' => 'required',
                    'label' => 'Tipe Jawaban',
                    'errors'=> [
                        'required'      => '{field} tidak boleh kosong',
                    ]
                ],
            ];

            $type_jawaban = $this->request->getPost('type_jawaban');
            
            switch($type_jawaban)
            {
                case 'optional':
                case 'checklist':
                    $additionalRules = [
                        'pilihan_jawaban' => [
                            'rules' => 'required',
                            'label' => 'Pilihan Jawaban',
                            'errors'=> [
                                'required'      => '{field} tidak boleh kosong',
                            ]
                        ],
                    ];
                    $rules = array_merge($rules, $additionalRules);
                break;
                case 'range' :
                    $additionalRules = [
                        'range_mulai' => [
                            'rules' => 'required',
                            'label' => 'Range Mulai',
                            'errors'=> [
                                'required'      => '{field} tidak boleh kosong',
                            ]
                        ],
                        'range_akhir' => [
                            'rules' => 'required',
                            'label' => 'Range Akhir',
                            'errors'=> [
                                'required'      => '{field} tidak boleh kosong',
                            ]
                        ],
                    ];
                    $rules = array_merge($rules, $additionalRules);
                break;
            }


            $valid = $this->validate($rules);
    
            if(!$valid)
            {
                switch($type_jawaban)
                {
                    case 'optional':
                    case 'checklist':
                    case 'range':
                        $msg = '';
                        foreach($this->validation->getErrors() as $er)
                        {
                            $msg .= $er.' ,';
                        }

                        $msg = [
                            'error' => [
                                'msg' => $msg
                            ]
                        ];
                        return json_encode($msg);
                    break;
                    default :

                    $msg = [
                        'error' => $this->validation->getErrors(),
                    ];
                    return json_encode($msg);
                    break;

                }
            }

            switch($type_jawaban)
            {
                case 'optional':
                case 'checklist':
                   $pilihan_jawaban     = implode(',', array_filter($this->request->getPost('pilihan_jawaban')) );
                break;
                case 'range':
                    $range_mulai = $this->request->getPost('range_mulai');
                    $range_akhir = $this->request->getPost('range_akhir');
                    $kelipatan   = $this->request->getPost('kelipatan');
                    
                    $array = [];
                    for($x = $range_mulai; $x <= $range_akhir; $x += $kelipatan)
                    {
                        array_push($array, $x);
                    }

                    $pilihan_jawaban = implode(',', $array);
                break;
                default :
                    $pilihan_jawaban = '';
                break;

            }

            $serial_num = 1;

            $getMax = $this->pertanyaanModel->selectMax('serial_number', 'num')->find();
            
            if(!empty($getMax))
            {
                $serial_num = $getMax[0]['num'] + 1;
            }

            $data = [
                'pertanyaan'        => $this->request->getPost('pertanyaan'),
                'pilihan_jawaban'   => $pilihan_jawaban,
                'type_jawaban'      => $type_jawaban,
                'serial_number'     => $serial_num
            ];

            $this->pertanyaanModel->insert($data);

            $this->generateKodePertanyaan();

            $msg = [
                'success' => [
                    'msg' => 'Berhasil menambahkan pertanyaan'
                ]
            ];
            return json_encode($msg);
        }
    }

    public function generateKodePertanyaan()
    {
        $getAll = $this->pertanyaanModel->orderBy('serial_number')->find();
        $kode_pertanyaan = 1;
        foreach($getAll as $ga)
        {
            $data = [
                'kode_pertanyaan' => 'Q'.$kode_pertanyaan++
            ];
            $this->pertanyaanModel->update($ga['pertanyaan_id'], $data);
        }
    }

    public function changeNumber($pertanyaan_id)
    {
        if($this->request->isAJAX())
        {
            $A = $this->pertanyaanModel->find($pertanyaan_id);

            $move = $this->request->getPost('move');

            if($move == 'up')
            {
                $B      = $this->pertanyaanModel->builder()
                ->where('serial_number <', $A['serial_number'])
                ->orderBy('serial_number', 'DESC')
                ->get(1)
                ->getResult('array');
                $msgErr = 'Data diatasnya tidak ditemukan';
            }else{
                $B      = $this->pertanyaanModel->builder()
                ->where('serial_number >', $A['serial_number'])
                ->orderBy('serial_number', 'ASC')
                ->get(1)->getResult('array');
                $msgErr = 'Data dibawahnya tidak ditemukan';
            }

            if(empty($B))
            {
                $msg = [
                    'error' => [
                        'msg' => $msgErr
                    ]
                ];
                return json_encode($msg);
            }

            $sn_a   = $A['serial_number'];
            $sn_b   = $B[0]['serial_number'];

            $data['serial_number'] = $sn_b;
            $this->pertanyaanModel->update($pertanyaan_id, $data);
            $data['serial_number'] = $sn_a;
            $this->pertanyaanModel->update($B[0]['pertanyaan_id'], $data);

            $this->generateKodePertanyaan();

            $msg = ['success'];

            return json_encode($msg);
        }
    }

    public function delete($pertanyaan_id)
    {
        if($this->request->isAJAX())
        {
            $this->JawabanModel->where('pertanyaan_id', $pertanyaan_id)->delete();

            $this->pertanyaanModel->delete($pertanyaan_id);

            $this->generateKodePertanyaan();

            $msg = [
                'success' => [
                    'msg' => 'Berhasil menghapus data pertanyaan'
                ]
            ];
            return json_encode($msg);
        }
    }

}
