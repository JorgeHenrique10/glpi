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

if(!isset($_POST["sel_date"])) {
	$id_date = $_GET["sel_date"];
}

else {
	$id_date = $_POST["sel_date"];
}


# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' || $sel_ent == -1) {

//	$sel_ent = 0;
	$entities = $_SESSION['glpiactiveentities'];
	$ent = implode(",",$entities);

	$entidade = "AND glpi_tickets.entities_id IN (".$ent.") ";
	$entidade_d = "AND entities_id IN (".$ent.") ";
	$entidade_dw = "WHERE entities_id IN (".$ent.") ";
}

else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";
	$entidade_d = "AND entities_id IN (".$sel_ent.") ";
	$entidade_dw = "WHERE entities_id IN (".$sel_ent.") ";
}

?>
<html>
<head>
<title> GLPI - <?php echo __('Tickets','dashboard') .'  '. __('by Date','dashboard') ?> </title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
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

<script src="../js/bootstrap.min.js"></script>
<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">

<script src="../js/media/js/jquery.dataTables.min.js"></script>
<link href="../js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />
<script src="../js/media/js/dataTables.bootstrap.js"></script>

<script src="../js/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.html5.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.print.min.js"></script>
<script src="../js/media/pdfmake.min.js"></script>
<script src="../js/media/vfs_fonts.js"></script>
<script src="../js/media/jszip.min.js"></script>

<script src="../js/extensions/Select/js/dataTables.select.min.js"></script>
<link href="../js/extensions/Select/css/select.bootstrap.css" type="text/css" rel="stylesheet" />

<style type="text/css">
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>

</head>

<body style="background-color: #e5e5e5;">

