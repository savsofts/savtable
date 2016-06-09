<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {


	public function index()
	{
		
		
		
		$this->load->library('savtable');
		
		$data['table_list'] = $this->savtable->table_list('mysql_table_name_here');
		
		$this->load->view('table_list',$data);
	}
	
	
	
	
	
}
