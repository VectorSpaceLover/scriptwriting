<?php
include_once 'config.php';
session_start();


$company=$_GET['company'];

$aColumns_output = array('userprofile.username','userprofile.name','userprofile.email','userprofile.email_verify','users_type.title','userprofile.id','userprofile.user_type');
$aColumns_order = array('userprofile.username','userprofile.name','userprofile.email','userprofile.email_verify','users_type.title','userprofile.id','userprofile.user_type');
$aColumns_where = array('userprofile.username','userprofile.name','userprofile.email','userprofile.email_verify','users_type.title','userprofile.id','userprofile.user_type');
$sIndexColumn = "userprofile.id";
$sTable = "userprofile";
 $sJoin = "left join login  on userprofile.user_id=login.id left join users_type on userprofile.user_type=users_type.id ";
$sLimit = "";
if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	$sLimit = "LIMIT ".mysqli_real_escape_string($con,$_GET['iDisplayStart']).", ".mysqli_real_escape_string($con,$_GET['iDisplayLength']);
if ( isset( $_GET['iSortCol_0'] ) )
{
	$sOrder = "ORDER BY  ";
	for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
	{
		if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			$sOrder .= $aColumns_order[ intval( $_GET['iSortCol_'.$i] ) ]." ".mysqli_real_escape_string($con,$_GET['sSortDir_'.$i]) .", ";
	}
	$sOrder = substr_replace( $sOrder, "", -2 );
	if ( $sOrder == "ORDER BY" )
		$sOrder = "";
}
$sWhere = " where login.business_name ='".$company."'";
if ( $_GET['sSearch'] != "" )
{
	$sWhere .= " and (";
	for ( $i=0 ; $i<count($aColumns_where) ; $i++ )
		$sWhere .= $aColumns_where[$i]." LIKE '%".mysqli_real_escape_string($con,$_GET['sSearch'])."%' OR ";
	$sWhere = substr_replace( $sWhere, "", -3 );
	$sWhere .= ')';
}
for ( $i=0 ; $i<count($aColumns_where) ; $i++ )
{
	if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
	{
		if ( $sWhere == "" )
			$sWhere = "WHERE ";
		else
			$sWhere .= " AND ";
		$sWhere .= $aColumns_where[$i]." LIKE '%".mysqli_real_escape_string($con,$_GET['sSearch_'.$i])."%' ";
	}
}
//$sWhere .= ($sWhere ? ' AND ' : ' WHERE ') . 'login.business_name = ' . $company;
$sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns_output))." FROM   $sTable $sJoin $sWhere $sOrder $sLimit ";
$sQuery=stripslashes($sQuery);
//echo $sQuery;
$rResult = mysqli_query($con,$sQuery);

$sQuery = "SELECT FOUND_ROWS() as cnt";
$rResultFilterTotal = mysqli_query($con,$sQuery);
$aResultFilterTotal = mysqli_fetch_assoc($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal['cnt'];
$sQuery = "SELECT COUNT(".$sIndexColumn.") as cnt FROM  $sTable left join login on userprofile.id=login.id where login.business_name ='".$company."'";

$rResultTotal = mysqli_query($con,$sQuery);
$aResultTotal = mysqli_fetch_assoc($rResultTotal);
$iTotal = $aResultTotal['cnt'];
$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
);
$sno=1+$_GET['iDisplayStart'];
while ($aRow=mysqli_fetch_assoc($rResult))
{
	$row = array();
	$row[] = $aRow['username'];
	$row[] = $aRow['name'];
	$row[] = $aRow['email'];
	
	if($aRow['email_verify'] == 1)
		$row[]='<span class="text-success">Email Verified</span>';
	else 
		$row[]='<span class="text-danger">Not Verified</span>';
	
	$row[] = $aRow['title'];
	
	$s=' ';
	if($_GET['usertype']==1){
	$s.='<div style="display:flex"><button class="btn btn-primary edit_user" id="'.$aRow['id'].'"><i class="fa fa-edit"></i></button>';
	$s.=' &nbsp;<button class="btn btn-danger del_user" id="'.$aRow['id'].'"><i class="fa fa-trash"></i></button></div>';
	}
    else if($_POST['usertype']==1){
	$s.='<div style="display:flex"><button class="btn btn-primary edit_user" id="'.$aRow['id'].'"><i class="fa fa-edit"></i></button>';
	$s.=' &nbsp;<button class="btn btn-danger del_user" id="'.$aRow['id'].'"><i class="fa fa-trash"></i></button></div>';
	} 
    
    
    
     
                                            
		
	$row[]=$s;
	$output['aaData'][] = $row;
	$sno++;
}
echo json_encode($output);
?>



