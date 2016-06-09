<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table_p extends CI_Controller {
	
	function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	 }
	 
	 
	public function update_row()
	{
		/*
		| add security validation here.
		| like authorized session
		*/
		
		
		
	 $table_name=$this->input->post('table_name');
	 $uid=$this->input->post('uid');
	 $ucol=$this->input->post('ucol');
	 $value=$this->input->post('value');
	 $column=$this->input->post('column');
	 
	if($this->db->query("update $table_name set $column='$value' where $ucol='$uid' ")){
		echo "Data updated successfully!";
	}else{
		echo "Unable to update data!";
	}
	
	}
	
	public function update_row2()
	{
		/*
		| add security validation here.
		| like authorized session
		*/
		
		
		
	 $table_name=$this->input->post('table_name');
	 $uid=$this->input->post('uid');
	 $ucol=$this->input->post('ucol');
	 $value=$this->input->post('value');
	 $column=$this->input->post('column');
	$j1=$this->input->post('j1');
	$j2=$this->input->post('j2');
	$base_table=$this->input->post('base_table');
	$query=$this->db->query("select * from $table_name where $column='$value' ");
	 if($query->num_rows()>=1){
		 $jrow=$query->row_array();
	 $aid=$jrow[$this->getcolumn($j2)];
	 }else{
		$userdata[$column]=$value;
		$this->db->insert($table_name,$userdata);
		$aid=$this->db->insert_id();
 		$_SESSION['extra_row_table']=$table_name;
		$_SESSION['extra_row_aid']=$aid;
		$fields=$this->db->list_fields($table_name);
		$_SESSION['extra_row_ucol']=$fields[0];
		
	 }
	if($this->db->query("update $base_table set $j1='$aid' where $ucol='$uid' ")){
		echo "Data updated successfully!";
	}else{
		echo "Unable to update data!";
	}
	
	}
	
	public function suglist(){
		$data="";
		$id=$this->input->post('id');
		$stable=$this->input->post('stable');
		$scol=$this->input->post('scol');
		$value=$this->input->post('value');
		$this->db->limit(5);
		$this->db->like($scol,$value);
		$query=$this->db->get($stable);
	 if($query->num_rows()>=1){
		$result=$query->result_array();
			foreach($result as $r => $rval){
					$data.="<div><a href=javascript:putsug('".$id."','".$rval[$scol]."'); >".$rval[$scol]."</a></div>";
			}
		echo $data;
	 }else{
		 
	 }
		
	}
	
	function remove_extra(){
		
		if(isset($_SESSION['extra_row_aid']) && $_SESSION['extra_row_aid'] >=1){
			$aid=$_SESSION['extra_row_aid'];
			$table_name=$_SESSION['extra_row_table'];
			$ucol=$_SESSION['extra_row_ucol'];
			$this->db->where($ucol,$aid);
			$this->db->delete($table_name);
			$_SESSION['extra_row_aid']=0;
			unset($_SESSION['extra_row_aid']);
			unset($_SESSION['extra_row_table']);
			unset($_SESSION['extra_row_ucol']);
			
		}
		
	}
	
	
	public function delete_row()
	{
		/*
		| add security validation here.
		| like authorized session
		*/
		
	 $table_name=$this->input->post('table_name');
	 $uid=$this->input->post('uid');
	 $ucol=$this->input->post('ucol');
	
	 
	if($this->db->query("delete from $table_name  where $ucol='$uid' ")){
		echo "Row deleted successfully!";
	}else{
		echo "Unable to delete row!";
	}
	
	}
	
	
	public function insert_row($table_name,$ucol){
		/*
		| add security validation here.
		| like authorized session
		*/
		
		
		/*
		// don't insert first column
		if(isset($_POST[$ucol])){
		unset($_POST[$ucol]);
		}
		*/
		$this->db->insert($table_name,$_POST);
	
	}
	
	
	public function paging($limit){
		if($limit < 0){
			$limit=0;
		}
		$_SESSION['table_p_limit']=$limit;
		redirect($_SESSION['table_p_url']);
		
	}
	
	
		public function sorting($sortby){
		
		 
		 if(isset($_SESSION['order_by']) && $_SESSION['order_by']=="ASC"){
		$_SESSION['order_by']="DESC";
		 }else if(isset($_SESSION['order_by']) && $_SESSION['order_by']=="DESC"){
		$_SESSION['order_by']="ASC";
		 }else if(!isset($_SESSION['order_by'])){
		$_SESSION['order_by']="ASC";
		 }
		
		 $_SESSION['order_col']=$sortby;
		redirect($_SESSION['table_p_url']);
		
	}
	
	
	
	
	
		public function getcolumn($fields){
		
		if(is_array($fields)){
		$fca=array();

			foreach($fields as $fg => $field){
			$fc=explode('.',$field);
			if(isset($fc[1])){
				$fca[]=$fc[1];	
			}else{
				$fca[]=$fc[0];	
			}				
			}
			return $fca;
		}else{
			
		$fc=explode('.',$fields);
			if(isset($fc[1])){
				return $fc[1];	
			}else{
				return $fc[0];	
			}
		}
		
		
	}
	
	
}
