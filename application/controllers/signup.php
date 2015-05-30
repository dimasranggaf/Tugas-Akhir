<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {

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
        $this->data['prob'] = '';
        $this->data['nav_right'] = '<li><a href="#" data-toggle="modal" data-target="#formLogin">Masuk</a></li>';
        if($this->session->userdata('logged_in') == TRUE)
        {
        	redirect('/dashboard/');
        }
    }

	public function index()
	{
		$this->load->view('signup', $this->data);
	}

	public function ready()
	{
		$this->load->model('daftar');
		$hasil = $this->daftar->insert_user();
		if($hasil == 0)
		{
			redirect('/signup/failed/');
		}
		else
		{
			redirect('/home/success/');
		}
	}

	public function failed()
	{
        $this->data['prob'] = '<div class="panel panel-danger">
				                <div class="panel-heading">
				                  <h3 class="panel-title">Pendaftaran Gagal</h3>
				                </div>
				                <div class="panel-body">
				                  Maaf! Username yang dipilih sudah terdaftar. Silahkan coba lagi.
				                </div>
			              </div>';
		$this->load->view('signup', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
