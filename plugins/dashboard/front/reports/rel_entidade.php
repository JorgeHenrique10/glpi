<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");
include "../inc/functions.php";

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(!empty($_POST['submit']))
{
    $data_ini =  $_POST['date1'];
    $data_fin = $_POST['date2'];
}

else {
    $data_ini = date("Y-m-01");
    $data_fin = date("Y-m-d");
}

if(!isset($_POST["sel_ent"])) {
	$id_ent = $_REQUEST["sel_ent"];	
}

else {
	$id_ent = $_POST["sel_ent"];
}

?>

<html>
<head>
<title> GLPI - <?php echo __('Tickets', 'dashboard') .'  '. __('by Entity', 'dashboard') ?> </title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />
<meta charset="utf-8">

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />

<script language="javascript" src="../js/jquery.js"></script>

<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>

<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">

<script src="../js/media/js/jquery.dataTables.min.js"></script>
<script src="../js/media/js/dataTables.bootstrap.js"></script>
<link href="../js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />

<script src="../js/extensions/Select/js/dataTables.select.min.js"></script>
<link href="../js/extensions/Select/css/select.bootstrap.css" type="text/css" rel="stylesheet" />

<script src="../js/extensions/FixedHeader/js/dataTables.fixedHeader.min.js"></script>
<link href="../js/extensions/FixedHeader/css/fixedHeader.dataTables.min.css" type="text/css" rel="stylesheet" />
<link href="../js/extensions/FixedHeader/css/fixedHeader.bootstrap.min.css" type="text/css" rel="stylesheet" />

<script src="../js/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.html5.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.print.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.colVis.min.js"></script>
<script src="../js/media/pdfmake.min.js"></script>
<script src="../js/media/vfs_fonts.js"></script>
<script src="../js/media/jszip.min.js"></script>


<style type="text/css">
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>

</head>

<body style="background-color: #e5e5e5; margin-left:0%;">

