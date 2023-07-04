<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class User extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation               = \Config\Services::validation();
    }
    public function index()
    {
        $data = [
            'title' => name_app().' - User',
            'page'  => 'User'
        ];
        return view('user/index', $data);
    }

    public function modalImport()
    {
        if($this->request->isAJAX())
        {
            $modalId = 'modalImport';
            $data['modalId'] = $modalId;
            $msg = [
                'modal' => [
                    'view'  => view('user/modal/import', $data),
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
                        'password'          => $this->createPassword('12345'),
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

            $data = $this->userModel->getDatatables($params);
        
            $no = $start + 1;
    
            $result = [];
            foreach($data as $d)
            {

                $viewnama = '<b>'.$d['nama'].'</b>';

                if($d['user_identitas'] != null)
                {
                    $viewnama .= '<br> '.$d['user_identitas'];
                }

                $aksi = [
                    'user_id' => $d['user_id']
                ];
                $result[] = [
                    'no'                    => $no++,
                    'nama'                  => $viewnama,
                    'email'                 => $d['email'],
                    'institusi'             => $d['institusi'],
                    'prodi'                 => $d['prodi'],
                    'wilayah'               => $d['wilayah'],
                    'otoritas'              => $d['role'],
                    'status'                => $d['status_mahasiswa'] == 'Aktif' ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Alumni</span>',
                    'aksi'                  => view('user/assets/aksi', $aksi)
                ];
            }
    
            $callback = array(
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->userModel->countAll(),
                'recordsFiltered'   => $this->userModel->countFiltered($params),
                'data'				=> $result,
            );
    
            return json_encode($callback);      
        }
    }

}
