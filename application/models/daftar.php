<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar extends CI_Model {

     function insert_user()
    {
        $username = $this->input->post('inputUser');
        $nama = $this->input->post('inputNama');
        $email = $this->input->post('inputEmail');
        $password = md5($this->input->post('inputPassword'));
        
        $cek = $this->db->get_where('user', array('username' => $username));
        if ($cek->num_rows() > 0)
        {
            return 0;
        }
        else
        {
            $data = array(
                'username' => $username ,
                'nama' => $nama ,
                'email' => $email ,
                'password' => $password 
                );
            $this->db->insert('user', $data);
            return 1;
        } 
    }

    function get_user()
    {
        $username = $this->input->post('inputUser');
        $password = md5($this->input->post('inputPassword'));

	$array = array('status =' => 0, 'username =' => $username, 'password =' => $password);
        $this->db->where($array);
	$cek = $this->db->get('user');

        if ($cek->num_rows() > 0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function get_online()
    {
        $username = $this->session->userdata('username');
        $array = array('status !=' => 0, 'username !=' => $username);
        $this->db->where($array);
        //here we select every clolumn of the table
        $query = $this->db->get('user');
        return $query;
    }

    function get_rooms()
    {
        $string = "select id, nama, (select count(username) from user where id_room = room.id) as us from room group by room.id";
        $query = $this->db->query($string);
        return $query;
    }


    function update_online()
    {
        $data = array('status' => 1);
        $username = $this->input->post('inputUser');
        $this->db->update('user', $data, "username = '".$username."'");
    }

    function update_on_call()
    {
	$data = array('status' => 2);
	$username = $this->session->userdata('username');
	$this->db->update('user', $data, "username = '".$username."'");
    }

    function update_end_call()
    {
        $data = array('status' => 1);
        $username = $this->session->userdata('username');
        $this->db->update('user', $data, "username = '".$username."'");
    }

    function update_offline()
    {
        $data = array('status' => 0);
        $username = $this->session->userdata('username');
        $this->db->update('user', $data, "username = '".$username."'");
    }

    function update_speaker()
    {
	$data = array('status' => 2);
	$username = $this->session->userdata('username');
	$this->db->update('user', $data, "username = '".$username."'");
    }

    function create_room()
    {
        if($this->input->get('inputName')!='' && $this->input->get('inputPassword')!='')
        {
            $name = $this->input->get('inputName');
            $pass = md5($this->input->get('inputPassword'));

            $cek = $this->db->get_where('room', array('nama' => $name));
            if ($cek->num_rows() > 0)
            {
                return 0;
            }
            else
            {
                $data = array(
                    'nama' => $name ,
                    'password' => $pass 
                    );
                $this->db->insert('room', $data);
                return 1;
            } 

        }
        else
        {
            return 0;
        }
    }

    function get_speaker($id_room)
    {
        $string = "select username from user where id_room = (select id from room where nama = '".$id_room."') and status = 2";
        $query = $this->db->query($string);
        return $query;
    }

    function get_room($room, $password)
    {
        $array = array('nama =' => $room, 'password =' => $password);
        $this->db->where($array);
        $query = $this->db->get('room');
        if ($query->num_rows() > 0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

}
