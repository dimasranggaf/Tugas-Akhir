<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
        if($this->session->userdata('logged_in') != TRUE)
        {
        	redirect('/home/');
        }
        else
        {
        	$this->data['username'] = $this->session->userdata('username');
        	$this->data['nav'] = '
                      <!--li class="active"><a href="#">Home</a></li-->
                      <li><a href="'.base_url().'call/">Call</a></li>
                      <li><a href="'.base_url().'room/">Room</a></li>
                    ';
	        $this->data['nav_right'] = '<li class="dropdown">
		                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Your ID : '.$this->data['username'].'</a>
		                        <ul class="dropdown-menu" role="menu">
		                          <li><a href="'.base_url().'dashboard/logout/">Logout</a></li>
		                        </ul>
		                      </li>';
        }
    }

	public function index()
	{
		$this->load->view('dashboard', $this->data);
	}

	public function logout()
	{
		$this->load->model('daftar');
		$this->daftar->update_offline();
		$this->session->sess_destroy();
		echo "<script>peer.disconnect();</script>";
		redirect('/home/');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