<div id='content' >
	<div id='container-fluid' style="margin: <?php echo margins(); ?> ;">
		<div id="charts" class="fluid chart">
			<div id="pad-wrapper" >

				<div id="head-rel" class="fluid">
					<style type="text/css">
					a:link, a:visited, a:active {
					    text-decoration: none
					    }
					a:hover {
					    color: #000099;
					    }
					</style>

					<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:20px;"></i><span></span></a>

					<div id="titulo_rel"> <?php echo __('Tickets','dashboard') .'  '. __('by Date','dashboard') ?> </div>
						<div id="datas-tec" class="col-md-12 col-sm-12 fluid" >
						    <form id="form1" name="form1" class="form_rel" method="post" action="rel_data.php?con=1">
							    <table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef" >
								    <tr>
										<td style="width: 310px;">
										<?php
										$url = $_SERVER['REQUEST_URI'];
										$arr_url = explode("?", $url);
										$url2 = $arr_url[0];

										echo'
											<table>
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
										
										$arr_date = array(
											 __('----'),
										    __('Today','dashboard'),
										    __('Yesterday','dashboard'),
										    __('Last 7 days','dashboard'),
										    __('Last 15 days','dashboard'),
										    __('Last 30 days','dashboard'),
										    __('Last 3 months','dashboard'),
										 );

										$name = 'sel_date';
										$options = $arr_date;
										$selected = $id_date;

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

			</div>


		<?php

		if(isset($_GET['con'])){$con = $_GET['con'];}
		else {$con = '';}
		
		if($con == "1") {

			if(!empty($_POST['date1']))
			{
			    $data_ini2 = $_POST['date1'];
			    $data_fin2 = $_POST['date2'];
			}
			else {
			    $data_ini2 = $_GET['date1'];
			    $data_fin2 = $_GET['date2'];
			}

			if($data_ini2 == $data_fin2) {
				$datas2 = "LIKE '".$data_ini2."%'";
			}
			else {
				$datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";
			}

			// do select
			$post_date = $_POST["sel_date"];

			if(!isset($post_date) or $post_date == "0") {
			    $sel_date = $datas2;
			}
			else {
			    $sel_date = $_POST["sel_date"];
			}

			switch($post_date) {

				 case ("1") :
	             $data_ini2 = date('Y-m-d');
	             $data_fin2 = date('Y-m-d');
	              $sel_date = "BETWEEN '" . $data_ini2 ." 00:00:00' AND '". $data_fin2 ." 23:59:59'";
	          break;
	          case ("2") :
	             $data_ini2 = date('Y-m-d', strtotime('-1 day'));
	             $data_fin2 = date('Y-m-d', strtotime('-1 day'));
	              $sel_date = "BETWEEN '" . $data_ini2 ." 00:00:00' AND '". $data_fin2 ." 23:59:59'";
	          break;
	          case ("3") :
	             $data_ini2 = date('Y-m-d', strtotime('-1 week'));
	              $sel_date = "BETWEEN '" . $data_ini2 ." 00:00:00' AND '".$data_fin2." 23:59:59'";
	          break;
	          case ("4") :
	             $data_ini2 = date('Y-m-d', strtotime('-15 day'));
	              $sel_date = "BETWEEN '" . $data_ini2 ." 00:00:00' AND '".$data_fin2." 23:59:59'";
	          break;
	          case ("5") :
	              $data_ini2 = date('Y-m-d', strtotime('-1 month'));
	              $sel_date = "BETWEEN '" . $data_ini2 ." 00:00:00' AND '".$data_fin2." 23:59:59'";
	          break;
	          case ("6") :
	              $data_ini2 = date('Y-m-d', strtotime('-3 month'));
	              $sel_date = "BETWEEN '" . $data_ini2 ." 00:00:00' AND '".$data_fin2." 23:59:59'";
	          break;

			}

			//status
			$status = "";
			$status_open = "('2','1','3','4')";
			$status_close = "('5','6')";
			$status_all = "('2','1','3','4','5','6','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28')";
			$status_all_not_close = "('2','1','3','4','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28')";

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

			if(isset($_GET['entidade']))
			{
				$entidade = "AND glpi_tickets.entities_id = ". $_GET['entidade'];
				$sel_date = $datas2;
			}

			// Chamados
			$sql_cham = "SELECT glpi_tickets.id AS id, glpi_tickets.name AS descr, glpi_tickets.date AS date, glpi_tickets.solvedate AS solvedate, glpi_tickets.status AS status, glpi_entities.name AS unidade
			FROM glpi_tickets, glpi_entities
			WHERE glpi_tickets.date ".$sel_date."
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.status IN ".$status_all_not_close."
			AND glpi_tickets.entities_id = glpi_entities.id
			".$entidade."
			ORDER BY id DESC ";

			$result_cham = $DB->query($sql_cham);

			//quant de chamados
			$sql_cham2 =
			"SELECT count(id) AS total
			FROM glpi_tickets
			WHERE date ".$sel_date."
			AND glpi_tickets.status IN ".$status_all."
			AND glpi_tickets.is_deleted = 0
			".$entidade." ";

			$result_cham2 = $DB->query($sql_cham2);

			$conta_cham = $DB->fetch_assoc($result_cham2) ;

			$total_cham = $conta_cham['total'];


			//count by status
			$query_stat = "SELECT
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
			WHERE glpi_tickets.is_deleted = '0'
			AND glpi_tickets.date ".$sel_date."
			".$entidade." ";

		 	$result_stat = $DB->query($query_stat);

            $new = $DB->result($result_stat,0,'new') + 0;
            $assig = $DB->result($result_stat,0,'assig') + 0;
            $plan = $DB->result($result_stat,0,'plan') + 0;
            $pend = $DB->result($result_stat,0,'pend') + 0;
            $solve = $DB->result($result_stat,0,'solve') + 0;
            $close = $DB->result($result_stat,0,'close') + 0;
			$validacao_tr = $DB->result($result_stat, 0, 'validacao_tr') + 0;
			$publicacao = $DB->result($result_stat, 0, 'publicacao') + 0;
			$parecer_habilitacao = $DB->result($result_stat, 0, 'parecer_habilitacao') + 0;
			$validacao_tecnica = $DB->result($result_stat, 0, 'validacao_tecnica') + 0;
			$resultados = $DB->result($result_stat, 0, 'resultados') + 0;
			$homologacao = $DB->result($result_stat, 0, 'homologacao') + 0;
			$juridico = $DB->result($result_stat, 0, 'juridico') + 0;
			$validacao_interna = $DB->result($result_stat, 0, 'validacao_interna') + 0;
			$envio_contrato = $DB->result($result_stat, 0, 'envio_contrato') + 0;
			$formalizacao = $DB->result($result_stat, 0, 'formalizacao') + 0;
			$atribuido = $DB->result($result_stat, 0, 'atribuido') + 0;
			$pendente_unidade = $DB->result($result_stat,0,'pendente_unidade') + 0;
			$publicacao_errata = $DB->result($result_stat,0,'publicacao_errata') + 0;
			$prorrogacao = $DB->result($result_stat,0,'prorrogacao') + 0;
			$diligencia = $DB->result($result_stat,0,'diligencia') + 0;
			$recurso = $DB->result($result_stat,0,'recurso') + 0;


			//chamados abertos
			$sql_ab = "SELECT COUNT(glpi_tickets.id) AS total
			FROM glpi_tickets
			WHERE glpi_tickets.date ".$sel_date."
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.status IN ".$status_all."
			".$entidade." ";

			$result_ab = $DB->query($sql_ab) or die ("erro_ab");
			$data_ab = $DB->fetch_assoc($result_ab);

			$abertos = $data_ab['total'];

			if($total_cham > 0) {

			if($conta_cham > 0) {

				//barra de porcentagem
				if($status == $status_close ) {
				    $barra = 100;
				    $cor = "progress-bar-success";
				}

				else {

					//porcentagem
					$perc = round(($abertos*100)/$total_cham,1);
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
			$total_cham2=$total_cham-$close;
			//listar chamados
			echo "
			<div class='well info_box fluid col-md-12 report' style='margin-left: -1px;'>

			<table class='col-md-12 col-sm-12 fluid'  style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
				<td colspan='2' style='font-size: 18px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ". __('Tickets','dashboard').":</span> ".$total_cham2." </td>
				<td colspan='2' style='font-size: 18px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>
				". __('Period','dashboard') .": </span>" . conv_data($data_ini2) ." a ". conv_data($data_fin2)."
				</td>
					<td style='vertical-align:middle; width: 190px; '>
					<div class='progress' style='margin-top: 19px;'>
						<div class='progress-bar ". $cor ." ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
			    			".$barra." % ".__('Closed', 'dashboard') ."
			    		</div>
					</div>
					</td>
			</table>

			<table align='right' style='margin-bottom:10px;'>
				<tr>
					<td colspan='3'>
						<button class='btn btn-primary btn-sm' type='button' name='abertos' value='Abertos' onclick='location.href=\"rel_data.php?con=1&stat=open&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ". __('Opened','dashboard'). "</button>
						<button class='btn btn-primary btn-sm' type='button' name='fechados' value='Fechados' onclick='location.href=\"rel_data.php?con=1&stat=close&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".  __('Closed','dashboard') ." </button>
						<button class='btn btn-primary btn-sm' type='button' name='todos' value='Todos' onclick='location.href=\"rel_data.php?con=1&stat=all&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ". __('All','dashboard') ." </button>
				</tr>
			</table>

			<table style='font-size: 16px; font-weight:bold; width: 90%;' border=0>
				<tr>
					  <td><span style='color: #000;'>". _x('status','New').": </span><b>".$new." </b></td>
			        <td><span style='color: #000;'>". __('Assigned'). ": </span><b>". ($assig + $plan) ."</b></td>
			        <td><span style='color: #000;'>". __('Pending').": </span><b>".$pend." </b></td>
			        <td><span style='color: #000;'>". __('Solved','dashboard').": </span><b>".$solve." </b></td>
					<td><span style='color: #000;'>Fechado </span><b>".$close." </b></td>
					<td><span style='color: #000;'>Validação TR </span><b>".$validacao_tr." </b></td>
					<td><span style='color: #000;'>Publicação </span><b>".$publicacao." </b></td>
					<td><span style='color: #000;'>Parecer Habilitação </span><b>".$parecer_habilitacao." </b></td>
					<td><span style='color: #000;'>Validacao Técnica </span><b>".$validacao_tecnica." </b></td>
					<td><span style='color: #000;'>Resultados </span><b>".$resultados." </b></td>
					<td><span style='color: #000;'>Homologação </span><b>".$homologacao." </b></td>
				</tr>
				<tr>
				<td><span style='color: #000;'>Jurídico </span><b>".$juridico." </b></td>
				<td><span style='color: #000;'>Validação Interna </span><b>".$validacao_interna." </b></td>
				<td><span style='color: #000;'>Envio Contrato </span><b>".$envio_contrato." </b></td>
				<td><span style='color: #000;'>Formalização </span><b>".$formalizacao." </b></td>
				<td><span style='color: #000;'>Atribuído </span><b>".$atribuido." </b></td>
				<td><span style='color: #000;'>Pendente Unidade </span><b>".$pendente_unidade." </b></td>
				<td><span style='color: #000;'>Publicacao Errata </span><b>".$publicacao_errata." </b></td>
				<td><span style='color: #000;'>Prorrogação </span><b>".$prorrogacao." </b></td>
				<td><span style='color: #000;'>Diligência </span><b>".$diligencia." </b></td>
				<td><span style='color: #000;'>Recurso </span><b>".$recurso." </b></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
			</table>

			<table id='data' class='display'  style='font-size: 12px; font-weight:bold;' cellpadding = 2px>
				<thead>
					<tr>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Tickets','dashboard')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Status')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Unidade')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Objeto')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Requester')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Technician')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Opened','dashboard')."</th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Closed')." </th>
					</tr>
				</thead>
			<tbody>";

			while($row = $DB->fetch_assoc($result_cham)){

			    $status1 = $row['status'];
			    if($status1 == "1" ) { $status1 = "new";}
			    if($status1 == "2" ) { $status1 = "assign";}
			    if($status1 == "3" ) { $status1 = "plan";}
			    if($status1 == "4" ) { $status1 = "waiting";}
			    if($status1 == "5" ) { $status1 = "solved";}
			    if($status1 == "6" ) { $status1 = "closed";}
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
	/*			$sql_user = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
				FROM `glpi_tickets_users` , glpi_tickets, glpi_users
				WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
				AND glpi_tickets.id = ". $row['id'] ."
				AND glpi_tickets_users.`users_id` = glpi_users.id
				AND glpi_tickets_users.type = 1
				".$entidade." ";*/
				
				//requerente
				$sql_user = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname, glpi_tickets_users.alternative_email AS amail
				FROM glpi_tickets, glpi_tickets_users
				LEFT OUTER JOIN glpi_users on glpi_tickets_users.users_id = glpi_users.id
				WHERE glpi_tickets.id = glpi_tickets_users.tickets_id
				AND glpi_tickets.id = ". $row['id'] ."
				AND glpi_tickets_users.type = 1
				".$entidade." ";

				$result_user = $DB->query($sql_user);
				$row_user = $DB->fetch_assoc($result_user);

				//tecnico
				$sql_tec = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
				FROM `glpi_tickets_users` , glpi_tickets, glpi_users
				WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
				AND glpi_tickets.id = ". $row['id'] ."
				AND glpi_tickets_users.`users_id` = glpi_users.id
				AND glpi_tickets_users.type = 2
				".$entidade." ";

				$result_tec = $DB->query($sql_tec);
				$row_tec = $DB->fetch_assoc($result_tec);

				$array = ['SETOR CONTRATOS CONTRATOS > DISPENSA DE COTAÇÃO','SETOR CONTRATOS CONTRATOS > ADITIVO', 'SETOR CONTRATOS CONTRATOS > DISTRATOS','SETOR CONTRATOS CONTRATOS > COTAÇÃO','SETOR CONTRATOS CONTRATOS &gt; DISPENSA DE COTAÇÃO','SETOR CONTRATOS CONTRATOS &gt; ADITIVO', 'SETOR CONTRATOS CONTRATOS &gt; DISTRATOS','SETOR CONTRATOS CONTRATOS &gt; COTAÇÃO'];

				$objeto = str_replace($array, "", $row['descr']);

				echo "
				<tr style='font-weight:normal;'>
					<td style='vertical-align:middle; text-align:center; font-weight:bold;'><a href=".$CFG_GLPI['url_base']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
					<td style='vertical-align:middle;'><img src=".$CFG_GLPI['url_base']."/pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/>&nbsp; ".Ticket::getStatus($row['status'])."  </td>
					<td style='vertical-align:middle;'> ". $row['unidade'] ." </td>
					<td style='vertical-align:middle;'> ". $objeto ." </td>
					<td style='vertical-align:middle;'> ". $row_user['name'] ." ".$row_user['sname'] ." </td>
					<td style='vertical-align:middle;'> ". $row_tec['name'] ." ".$row_tec['sname'] ." </td>
					<td style='vertical-align:middle; text-align:center;'> ". conv_data_hora($row['date']) ." </td>
					<td style='vertical-align:middle; text-align:center;'> ". conv_data_hora($row['solvedate']) ." </td>
				</tr>";
			}

			echo "</tbody>
					</table>
					</div>"; ?>

			<script type="text/javascript" charset="utf-8">

			$('#data')
				.removeClass( 'display' )
				.addClass('table table-striped table-bordered dataTable');

			$(document).ready(function() {
			    $('#data').DataTable( {

					  select: true,
			        dom: 'Blfrtip',
			        filter: false,
			        deferRender: true,
			        pagingType: "full_numbers",
			        sorting: [[0,'desc'],[1,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc']],
					  displayLength: 25,
			        lengthMenu: [[25, 50, 75, 100], [25, 50, 75, 100]],
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
					                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:25px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 20%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $total_cham ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
					                },
										  {
					                 extend: "print",
					                 autoPrint: true,
					                 text: "<?php echo __('Selected','dashboard'); ?>",
					                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:25px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 20%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $total_cham ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
					                 exportOptions: {
					                    modifier: {
					                        selected: true
					                    }
					                }
					                }
				                ]
			             },
			             {
			                 extend:    "collection",
			                 text: "<?php echo _x('button', 'Export'); ?>",
			                 buttons: [ "csvHtml5", "excelHtml5",
			                  {
			                 		extend: "pdfHtml5",
			                 		orientation: "landscape",
			                 		message: "",
			                  }
			                  ]
			             }
			        ]

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
				<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
				<tr></tr>
				</table></div>";
			}

		}
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
