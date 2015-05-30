<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Room extends CI_Controller {

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
                      <!--li><a href="'.base_url().'dashboard/">Home</a></li-->
                      <li><a href="'.base_url().'call/">Call</a></li>
                      <li class="active"><a href="#">Room</a></li>
                    ';
	        $this->data['nav_right'] = '<li class="dropdown">
		                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Your ID : '.$this->data['username'].'</a>
		                        <ul class="dropdown-menu" role="menu">
		                          <li><a href="'.base_url().'dashboard/logout/">Logout</a></li>
		                        </ul>
		                      </li>';
		$this->data['call_to_id'] = '';
        }
    }

	public function index()
	{
		$this->load->view('room', $this->data);
	}

	public function list_room()
	{
		$this->load->model('daftar');
		$rooms = $this->daftar->get_rooms();
		$string =  '';
		if ($rooms->num_rows() > 0)
		{
		   foreach ($rooms->result() as $row)
		   {
		   		$string = $string.'<li><a href="#" onclick="joinRoom(this)" data-toggle="modal" data-target="#joinRoom" id="'.$row->nama.'"><span class="glyphicon glyphicon-th" aria-hidden="true">&nbsp</span>'.$row->nama.' - '.$row->us.' User(s)</a></li>';
		   }
		}
		echo $string;
	}

	public function create()
	{
		$this->load->model('daftar');
		$hasil = $this->daftar->create_room();
		$string = '';
		if($hasil == 0)
		{
			$string = '<div class="panel panel-danger">
					        <div class="panel-heading">
					            <h3 class="panel-title">Create Room Error</h3>
					        </div>
					        <div class="panel-body">
					            Name or Password Room is wrong. Try another parse.
					        </div>
					    </div>';
		}
		else
		{
			$string = '<div class="panel panel-success">
					        <div class="panel-heading">
					            <h3 class="panel-title">Create Room Success</h3>
					        </div>
					        <div class="panel-body">
					            Try to join room.
					        </div>
					    </div>';
		}
		echo $string;
	}

	public function join($id)
	{
		if($id==NULL)
		{
			redirect('/room/');
		}
		else
		{
			$pass = md5($this->input->post('inputPassword'));
			$this->load->model('daftar');
			$room = $this->daftar->get_room($id, $pass);
			if($room == 1)
			{
				$speaker = $this->daftar->get_speaker($id);
				if ($speaker->num_rows() > 0)
				{
					$row = $speaker->row();
					$this->data['call_to_id'] = $row->username;
					$this->load->view('room', $this->data);
				}
				else
				{
					$this->data['call_to_id'] = $this->data['username'];
					$this->daftar->update_speaker();
					$this->load->view('room', $this->data);
				}
			}
			else
			{
				redirect('/room/');
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
