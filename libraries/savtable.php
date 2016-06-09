<?php 
/**
 * savTable is a codeigniter library to generate table list with less effort.
 * Automatically create insert, edit and delete functionality
 * www.savtable.com
 */
 
class Savtable {
	
	 private $CI;
  function __construct() {
$this->CI =& get_instance();
$this->CI->load->database();
 
   }

   
function table_list($table_name='', $attr=''){
	
	$delete_heading="Delete";
	$delete_btn="Delete";
	$contenteditable="contenteditable='true'";
	
	
	
	
	
	if(isset($attr['contenteditable'])){
		$contenteditable="contenteditable='".$attr['contenteditable']."'";
	} 
$_SESSION['table_p_url']=site_url(uri_string());
$table_data="<form method='post' action='#' id='table_p_form'>";

// table css class
if(isset($attr['table_class_name'])){
$table_class_name="class='".$attr['table_class_name']."'";	
}else{
$table_class_name="class='table_p_default'";	
}


// selected column
if(isset($attr['column'])){
$column=implode(',',$attr['column']);	
}else{
$column='*';	
}

// limit, default 0
if(isset($attr['limit'])){
$limit=$attr['limit'];	
}else{
	if(isset($_SESSION['table_p_limit'])){
		$limit=$_SESSION['table_p_limit'];
	}else{
		$limit='0';	
	}

}

// number of record per page, default 30
if(isset($attr['nor'])){
$nor=$attr['nor'];	
}else{
$nor='30';	
}


// join
$join="";
if(isset($attr['join'])){
	foreach($attr['join'] as $jk => $joins){
		if(isset($joins[2])){
		$join.=" ".$joins[2]." join ".$joins[0]." on ".$joins[1]." ";		
		}else{
		$join.=" join ".$joins[0]." on ".$joins[1]." ";	
		}


	}
}else{
$join='';	
}


// where condition with and
if(isset($attr['where'])){
if(is_array($attr['where'])){
$where=" where ".implode(' and ',$attr['where']);	
}else{
$where=" where ".$attr['where'];	
}
}else{
$where='';	
}





// where condition with or
if(isset($attr['where_or'])){
if(is_array($attr['where_or'])){
$where=" where ".implode(' or ',$attr['where_or']);	
}else{
$where=" where ".$attr['where_or'];	
}
}else{
$where='';	
}






$extra="";
// extra conditions
if(isset($attr['extra'])){
$extra=$attr['extra'];	
}






$orderby="";

if(isset($_SESSION['order_by']) && isset($_SESSION['order_col'])){
	
	$orderby=" order by ".$_SESSION['order_col']." ".$_SESSION['order_by']."";
}

$result_column=array();
$table_name_arr=array();
$table_name_arr_join=array();

	if($column=="*"){
$fields = $this->CI->db->list_fields($table_name);
	for($tk=0; $tk < count($fields); $tk++){
		$table_name_arr[$tk]=$table_name;	
	}
	 
	
	 
if(isset($attr['join'])){
	 
	foreach($attr['join'] as $jk => $joins){
		
	$fields=array_merge($fields,$this->CI->db->list_fields($joins[0]));
	$fields=array_unique($fields);
	$fields_j=$this->CI->db->list_fields($joins[0]);
	  
	for($tki=$tk; $tki < (count($fields_j)+$tk); $tki++){
		$table_name_arr[$tki]=$joins[0];
 	 
	 $table_name_arr_join[$tki]=explode('=',$joins[1]);
	}
}


}


	}else{
	$fields = $attr['column'];
	$in_fields=$this->CI->db->list_fields($table_name);
	$tk=0;
	foreach($in_fields as $fkk => $fval){
		if(in_array($fval,$this->getcolumn($fields))){
		$table_name_arr[$tk]=$table_name;
		$tk+=1;		
		}
	}
	

	 
	
	 
if(isset($attr['join'])){
	 
	foreach($attr['join'] as $jk => $joins){
		
	 $in_fields=$this->CI->db->list_fields($joins[0]);
	  
		foreach($in_fields as $fkk => $fval){
		if(in_array($fval,$this->getcolumn($fields))){
		$table_name_arr[$tk]=$joins[0];
		$table_name_arr_join[$tk]=explode('=',$joins[1]);
		$tk+=1;		
		}
}


}
}
	
	
	
		
	}
	    
	    
	  
	if($column=="*"){
		foreach ($fields as $field)
			{
			  $result_column[]=ucfirst(str_replace('_',' ',$this->getcolumn($field)));
			}

	}else{
		
		foreach ($fields as $field)
			{
				if(in_array($field,$attr['column'])){
					$result_column[]=ucfirst(str_replace('_',' ',$this->getcolumn($field)));
				}
			}				
	}
	
$first_column=$fields[0];
if(isset($attr['column'])){
	if(!in_array($first_column,$attr['column'])){	
$column=$first_column.",".$column;
}
}

if(!isset($attr['query'])){
$qr2="select $column from  $table_name $join $where $extra";
}else{
$qr2=$attr['query'];	
}

$query2=$this->CI->db->query($qr2);
$total_row=$query2->num_rows();



if(!isset($attr['query'])){
$qr="select $column from $table_name $join $where $extra $orderby limit $limit,$nor  ";
}else{
$qr=$attr['query'];	
}
$query=$this->CI->db->query($qr);
$result_row=$query->result_array();
 


 


if($table_class_name=="class='table_p_default'"){
	$table_data.="<style> .table_p_default th, td{	padding:4px; border:1px solid #666666; }</style>";
}
$table_data.="<style> .suglist{	display:none; position:fixed;z-index:100;padding:5px;top:0px;left:0px;background:#eeeeee;min-width:150px;border:1px solid #dddddd; }</style>";
$table_data.="<div id='table_p_message'></div><table ".$table_class_name." ><tr>";
$cj=0;
foreach($result_column as $rc => $value){
	$table_data.="<th><a href='".site_url('table_p/sorting/'.lcfirst(str_replace(' ','_',$value)))."'>".$value."</a></th>";
	$cj+=1;
}
$table_data.="<th>".$delete_heading."</th></tr>";
 if($query->num_rows()==0){
 $table_data.="<tr><td colspan='".($cj+1)."'>No Record Found!</td></tr>";
 
 }else{
foreach($result_row as $rr => $row){
	$table_data.="<tr>";
	
	if($column=="*"){
		$kl=0;
		foreach($row as $rr2 => $value){
		if($table_name_arr[$kl]==$table_name){
		$table_data.="<td  id='".$table_name_arr[$kl]."--".$row[$fields[0]]."--edit--".$rr2."--".$fields[0]."' ".$contenteditable." onBlur='actionrow(this.id);'>".$value."</td>";
		}else{
		$table_data.="<td  id='".$table_name_arr[$kl]."--".$row[$fields[0]]."--edit2--".$rr2."--".$fields[0]."--".$table_name_arr_join[$kl][0]."--".$table_name_arr_join[$kl][1]."--".$table_name."--".rand(11111,99999)."'  ".$contenteditable." onBlur='actionrow(this.id);'    onKeyup=getsug('".$table_name_arr[$kl]."','".$rr2."',this.id) >".$value."</td>";
		}
		$kl+=1;
	}
	
	}else{
		$kl=0;
	foreach($row as $rr2 => $value){
		if(in_array($rr2,$this->getcolumn($attr['column']))){
		if($table_name_arr[$kl]==$table_name){
			$table_data.="<td  id='".$table_name_arr[$kl]."--".$row[$this->getcolumn($fields[0])]."--edit--".$rr2."--".$fields[0]."'  ".$contenteditable." onBlur='actionrow(this.id);'>".$value."</td>";
		}else{
			$table_data.="<td  id='".$table_name_arr[$kl]."--".$row[$this->getcolumn($fields[0])]."--edit2--".$rr2."--".$fields[0]."--".$table_name_arr_join[$kl][0]."--".$table_name_arr_join[$kl][1]."--".$table_name."--".rand(11111,99999)."'  ".$contenteditable."   onBlur='actionrow(this.id);'    onKeyup=getsug('".$table_name_arr[$kl]."','".$rr2."',this.id)  >".$value."</td>";
		}
		$kl+=1;
		}
		
	}
	
	}
	
	$table_data.="<td><a   id='".$table_name."--".$row[$this->getcolumn($fields[0])]."--delete--".$fields[0]."' href='#' onClick='actionrow(this.id);'>".$delete_btn."</a></td></tr>";	
}
 
$table_data.="<tr>";
	if($column=="*"){
		foreach($row as $rr2 => $value){
	$table_data.="<td  ><input type='text' name='".$rr2."'></td>";
	}
	
	}else{
	foreach($row as $rr2 => $value){
		if(in_array($rr2,$this->getcolumn($attr['column']))){
		$table_data.="<td  ><input type='text' name='".$rr2."'></td>";
		}	
	}	
	}
	$table_data.="<td><input type='button' value='Insert' id='".$table_name."'  onClick=insertrow(this.id,'".$first_column."');  ></td></tr>";	
 
 } 
$table_data.="</table></form><span style='font-size:11px;'>Generated by <a href='http://savtable.com' >savTable</a></span><br><br><div class='suglist' id='suglist'></div>";
$numbering="";
for($i=0; $i < intval($total_row/$nor); $i++){
	
	$numbering.="<a href='".site_url('table_p/paging/'.($i*$nor))."'  >".($i+1)."</a> &nbsp;";
	
}
$table_data.="<a href='".site_url('table_p/paging/'.($limit-$nor))."'  >Back</a> &nbsp;&nbsp;".$numbering."&nbsp;&nbsp; <a href='".site_url('table_p/paging/'.($limit+$nor))."'    >Next</a>";

$table_data.="<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js'></script>
<script>
var p1=0;
var p2=0;

$( document ).on( 'click', function( event ) {
p2=event.pageX;
p1= event.pageY;
});

function getsug(stable,scol,id){
	var pid=document.getElementById(id);
var topp=(parseInt(p1)+20)+'px';
var leftp=(parseInt(p2)-20)+'px';
  
	
var split_id=id.split('--');
var suglist='#suglist';
var value=document.getElementById(id).innerHTML;
		var formData = {stable:stable,scol:scol,value:value,id:id};
		$.ajax({
			 type: 'POST',
			 data : formData,
				url: '".site_url()."/table_p/suglist/',
			success: function(data){
			$(suglist).html(data);
			$(suglist).css('display','block');
			$(suglist).css('top',topp);
			$(suglist).css('left',leftp);
			  	
				},
			error: function(xhr,status,strErr){
				//alert(status);
				}	
			});		
	
}



function putsug(id,val){
	document.getElementById(id).innerHTML=val;
	var suglist='#suglist';
	$(suglist).html('');
	$(suglist).css('display','none');
	
		var formData = {id:id};
		$.ajax({
			 type: 'POST',
			 data : formData,
				url: '".site_url()."/table_p/remove_extra/',
			success: function(data){
			 
				
				},
			error: function(xhr,status,strErr){
				//alert(status);
				}	
			});
			
			
}



function actionrow(id){
	 
	var split_id=id.split('--');
	if(split_id[2]=='edit'){
		var ucol=split_id[4];
		var uid=split_id[1];
		var value=document.getElementById(id).innerHTML;
		var table_name=split_id[0];
		var column=split_id[3];
		var formData = {pdata:id,ucol:ucol,value:value,table_name:table_name,uid:uid,column:column};
		$.ajax({
			 type: 'POST',
			 data : formData,
				url: '".site_url()."/table_p/update_row/',
			success: function(data){
			$('#table_p_message').html(data);
				
				},
			error: function(xhr,status,strErr){
				//alert(status);
				}	
			});
		
	}else if(split_id[2]=='edit2'){
		var ucol=split_id[4];
		var j1=split_id[5];
		var j2=split_id[6];
		var base_table=split_id[7];
		var uid=split_id[1];
		var value=document.getElementById(id).innerHTML;
		var table_name=split_id[0];
		var column=split_id[3];
		var formData = {pdata:id,ucol:ucol,value:value,table_name:table_name,uid:uid,column:column,base_table:base_table,j1:j1,j2:j2};
		$.ajax({
			 type: 'POST',
			 data : formData,
				url: '".site_url()."/table_p/update_row2/',
			success: function(data){
			$('#table_p_message').html(data);
			
					var suglist='#suglist';
					$(suglist).html('');
					$(suglist).css('display','none');
	
	
				},
			error: function(xhr,status,strErr){
				//alert(status);
				}	
			});
		
	}else{
		if(confirm('Do you really want to delete this row?')){
		var ucol=split_id[3];
		var uid=split_id[1];
		var table_name=split_id[0];

		var formData = {pdata:id,ucol:ucol,table_name:table_name,uid:uid};
		$.ajax({
			 type: 'POST',
			 data : formData,
				url: '".site_url()."/table_p/delete_row/',
			success: function(data){
			location.reload();
			
				},
			error: function(xhr,status,strErr){
				//alert(status);
				}	
			});
	}
	}
	
		
}

function insertrow(id,first_column){
	var table_name=id;
	var str = $('#table_p_form').serialize();
	 
		$.ajax({
			 type: 'POST',
			 data : str,
				url: '".site_url()."/table_p/insert_row/'+table_name+'/'+first_column,
			success: function(data){
			location.reload();
			
				},
			error: function(xhr,status,strErr){
				//alert(status);
				}	
			});	
}
</script>";

return $table_data;
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






























?>