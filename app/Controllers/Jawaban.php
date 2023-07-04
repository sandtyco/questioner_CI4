<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WebConfigModel;
use App\Models\JawabanModel;
use App\Models\PertanyaanModel;
use App\Models\UserModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Calculation\Financial;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;

class Jawaban extends BaseController
{
    protected $userModel;
    protected $pertanyaanModel;
    protected $jawabanModel;
    protected $WebConfigModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel                = new UserModel();
        $this->jawabanModel                = new JawabanModel();
        $this->pertanyaanModel                = new PertanyaanModel();
        $this->WebConfigModel                = new WebConfigModel();
        $this->validation               = \Config\Services::validation();
    }
    public function index()
    {
        $config = $this->WebConfigModel->find(1);
        $data = [
            'title' => name_app().' - Jawaban',
            'page'  => 'Jawaban',
            'lock'  => $config['lock']
        ];
        return view('jawaban/index', $data);
    }

    public function detail($user_id)
    {
        $user = $this->userModel->find($user_id);
        $data = [
            'title' => name_app().' - Jawaban '.$user['nama'],
            'page'  => 'Jawaban '.$user['nama'],
            'user'  => $user
        ];
        return view('jawaban/detail', $data);
    }

    public function reloadPertanyaan($user_id)
    {
        if($this->request->isAJAX())
        {
            $allPertanyaan = $this->pertanyaanModel
            
            ->orderBy('serial_number')->find();

            $pertanyaan = [];
            foreach($allPertanyaan as $p)
            {
                $where = [
                    'user_id'       => $user_id,
                    'pertanyaan_id' => $p['pertanyaan_id']
                ];
                $checkAvaliable = $this->jawabanModel->where($where)->first();
                
                $rest_temp = $p;
                if(!empty($checkAvaliable))
                {
                    $jawaban = $checkAvaliable['jawaban'];
                }else{
                    $jawaban = '';
                }


                switch($p['type_jawaban'])
                {
                    case 'short' :
                        $data['jawaban'] = $jawaban;
                        $data['pertanyaan_id'] = $p['pertanyaan_id'];
                        $viewResponse = view('jawaban/assets/short', $data);
                    break;
                    case 'long' :
                        $data['jawaban'] = $jawaban;
                        $data['pertanyaan_id'] = $p['pertanyaan_id'];
                        $viewResponse = view('jawaban/assets/long', $data);
                    break;
                    case 'optional' :
                        $data['jawaban'] = $jawaban;
                        $data['pilihan_jawaban'] = explode(',', $p['pilihan_jawaban'] );
                        $data['pertanyaan_id'] = $p['pertanyaan_id'];
                        $viewResponse = view('jawaban/assets/optional', $data);
                    break;
                    case 'range' :
                        $data['jawaban'] = $jawaban;
                        $data['pilihan_jawaban'] = explode(',', $p['pilihan_jawaban'] );
                        $data['pertanyaan_id'] = $p['pertanyaan_id'];
                        $viewResponse = view('jawaban/assets/range', $data);
                    break;
                    case 'checklist' :
                        $data['jawaban'] = explode(',', $jawaban);
                        $data['pilihan_jawaban'] = explode(',', $p['pilihan_jawaban'] );
                        $data['pertanyaan_id'] = $p['pertanyaan_id'];
                        $viewResponse = view('jawaban/assets/checklist', $data);
                    break;
                }

                $rest_temp['jawaban'] = $jawaban;
                $rest_temp['viewResponse'] = $viewResponse;
                $pertanyaan[] = $rest_temp;
            }

            $data['pertanyaan'] = $pertanyaan;
            $data['user_id'] = $user_id;
            $msg = [
                'success' => [
                    'view' => view('jawaban/assets/view-pertanyaan', $data )
                ]
            ];
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

            $data = $this->jawabanModel->getDatatables($params);
        
            $no = $start + 1;

            $allQuestion = $this->pertanyaanModel->countAllResults();
    
            $result = [];
            foreach($data as $d)
            {
                if($allQuestion == $d['jumlah_jawaban'])
                {
                    $status = '<span class="badge badge-success">Lengkap</span>';
                }else{
                    $status = '<span class="badge badge-warning">Belum Lengkap</span>';
                }
                
                $aksi = [
                    'user_id' => $d['user_id']
                ];
                $result[] = [
                    'no'                    => $no++,
                    'nama'                  => '<b>'.$d['nama'].'</b>' .' <br>'. $d['user_identitas'],
                    'institusi'             => $d['institusi'] .' <br>'. $d['prodi'],
                    'wilayah'               => $d['wilayah'],
                    'status'                => $status,
                    'aksi'                  => view('jawaban/assets/aksi', $aksi)
                ];
            }
    
            $callback = array(
                'draw'              => $this->request->getPost('draw'),
                'recordsTotal'      => $this->jawabanModel->countAll(),
                'recordsFiltered'   => $this->jawabanModel->countFiltered($params),
                'data'				=> $result,
            );
    
            return json_encode($callback);      
        }
    }

    public function saveJawaban($user_id, $pertanyaan_id)
    {
        if($this->request->isAJAX())
        {
            $val = $this->request->getPost('val');

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

    public function delete($user_id)
    {
        $this->jawabanModel->where('user_id', $user_id)->delete();

        $msg = [
            'success' => [
                'msg' => 'Berhasil menghapus jawaban user'
            ]
        ];
        return json_encode($msg);
    }

    public function exportExcel()
    {

            $jawaban = $this->jawabanModel->gets();
            $pertanyaan = $this->pertanyaanModel->orderBy('serial_number')->find();

            $fileName = 'Rekap Quesioneer - '.date('YmdHis').'.xlsx';  
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();               
            $sheet->getColumnDimension('A')->setWidth(5);     
            $sheet->getColumnDimension('B')->setWidth(20);     
            $sheet->getColumnDimension('C')->setWidth(20);     
            $sheet->getColumnDimension('D')->setWidth(20);     
            $sheet->getColumnDimension('E')->setWidth(20);     
            $sheet->getColumnDimension('F')->setWidth(20);     
            $sheet->getColumnDimension('G')->setWidth(20);     
            $sheet->getColumnDimension('H')->setWidth(20);                   

            $sheet->setCellValue('A1', 'Quesioneer');        
            $sheet->setCellValue('A2', 'No');     
            $sheet->setCellValue('B2', 'Nama');     
            $sheet->setCellValue('C2', 'NIM');     
            $sheet->setCellValue('D2', 'Email');                      
            $sheet->setCellValue('E2', 'Institute');                      
            $sheet->setCellValue('F2', 'Progam Studi');        
            $sheet->setCellValue('G2', 'Wilayah');        
            $sheet->setCellValue('H2', 'Status Mahasiswa'); 
            
            $i = 'G';
            foreach($pertanyaan as $prt)
            {
                $sheet->setCellValue(++$i.'2', $prt['kode_pertanyaan']); 
                $sheet->getColumnDimension($i)->setWidth(20); 
            }

            $border = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '#000000'],
                    ],
                ],
            ];
            $header = [
                'font' => [
                    'bold'      => true,
                    'name'      => 'Arial',
                    'size'      => 20,
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];
            $header_table = [
                'font' => [
                    'bold'      => true,
                    'name'  => 'Arial'
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    // 'rotation' => 90,
                    'startColor' => array('argb' => 'B3C6E7')
                ],
            ];
            $vertical_align_center = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];
            $vertical_align_top = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                ],
            ];
            $horizontal_align_center = [
                'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $horizontal_align_left = [
                'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
            ];
            
            $horizontal_align_right = [
                'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
            ];
            
            $sheet->getStyle('C1')->applyFromArray($header);
            $sheet->getStyle('A2:'.$i.'2')->applyFromArray($header_table);

            $result = [];
            $no     = 1;
            $row_start = 3;

            foreach($jawaban as $jwb)
            {
                $sheet->setCellValue('A'.$row_start, $no++);

                $next_row = $row_start;
                
                
                //Nama  
                $sheet->setCellValue('B'.$next_row, $jwb['nama']); 
                $sheet->getStyle('B'.$next_row)->getAlignment()->setWrapText(true);
                //NIM  
                $sheet->setCellValue('C'.$next_row, $jwb['user_identitas']); 
                $sheet->getStyle('C'.$next_row)->getAlignment()->setWrapText(true);
                //Email  
                $sheet->setCellValue('D'.$next_row, $jwb['email']); 
                $sheet->getStyle('D'.$next_row)->getAlignment()->setWrapText(true);
                //Institusi  
                $sheet->setCellValue('E'.$next_row, $jwb['institusi']); 
                $sheet->getStyle('E'.$next_row)->getAlignment()->setWrapText(true);
                //Prodi
                $sheet->setCellValue('F'.$next_row, $jwb['prodi']); 
                $sheet->getStyle('F'.$next_row)->getAlignment()->setWrapText(true);
                //Wilayah
                $sheet->setCellValue('G'.$next_row, $jwb['wilayah']); 
                $sheet->getStyle('G'.$next_row)->getAlignment()->setWrapText(true);
                //Status Mahasiswa
                $sheet->setCellValue('H'.$next_row, $jwb['status_mahasiswa']); 
                $sheet->getStyle('H'.$next_row)->getAlignment()->setWrapText(true);

                $i = 'H';
                foreach($pertanyaan as $ptr)
                {
                    //Status Mahasiswa
                    $where = [
                        'user_id'       => $jwb['user_id'],
                        'pertanyaan_id' => $ptr['pertanyaan_id']
                    ];
                    $jawabanUser = $this->jawabanModel->where($where)->first();

                    $sheet->setCellValue($i.$next_row, $jawabanUser['jawaban']); 
                    $sheet->getStyle($i++.$next_row)->getAlignment()->setWrapText(true);
                }
                $next_row++;
                $row_start = $next_row++;
            }

            $end_row = $row_start -1;

            // $sheet->mergeCells('A'.$row_start.':D'.$row_start);
            // $sheet->getStyle('A4:E'.$row_start)->applyFromArray($border);
            $sheet->getStyle('A3:A'.$end_row)->applyFromArray($vertical_align_center);
            $sheet->getStyle('B3:B'.$end_row)->applyFromArray($vertical_align_center);
            $sheet->getStyle('C3:C'.$end_row)->applyFromArray($vertical_align_center);
            $sheet->getStyle('D3:D'.$end_row)->applyFromArray($vertical_align_center);
            $sheet->getStyle('E3:E'.$end_row)->applyFromArray($vertical_align_center);
            $sheet->getStyle('F3:F'.$end_row)->applyFromArray($vertical_align_center);
            $sheet->getStyle('G3:G'.$end_row)->applyFromArray($vertical_align_center);
            $sheet->getStyle('H3:H'.$end_row)->applyFromArray($vertical_align_center);
            
            $i = 'G';
            foreach($pertanyaan as $ptr)
            {
                $sheet->getStyle($i.'3:'.++$i.$end_row)->applyFromArray($vertical_align_center);
            }

            $sheet->getStyle('A2:'.$i.$end_row)->applyFromArray($border);
    
            $file_save_dir = $_SERVER['DOCUMENT_ROOT']."/assets/temp/".$fileName; 
    
            $writer = new WriterXlsx($spreadsheet);
            $writer->save($file_save_dir); 
    
            $file = $file_save_dir;
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: private');
                header('Pragma: private');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                unlink($file_save_dir);
                exit;
            }else{
                session()->setFlashdata('error', 'file tidak ditemukan');
                return redirect()->back();
            }
            
            unlink($file_save_dir);
            return redirect()->back();
    }

    public function lock()
    {
        if($this->request->isAJAX())
        {
            $config = $this->WebConfigModel->find(1);

            if($config['lock'] == '0')
            {
                $data = [
                    'lock' => 1
                ];
            }else{
                $data = [
                    'lock' => '0'
                ];
            }
            $this->WebConfigModel->update(1, $data);
        }
    }
}
