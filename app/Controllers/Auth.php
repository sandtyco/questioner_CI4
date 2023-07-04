<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
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
            'title' => name_app().' - Login'
        ];
        return view('auth/index', $data);
    }

    public function register()
    {
        $data = [
            'title' => name_app().' - Registrasi'
        ];
        return view('auth/register', $data);
    }

    public function login()
    {
        $valid = $this->validate([
            'password' => [
                'rules' => 'required',
                'label' => 'Password',
                'errors'    => [
                    'required'      => '{field} tidak boleh kosong',
                ]
            ],
            'email' => [
                'rules' => 'required',
                'label' => 'Email',
                'errors'    => [
                    'required'      => '{field} tidak boleh kosong',
                ]
            ],
        ]);
        if (!$valid) {

            $errors =  $this->validation->getErrors();

            $msg = '';
            foreach($errors as $err)
            {
                $msg .=  $err.'<br>';
            }

            session()->setFlashdata('error', $msg);
            return redirect()->to('/login');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $check = $this->userModel->where('email', $email)->find();

        if(empty($check))
        {
            session()->setFlashdata('error', 'Email yang anda masukan belum terdaftar / tidak ditemukan');
            return redirect()->to('/login');
        }

        $user = $check[0];
        $success = $this->verifyPassword($password, $user['password']);
        if(!$success)
        {
            session()->setFlashdata('error', 'Password yang anda masukan salah');
            return redirect()->to('/login');
        }

        $this->setSession($user['user_id']);

        if($user['role'] == 'responden')
        {
            session()->setFlashdata('success', 'Berhasil masuk');
            return redirect()->to('/');
        }

        if($user['role'] == 'admin')
        {
            session()->setFlashdata('success', 'Berhasil masuk');
            return redirect()->to('/pertanyaan');
        }
        
    }


    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }


    public function attemptRegister()
    {
        $valid = $this->validate([
            'nama' => [
                'rules' => 'required',
                'label' => 'Nama',
                'errors'    => [
                    'uploaded'      => '{field} tidak boleh kosong',
                ]
            ],
            'prodi' => [
                'rules' => 'required',
                'label' => 'Program Studi',
                'errors'    => [
                    'uploaded'      => '{field} tidak boleh kosong',
                ]
            ],
            'nim' => [
                'rules' => 'required',
                'label' => 'NIM',
                'errors'    => [
                    'uploaded'      => '{field} tidak boleh kosong',
                ]
            ],
            'wilayah' => [
                'rules' => 'required',
                'label' => 'Wilayah',
                'errors'    => [
                    'uploaded'      => '{field} tidak boleh kosong',
                ]
            ],
            'password' => [
                'rules' => 'required',
                'label' => 'Password',
                'errors'    => [
                    'uploaded'      => '{field} tidak boleh kosong',
                ]
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'label' => 'Password Confirm',
                'errors'    => [
                    'uploaded'      => '{field} tidak boleh kosong',
                    'matches'      => '{field} tidak sama',
                ]
            ],
            'email' => [
                'rules' => 'required|is_unique[tbl_user.email]',
                'label' => 'Email',
                'errors'    => [
                    'uploaded'      => '{field} tidak boleh kosong',
                    'is_unique'      => '{field} sudah ada , silahkan masukan {field} lainnya',
                ]
            ],
        ]);
        if (!$valid) {
            session()->setFlashdata('error', $this->validation->getErrors());
            return redirect()->to('/register');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $data = [
            'nama'              => $this->request->getPost('nama'),
            'prodi'             => $this->request->getPost('prodi'),
            'institusi'         => $this->request->getPost('institusi'),
            'wilayah'           => $this->request->getPost('wilayah'),
            'status_mahasiswa'  => $this->request->getPost('status_mahasiswa'),
            'user_identitas'    => $this->request->getPost('nim'),
            'role'              => 'responden',
            'email'             => $email,
            'password'          => $this->createPassword($password),
        ];

        $this->userModel->insert($data);

        session()->setFlashdata('success', 'Berhasil mendaftar');
        return redirect()->to('/login');
    }
}
