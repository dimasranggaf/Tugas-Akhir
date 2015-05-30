<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	private $data = [];

	public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->data['nav'] = '';
        $this->data['daftar'] = '';
        $this->data['nav_right'] = '<li><a href="#" data-toggle="modal" data-target="#formLogin">Login</a></li>';
        if($this->session->userdata('logged_in') == TRUE)
        {
        	redirect('/call/');
        }
    }

	public function index()
	{
		$this->load->view('home', $this->data);
	}

	public function login()
	{
		$username = $this->input->post('inputUser');
		$this->load->model('daftar');
		$hasil = $this->daftar->get_user();
		if($hasil == 1)
		{
			$sess = array(
                   'username'  => $username,
                   'logged_in' => TRUE
               		);

			$this->session->set_userdata($sess);
			$this->daftar->update_online();
			redirect('/call/');
		}
		else
		{
			redirect('/home/failed/');
		}
	}

	public function success()
	{
        $this->data['daftar'] = '<div class="panel panel-success">
				                <div class="panel-heading">
				                  <h3 class="panel-title">Register success</h3>
				                </div>
				                <div class="panel-body">
				                  Please login.
				                </div>
				            </div>';
		$this->load->view('home', $this->data);
	}

	public function failed()
	{
        $this->data['daftar'] = '<div class="panel panel-danger">
				                <div class="panel-heading">
				                  <h3 class="panel-title">Login Error</h3>
				                </div>
				                <div class="panel-body">
				                  Username or Password is wrong. Try again.
				                </div>
				            </div>';
		$this->load->view('home', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