<div id='content' >
<div id='container-fluid' style="margin: <?php echo margins(); ?> ;">
	<div id="charts" class="fluid chart">
		<div id="pad-wrapper" >
			<div id="head-lg" class="fluid">
				<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
				    <div id="titulo_rel"> <?php echo __('Tickets', 'dashboard') .'  '. __('by Entity', 'dashboard') ?> </div>
						    <div id="datas-tec" class="col-md-12 col-sm-12 fluid" >
							    <form id="form1" name="form1" class="form_rel" method="post" action="rel_entidade.php?con=1">
								    <table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef" >
								    <tr>
										<td style="width: 310px;">
										<?php
										$url = $_SERVER['REQUEST_URI'];
										$arr_url = explode("?", $url);
										$url2 = $arr_url[0];

										echo'
											<table style="margin-top:0px;" border=0>
												<tr>
													<td>
													   <div class="input-group date" id="dp1" data-date="'.$data_ini.'" data-date-format="yyyy-mm-dd">
													    	<input class="col-md-9 form-control" size="13" type="text" name="date1" value="'.$data_ini.'" >
													    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
												    	</div>
													</td>
													<td>&nbsp;</td>
													<td>
												   	<div class="input-group date" id="dp2" data-date="'.$data_fin.'" data-date-format="yyyy-mm-dd">
													    	<input class="col-md-9 form-control" size="13" type="text" name="date2" value="'.$data_fin.'" >
													    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>
												    	</div>
													</td>
													<td>&nbsp;</td>
												</tr>
											</table> ';
										?>

										<script language="Javascript">
											$('#dp1').datepicker('update');
											$('#dp2').datepicker('update');
										</script>
										</td>

										<td style="margin-top:2px;">
										<?php

										//seleciona entidade
										$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
										$result_e = $DB->query($sql_e);
										$sel_ent = $DB->result($result_e,0,'value');

										if($sel_ent == '' || $sel_ent == -1) {
											$entities = $_SESSION['glpiactiveentities'];
											$ents = implode(",",$entities);
										}
										else {
											$ents = $sel_ent;
										}
										
										$user_ents = Profile_User::getUserEntities($_SESSION['glpiID'], true);								
				
										//lista de entidades
										$sql_ent = "
										SELECT id, name, completename AS cname
										FROM `glpi_entities`
										WHERE id IN (".$ents.")
										ORDER BY `name` ASC ";

										$result_ent = $DB->query($sql_ent);

										$arr_ent = array();
										$arr_ent[-1] = "-- ". __('Select a entity', 'dashboard') . " --" ;
										$arr_ent[0] = __('All');

										//$DB->data_seek($result_ent, 0) ;
										while ($row_result = $DB->fetch_assoc($result_ent)) {
										   $v_row_result = $row_result['id'];
										   $arr_ent[$v_row_result] = $row_result['cname'] ;
										}

										$name = 'sel_ent';
										$options = $arr_ent;
										$selected = $id_ent;

										echo dropdown( $name, $options, $selected );

										?>
										</td>
									</tr>
									<tr><td height="15px"></td></tr>
									<tr>
										<td colspan="2" align="center">
											<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?> </button>
											<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" ><i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button>
										</td>
									</tr>
								    </table>
							<?php Html::closeForm(); ?>
						<!-- </form> -->
			         </div>
			    </div>


		<?php

		//entidades
		if(isset($_REQUEST['con'])) {
			$con = $_REQUEST['con'];
		}
		else { $con = ''; }

		if($con == "1") {

		if(!isset($_POST['date1']))
		{
		    $data_ini2 = $_GET['date1'];
		    $data_fin2 = $_GET['date2'];
		}

		else {
		    $data_ini2 = $_POST['date1'];
		    $data_fin2 = $_POST['date2'];
		}


		//entity
		if(!isset($_REQUEST["sel_ent"]) || $_REQUEST["sel_ent"] == 0 || $_REQUEST["sel_ent"] == "" ) 
		{ 
			if(in_array(0,$user_ents)) {
				$id_ent = 0 ;
				$entidade = '';
			}
			else {			
				$id_ent = implode(',',$_SESSION['glpiactiveentities']); 
		   	$entidade = "AND glpi_tickets.entities_id IN (".$id_ent.")";
		   }	
		}
		
		else { 
			$id_ent = $_REQUEST["sel_ent"]; 
			$entidade = "AND glpi_tickets.entities_id IN (".$id_ent.") ";
		}


		//dates
		if($data_ini2 == $data_fin2) {
			$datas2 = "LIKE '".$data_ini2."%'";
		}

		else {
			$datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";
		}

		//status
		$status = "";
		$status_open = "('2','1','3','4')";
		$status_close = "('5','6')";
		$status_all = "('2','1','3','4','5','6','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28')";

		if(isset($_GET['stat'])) {

		    if($_GET['stat'] == "open") {
		        $status = $status_open;
		    }
		    elseif($_GET['stat'] == "close") {
		        $status = $status_close;
		    }
		    else {
		    	$status = $status_all;
		    }
		}

		else {
		    	$status = $status_all;
		    }

		// Chamados
		$sql_cham =
		"SELECT id, name AS descr, date, closedate, solvedate, status , actiontime AS act, itilcategories_id AS cat, TYPE, FROM_UNIXTIME( UNIX_TIMESTAMP( `glpi_tickets`.`solvedate` ) , '%Y-%m' ) AS date_unix, glpi_tickets.solve_delay_stat AS time_sec
		FROM glpi_tickets
		WHERE is_deleted = 0
		".$entidade."
		AND date ".$datas2."
		AND status IN ".$status."
		ORDER BY id DESC ";

		$result_cham = $DB->query($sql_cham);


		$consulta1 =
		"SELECT glpi_tickets.id AS total
		FROM glpi_tickets
		WHERE glpi_tickets.is_deleted = 0
		".$entidade."
		AND glpi_tickets.date ".$datas2."
		AND glpi_tickets.status IN ".$status." ";

		$result_cons1 = $DB->query($consulta1);

		$conta_cons = $DB->numrows($result_cons1);
		$consulta = $conta_cons;
		

		if($consulta > 0) {

		//montar barra
		$sql_ab = "SELECT glpi_tickets.id AS total
		FROM glpi_tickets
		WHERE glpi_tickets.is_deleted = 0
		".$entidade."
		AND glpi_tickets.date ".$datas2."
		AND glpi_tickets.status IN ".$status_open ;

		$result_ab = $DB->query($sql_ab) or die ("erro_ab");
		$data_ab = $DB->numrows($result_ab);

		$abertos = $data_ab;

		//barra de porcentagem
		if($conta_cons > 0) {
	
			//barra de porcentagem
			if($status == $status_close ) {
			    $barra = 100;
			    $cor = "progress-bar-success";
			}	
	
			else {
				//porcentagem
				$perc = round(($abertos*100)/$conta_cons,1);
				$barra = 100 - $perc;
		
				// cor barra
				if($barra == 100) { $cor = "progress-bar-success"; }
				if($barra >= 80 and $barra < 100) { $cor = " "; }
				if($barra > 51 and $barra < 80) { $cor = "progress-bar-warning"; }
				if($barra > 0 and $barra <= 50) { $cor = "progress-bar-danger"; }
				if($barra < 0) { $cor = "progress-bar-danger"; $barra = 0; }		
			}		
		}
		else { $barra = 0;}

		// nome da entidade
		$sql_nm = "
		SELECT id , name AS name, entities_id
		FROM `glpi_entities`
		WHERE id = ".$id_ent."";

		$result_nm = $DB->query($sql_nm);
		$ent_name = $DB->fetch_assoc($result_nm);

		//total time		
		$total_time = '';
		while($row = $DB->fetch_assoc($result_cham)){

		$sql = "SELECT ( TIMESTAMPDIFF(SECOND , date, solvedate ) ) AS time FROM glpi_tickets WHERE id = ".$row['id']." ";
		$result = $DB->query($sql);

			while($row_t = $DB->fetch_assoc($result)) {

				if($row_t['time'] >= 86400) {
					$total_time += ($row_t['time'] / 3) ;
				}
				else {
					$total_time += ($row_t['time']);
				}
			}
		}
		

		//count by status
		$query_stat = "
		SELECT
		SUM(case when glpi_tickets.status = 1 then 1 else 0 end) AS new,
		SUM(case when glpi_tickets.status = 2 then 1 else 0 end) AS assig,
		SUM(case when glpi_tickets.status = 3 then 1 else 0 end) AS plan,
		SUM(case when glpi_tickets.status = 4 then 1 else 0 end) AS pend,
		SUM(case when glpi_tickets.status = 5 then 1 else 0 end) AS solve,
		SUM(case when glpi_tickets.status = 6 then 1 else 0 end) AS close,
		SUM(case when glpi_tickets.status = 13 then 1 else 0 end) AS validacao_tr,
		SUM(case when glpi_tickets.status = 14 then 1 else 0 end) AS publicacao,
		SUM(case when glpi_tickets.status = 15 then 1 else 0 end) AS parecer_habilitacao,
		SUM(case when glpi_tickets.status = 16 then 1 else 0 end) AS validacao_tecnica,
		SUM(case when glpi_tickets.status = 17 then 1 else 0 end) AS resultados,
		SUM(case when glpi_tickets.status = 18 then 1 else 0 end) AS homologacao,
		SUM(case when glpi_tickets.status = 19 then 1 else 0 end) AS juridico,
		SUM(case when glpi_tickets.status = 20 then 1 else 0 end) AS validacao_interna,
		SUM(case when glpi_tickets.status = 21 then 1 else 0 end) AS envio_contrato,
		SUM(case when glpi_tickets.status = 22 then 1 else 0 end) AS formalizacao,
		SUM(case when glpi_tickets.status = 23 then 1 else 0 end) AS atribuido,
		SUM(case when glpi_tickets.status = 24 then 1 else 0 end) AS pendente_unidade,
		SUM(case when glpi_tickets.status = 25 then 1 else 0 end) AS publicacao_errata,
		SUM(case when glpi_tickets.status = 26 then 1 else 0 end) AS prorrogacao,
		SUM(case when glpi_tickets.status = 27 then 1 else 0 end) AS diligencia,
		SUM(case when glpi_tickets.status = 28 then 1 else 0 end) AS recurso
		FROM glpi_tickets
		WHERE glpi_tickets.is_deleted = 0
		".$entidade."
		AND glpi_tickets.date ".$datas2." ";

		$result_stat = $DB->query($query_stat);

	    $new = $DB->result($result_stat,0,'new') + 0;
	    $assig = $DB->result($result_stat,0,'assig') + 0;
	    $plan = $DB->result($result_stat,0,'plan') + 0;
	    $pend = $DB->result($result_stat,0,'pend') + 0;
	    $solve = $DB->result($result_stat,0,'solve') + 0;
	    $close = $DB->result($result_stat,0,'close') + 0;
	    $validacao_tr = $DB->result($result_stat,0,'validacao_tr') + 0;
	    $publicacao = $DB->result($result_stat,0,'publicacao') + 0;
	    $parecer_habilitacao = $DB->result($result_stat,0,'parecer_habilitacao') + 0;
	    $validacao_tecnica = $DB->result($result_stat,0,'validacao_tecnica') + 0;
	    $resultados = $DB->result($result_stat,0,'resultados') + 0;
	    $homologacao = $DB->result($result_stat,0,'homologacao') + 0;
	    $juridico = $DB->result($result_stat,0,'juridico') + 0;
	    $validacao_interna = $DB->result($result_stat,0,'validacao_interna') + 0;
	    $envio_contrato = $DB->result($result_stat,0,'envio_contrato') + 0;
	    $formalizacao = $DB->result($result_stat,0,'formalizacao') + 0;
	    $atribuido = $DB->result($result_stat,0,'atribuido') + 0;
		$pendente_unidade = $DB->result($result_stat,0,'pendente_unidade') + 0;
		$publicacao_errata = $DB->result($result_stat,0,'publicacao_errata') + 0;
		$prorrogacao = $DB->result($result_stat,0,'prorrogacao') + 0;
		$diligencia = $DB->result($result_stat,0,'diligencia') + 0;
		$recurso = $DB->result($result_stat,0,'recurso') + 0;


		//listar chamados
		echo "
		<div class='well info_box fluid col-md-12 col-sm-12 report' style='margin-left: -1px;'>

		<table class='fluid'  style='width:100%; font-size: 18px; font-weight:bold;' cellpadding = '1px'>
			<tr>
				<td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Entity', 'dashboard').": </span>".$ent_name['name']." </td>
			</tr>
			<tr>
				<td style='font-size: 16px; font-weight:bold; vertical-align:middle; width:180px;'><span style='color:#000;'> ".__('Tickets', 'dashboard').": </span>".$consulta." </td>
				<td colspan='3' style='font-size: 16px; vertical-align:middle; width:200px;'><span style='color:#000;'>
				".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)."
				</td>
				<td style='vertical-align:middle; width: 190px;'>
					<div class='progress' style='margin-top: 19px;'>
						<div class='progress-bar ". $cor ." ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
		    				".$barra." % ".__('Closed', 'dashboard') ."
		    			</div>
					</div>
				</td>
			</tr>
		</table>

		<table align='right' style='margin-bottom:10px;'>
			<tr>
				<td>
					<button class='btn btn-primary btn-sm' type='button' name='abertos' value='Abertos' onclick='location.href=\"rel_entidade.php?con=1&stat=open&sel_ent=".$id_ent."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('Opened', 'dashboard') ." </button>
					<button class='btn btn-primary btn-sm' type='button' name='fechados' value='Fechados' onclick='location.href=\"rel_entidade.php?con=1&stat=close&sel_ent=".$id_ent."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('Closed', 'dashboard')." </button>
					<button class='btn btn-primary btn-sm' type='button' name='todos' value='Todos' onclick='location.href=\"rel_entidade.php?con=1&stat=all&sel_ent=".$id_ent."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('All', 'dashboard')." </button>
				</td>
			</tr>
		</table>

		<table style='font-size: 16px; width: 100%;' border=0>
			<tr>
				<td style='font-weight:bold;'><span style='color: #000;'>". _x('status','New').": </span>".$new." </td>
				<td style='font-weight:bold;'><span style='color: #000;'>". __('Assigned'). ": </span>". ($assig + $plan) ."</td>
				<td style='font-weight:bold;'><span style='color: #000;'>". __('Pending').": </span>".$pend." </td>
				<td style='font-weight:bold;'><span style='color: #000;'>". __('Solved','dashboard').": </span>".$solve." </td>
				<td style='font-weight:bold;'><span style='color: #000;'>". __('Closed').": </span>".$close." </td>
				
			</tr>
			";

			if($ent_name['entities_id'] == 17 || $ent_name['id'] == 17 || $ent_name['id'] == 0 || $ent_name['id'] == 1 )
			{
				echo "
					<tr>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Valida????o de TR').": </span>".$validacao_tr." </td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Publica????o').": </span>".$publicacao." </td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Parecer de Habilita????o'). ": </span>". ($parecer_habilitacao) ."</td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Valida????o T??cnica').": </span>".$validacao_tecnica." </td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Resultados').": </span>".$resultados." </td>
						
					</tr>
					<tr>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Jur??dico').": </span>".$juridico." </td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Valida????o Interna').": </span>".$validacao_interna." </td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Envio de Contrato').": </span>".$envio_contrato." </td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Homologa????o'). ": </span>". ($homologacao) ."</td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Formaliza????o').": </span>".$formalizacao." </td>						
					</tr>
					
					<tr>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Atribuido').": </span>".$atribuido." </td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Pendente Unidade').": </span>".$pendente_unidade." </td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Publica????o de Errata').": </span>".$publicacao_errata." </td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Prorroga????o').": </span>".$prorrogacao." </td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Dilig??ncia'). ": </span>". ($diligencia) ."</td>
						<td style='font-weight:bold;'><span style='color: #000;'>". __('Recurso').": </span>".$recurso." </td>						
					</tr>
				";
			}

		echo "
			<tr><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td></tr>
		</table>

		<table id='t_ent' class='display' style='font-size: 11px;' >
			<thead>
				<tr>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Tickets', 'dashboard')." </th>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Status')." </th>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Type')." </th>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Title')." </th>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer; max-width:120px;'> ".__('Requester')." </th>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Technician')." </th>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer; max-width:120px;'> ".__('Category')." </th>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Source')." </th>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Assets')." </th>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Opened', 'dashboard')."</th>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Closed', 'dashboard')." </th>
					<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Resolution')." </th>
				</tr>
			</thead>
		<tbody>
		";

		$DB->data_seek($result_cham,0);

		while($row = $DB->fetch_assoc($result_cham)){


		    $status1 = $row['status'];

		    if($status1 == "1" )  { $status1 = "new";}
		    if($status1 == "2" )  { $status1 = "assign";}
		    if($status1 == "3" )  { $status1 = "plan";}
		    if($status1 == "4" )  { $status1 = "waiting";}
		    if($status1 == "5" )  { $status1 = "solved";}
		    if($status1 == "6" )  { $status1 = "closed";}
		    if($status1 == "13" ) { $status1 = "validacao_tr";}
		    if($status1 == "14" ) { $status1 = "publicacao";}
		    if($status1 == "15" ) { $status1 = "parecer_habilitacao";}
		    if($status1 == "16" ) { $status1 = "validacao_tecnica";}
		    if($status1 == "17" ) { $status1 = "resultados";}
		    if($status1 == "18" ) { $status1 = "homologacao";}
		    if($status1 == "19" ) { $status1 = "juridico";}
		    if($status1 == "20" ) { $status1 = "validacao_interna";}
		    if($status1 == "21" ) { $status1 = "envio_contrato";}
		    if($status1 == "22" ) { $status1 = "formalizacao";}
		    if($status1 == "23" ) { $status1 = "atribuido";}

		//requerente
		      $sql_user = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
				FROM `glpi_tickets_users` , glpi_tickets, glpi_users
				WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
				AND glpi_tickets.id = ". $row['id'] ."
				AND glpi_tickets_users.`users_id` = glpi_users.id
				AND glpi_tickets_users.type = 1 ";

				$result_user = $DB->query($sql_user);
				$row_user = $DB->fetch_assoc($result_user);

		//tecnico
		      $sql_tec = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
				FROM `glpi_tickets_users` , glpi_tickets, glpi_users
				WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
				AND glpi_tickets.id = ". $row['id'] ."
				AND glpi_tickets_users.`users_id` = glpi_users.id
				AND glpi_tickets_users.type = 2 ";

				$result_tec = $DB->query($sql_tec);
				$row_tec = $DB->fetch_assoc($result_tec);


				//category
		    	$sql_cat = "SELECT name, completename
				FROM glpi_itilcategories
				WHERE id = ".$row['cat']." ";

				$result_cat = $DB->query($sql_cat);
				$row_cat = $DB->fetch_assoc($result_cat);


				// associated element
			   $sql_item = "SELECT itemtype, items_id
				FROM glpi_items_tickets
				WHERE glpi_items_tickets.tickets_id = ". $row['id'] ."";

				$result_item = $DB->query($sql_item);
				$row_item = $DB->fetch_assoc($result_item);

				$type = strtolower($row_item['itemtype']);
				$url_type = $CFG_GLPI['url_base']."/front/".$type.".form.php?id=";

				if(!empty($row_item['items_id'])) {
			    	$sql_ass = "SELECT id, name
					FROM glpi_".$type."s
					WHERE id = ".$row_item['items_id']." ";
	
					$result_ass = $DB->query($sql_ass);
				}
				
				//ticket source
				$sql_source = "
				SELECT glpi_requesttypes.name AS source
				FROM `glpi_tickets` , glpi_requesttypes
				WHERE glpi_tickets.is_deleted =0
				AND glpi_tickets.id = ". $row['id'] ."
				AND glpi_tickets.`requesttypes_id` = glpi_requesttypes.id";
				
				$result_source = $DB->query($sql_source);
				$row_source = $DB->fetch_assoc($result_source);


		if(isset($result_ass) AND $result_ass != '') {
			$row_item = $DB->fetch_assoc($result_ass);
		}

		echo "
		<tr>
			<td style='vertical-align:middle; text-align:center; font-weight:bold;'><a href=".$CFG_GLPI['url_base']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
			<td style='vertical-align:middle; font-size:10px;'><img src=".$CFG_GLPI['url_base']."/pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/>&nbsp; ".Ticket::getStatus($row['status'])." </td>
			<td style='vertical-align:middle;'> ". Ticket::getTicketTypeName($row['TYPE']) ." </td>
			<td style='vertical-align:middle;'> ". substr($row['descr'],0,55) ." </td>
			<td style='vertical-align:middle;'> ". $row_user['name'] ." ". $row_user['sname'] ." </td>
			<td style='vertical-align:middle;'> ". $row_tec['name'] ." ". $row_tec['sname'] ." </td>
			<td style='vertical-align:middle;'> ". $row_cat['completename'] ." </td>
			<td style='vertical-align:middle;'> ". $row_source['source'] ." </td>
			<td style='vertical-align:middle;'> <a href=". $url_type.$row_item['id'] ." target=_blank >". $row_item['name'] ." </a></td>
			<td style='vertical-align:middle;'> ". conv_data_hora($row['date']) ." </td>
			<td style='vertical-align:middle;'> ". conv_data_hora($row['closedate']) ." </td>
			<td style='vertical-align:middle;'> ". time_ext($row['time_sec']) ." </td>
		</tr>";
		}

		echo "</tbody>
				</table>
				</div>"; ?>

		<script type="text/javascript" charset="utf-8">

		$('#t_ent')
			.removeClass( 'display' )
			.addClass('table table-striped table-bordered table-hover dataTable');

		$(document).ready(function() {
		    $('#t_ent').DataTable( {

				  select: true,
		        dom: 'Blfrtip',
		        stateSave: true,
		        filter: false,
		        pagingType: "full_numbers",
		        deferRender: true,
				  fixedHeader: true,
       		 //"scrollY":   "90vh",
        		 //"scrollCollapse": true,
		        sorting: [[0,'desc'],[1,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc'],[6,'desc'],[7,'desc'],[8,'desc'],[9,'desc'],[10,'desc'],[11,'desc']],
				  displayLength: 25,
		        lengthMenu: [[25, 50, 75, 100], [25, 50, 75, 100]],
		        //select: { style: "multi" },
		        buttons: [
		        	    {
		                 extend: "copyHtml5",
		                 text: "<?php echo __('Copy'); ?>"
		             },
		             {
		             	  extend: "collection",
		                 text: "<?php echo __('Print','dashboard'); ?>",
								  buttons:[
								  	{
				                 extend: "print",
				                 autoPrint: true,
				                 text: "<?php echo __('All','dashboard'); ?>",
				                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid' style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Entity', 'dashboard'); ?> : </span><?php echo $ent_name['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
				                 exportOptions: {
				                 		columns: ':visible'
				                }
				                },
									  {
				                 extend: "print",
				                 autoPrint: true,
				                 text: "<?php echo __('Selected','dashboard'); ?>",
				                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid' style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Entity', 'dashboard'); ?> : </span><?php echo $ent_name['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
				                 exportOptions: {
				                 	  columns: ':visible',
				                    modifier: {
				                        selected: true
				                    }
				                }
				                }]
		             },
		             {
		                 extend: "collection",
		                 text: "<?php echo _x('button', 'Export'); ?>",
		                 buttons: [ "csvHtml5", "excelHtml5",
		                  {
		                 		extend: "pdfHtml5",
		                 		orientation: "landscape",
		                 		message: "<?php echo __('Entity', 'dashboard'); ?> : <?php echo $ent_name['name'] . '  -  '; ?> <?php echo  __('Tickets','dashboard'); ?> : <?php echo $consulta .'  -  '; ?> <?php echo  __('Period','dashboard'); ?> : <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> ",
		                 		exportOptions: {
				                  columns: ':visible'
				                }
		                  }]
		             },
		             {
                		extend: 'colvis',
                		text: "<?php echo __('Show/hide columns', 'dashboard'); ?>",
                		columns: ':not(:first-child)',
                		postfixButtons: [ 'colvisRestore' ]

            	   }
		        ],
                columnDefs: [ {
		            //targets: [7],
		            //visible: false
		        } ]

		    } );
		} );
		</script>

		<?php
			echo '</div><br>';
			}
			else {
	
				echo "
				<div id='nada_rel' class='well info_box fluid col-md-12'>
					<table class='table' style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
						<tr>
							<td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
						<tr></tr>
					</table>
				</div>\n";
			}
		}

/*
//entidades filhas
SELECT ent.`id`, ent.`name`, ent.`sons_cache`, count(sub_entities.id) as nb_subs
                  FROM `glpi_entities` as ent
                  LEFT JOIN `glpi_entities` as sub_entities
                     ON sub_entities.entities_id = ent.id
                  WHERE ent.`entities_id` = 26
                  GROUP BY ent.`id`, ent.`name`, ent.`sons_cache`
                  ORDER BY `name`

*/				
		?>

		<script type="text/javascript" >
			$(document).ready(function() { $("#sel1").select2({dropdownAutoWidth : true}); });
		</script>

		</div>
		</div>
	</div>
</div>

</body>
</html>
