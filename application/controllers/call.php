<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Call extends CI_Controller {

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
        	$this->data['call_to_id'] = '';
        	$this->data['username'] = $this->session->userdata('username');
        	$this->data['nav'] = '
                      <!--li><a href="'.base_url().'dashboard/">Home</a></li-->
                      <li class="active"><a href="#">Call</a></li>
                      <li><a href="'.base_url().'room/">Room</a></li>
                    ';
	        $this->data['nav_right'] = '<li class="dropdown">
		                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Your ID : '.$this->data['username'].'</a>
		                        <ul class="dropdown-menu" role="menu">
		                          <li><a href="'.base_url().'dashboard/logout/" id="logout">Logout</a></li>
		                        </ul>
		                      </li>';
        }
    }

	public function index()
	{
		$this->load->view('call', $this->data);
	}

	public function user($id)
	{
		if($id==NULL)
		{
			redirect('/call/');
		}
		else
		{
			$this->data['call_to_id'] = $id;
			$this->load->view('call', $this->data);
		}
	}

	public function list_user()
	{
		$this->load->model('daftar');
		$online_user = $this->daftar->get_online();
		$string =  '';
		if ($online_user->num_rows() > 0)
		{
		   foreach ($online_user->result() as $row)
		   {
		   		if($row->status ==1)
		   		{
		   			$string = $string.'<li><a href="'.base_url().'call/user/'.$row->username.'/"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true">&nbsp</span>'.$row->username.'</a></li>';
		   		}
		   		else
		   		{
		   			$string = $string.'<li class="disabled"><a href="#"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true">&nbsp</span>'.$row->username.' - On Call</a></li>';
		   		}
		   }
		}
		echo $string;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
