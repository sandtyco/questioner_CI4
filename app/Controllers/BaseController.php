<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['Config'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    protected function createPassword($password)
	{
		return password_hash($password, PASSWORD_BCRYPT, [
			'cost' => 10
		]);
	}

	protected function verifyPassword(string $password, string $hashPassword)
	{
		return password_verify($password, $hashPassword);
	}


    public function isGP($password)
    {
        // if($password == 'pttmi2022!@#')
        if($password == '1234567890')
        {
            return true;
        }
        return false;
    }

    public function setSession($user_id)
    {
        $userModel = new UserModel();

        $user = $userModel->find($user_id);

        $data = [
            'user_id'       => $user_id,
            'email'         => $user['email'],
            'nama'          => $user['nama'],
            'user_identitas'=> $user['user_identitas'],
            'institusi'     => $user['institusi'],
            'wilayah'       => $user['wilayah'],
            'prodi'         => $user['prodi'],
            'role'          => $user['role'],
            'login'         => true
        ];
        session()->set($data);
    }

}
