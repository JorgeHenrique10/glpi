<?php

include("../../../../inc/includes.php");
include("../../../../inc/config.php");

Session::checkLoginUser();
Session::checkRight("profile", READ);

global $DB;

switch (date("m")) {
	case "01":
		$mes = __('January', 'dashboard');
		break;
	case "02":
		$mes = __('February', 'dashboard');
		break;
	case "03":
		$mes = __('March', 'dashboard');
		break;
	case "04":
		$mes = __('April', 'dashboard');
		break;
	case "05":
		$mes = __('May', 'dashboard');
		break;
	case "06":
		$mes = __('June', 'dashboard');
		break;
	case "07":
		$mes = __('July', 'dashboard');
		break;
	case "08":
		$mes = __('August', 'dashboard');
		break;
	case "09":
		$mes = __('September', 'dashboard');
		break;
	case "10":
		$mes = __('October', 'dashboard');
		break;
	case "11":
		$mes = __('November', 'dashboard');
		break;
	case "12":
		$mes = __('December', 'dashboard');
		break;
}
?>

<html>

<head>
	<title>GLPI - <?php echo __('Charts', 'dashboard') . " " . __('by Requester', 'dashboard'); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<meta http-equiv="content-language" content="en-us" />
	<!--  <meta http-equiv="refresh" content= "120"/> -->

	<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
	<link href="../css/styles.css" rel="stylesheet" type="text/css" />
	<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
	<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />

	<script language="javascript" src="../js/jquery.min.js"></script>
	<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
	<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>

	<script src="../js/highcharts.js"></script>
	<script src="../js/modules/exporting.js"></script>
	<script src="../js/modules/no-data-to-display.js"></script>
	<script src="../js/bootstrap-datepicker.js"></script>

	<link href="../css/datepicker.css" rel="stylesheet" type="text/css">

	<!-- <link href="../css/style-dash.css" rel="stylesheet" type="text/css" /> -->
	<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-' . $_SESSION['style'] . '">';  ?>
	<?php echo '<script src="../js/themes/' . $_SESSION['charts_colors'] . '"></script>'; ?>

	<!-- odometer -->
	<link href="../css/odometer.css" rel="stylesheet">
	<script src="../js/odometer.js"></script>

</head>

<body style="background-color: #e5e5e5; margin-left:0%;">
	<?php

	global $DB;

	if (!empty($_POST['submit'])) {
		$data_ini =  $_POST['date1'];
		$data_fin = $_POST['date2'];
	} else {
		$data_ini = date("Y-m-01");
		$data_fin = date("Y-m-d");
	}

	//tech
	if (!isset($_POST["sel_tec"])) {
		$id_tec = $_REQUEST["tec"];
	} else {
		$id_tec = $_POST["sel_tec"];
	}

	$ano = date("Y");
	$month = date("Y-m");
	$datahoje = date("Y-m-d");

	//entity
	$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = " . $_SESSION['glpiID'] . "";
	$result_e = $DB->query($sql_e);
	$sel_ent = $DB->result($result_e, 0, 'value');

	//select entity
	if ($sel_ent == '' || $sel_ent == -1) {

		$query_ent1 = "
	SELECT entities_id
	FROM glpi_users
	WHERE id = " . $_SESSION['glpiID'] . " ";

		$res_ent1 = $DB->query($query_ent1);
		$user_ent = $DB->result($res_ent1, 0, 'entities_id');

		//get all user entities
		$entities = $_SESSION['glpiactiveentities'];
		$ent = implode(",", $entities);

		$entidade = "AND glpi_users.entities_id IN (" . $ent . ")";
		$entidade_age = "AND glpi_tickets.entities_id IN (" . $ent . ")";
		$entidade_u = "AND glpi_profiles_users.entities_id IN (" . $ent . ") ";
		$entidade1 = "";
	} else {
		$entidade = "AND glpi_users.entities_id IN (" . $sel_ent . ")";
		$entidade_age = "AND glpi_tickets.entities_id IN (" . $sel_ent . ")";
		$entidade_u = "AND glpi_profiles_users.entities_id IN (" . $sel_ent . ") ";
	}

	//seleciona t??cnico
	$sql_tec = "

SELECT DISTINCT glpi_users.`id` AS id , glpi_users.`firstname` AS name, glpi_users.`realname` AS sname									        										       										        
 FROM `glpi_profiles_users`										
 LEFT JOIN `glpi_tickets_users`
      ON (`glpi_tickets_users`.`users_id`=`glpi_profiles_users`.`users_id`)
 LEFT JOIN `glpi_users`
      ON (`glpi_users`.`id` = `glpi_profiles_users`.`users_id`)
 WHERE `glpi_users`.`is_deleted` = '0' 
	   AND `glpi_users`.`is_active` = '1'
	   AND glpi_tickets_users.type = 1											 										 
" . $entidade_u . "
ORDER BY name ASC ";

	$result_tec = $DB->query($sql_tec);

	$tec = $DB->fetch_assoc($result_tec);

	// lista de tecnicos
	function dropdown($name, array $options, $selected = null)
	{
		/*** begin the select ***/
		$dropdown = '<select style="width: 300px; height: 27px;" autofocus onChange="javascript: document.form1.submit.focus()" name="' . $name . '" id="' . $name . '">' . "\n";

		$selected = $selected;
		/*** loop over the options ***/
		foreach ($options as $key => $option) {
			/*** assign a selected value ***/
			$select = $selected == $key ? ' selected' : null;

			/*** add each option to the dropdown ***/
			$dropdown .= '<option value="' . $key . '"' . $select . '>' . $option . '</option>' . "\n";
		}

		/*** close the select ***/
		$dropdown .= '</select>' . "\n";

		/*** and return the completed dropdown ***/
		return $dropdown;
	}

	// lista de t??cnicos
	$res_tec = $DB->query($sql_tec);
	$arr_tec = array();
	$arr_tec[0] = "-- " . __('Select a requester', 'dashboard') . " --";

	$DB->data_seek($result_tec, 0);

	while ($row_result = $DB->fetch_assoc($result_tec)) {
		$v_row_result = $row_result['id'];
		$arr_tec[$v_row_result] = $row_result['name'] . " " . $row_result['sname'] . " (" . $row_result['id'] . ")";
	}

	$name = 'sel_tec';
	$options = $arr_tec;
	$selected = $id_tec;

	?>

	<div id='content'>
		<div id='container-fluid' style="margin: 0px 5% 0px 5%;">
			<div id="pad-wrapper">
				<div id="charts" class="fluid chart">
					<div id="head" class="fluid">
						<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i></a>

						<div id="titulo_graf">
							<?php echo __('Tickets', 'dashboard') . " " . __('by Requester', 'dashboard'); ?>
							<span style="color:#8b1a1a; font-size:35pt; font-weight:bold;"> </span>
						</div>

						<div id="datas-tec" class="col-md-12 fluid">
							<form id="form1" name="form1" class="form2" method="post" action="?date1=<?php echo $data_ini ?>&date2=<?php echo $data_fin ?>&con=1">
								<table border="0" cellspacing="0" cellpadding="1" bgcolor="#efefef">
									<tr>
										<td>
											<?php
											echo '
						<table>
							<tr>
								<td>
								   <div class="input-group date" id="dp1" data-date="' . $data_ini . '" data-date-format="yyyy-mm-dd">
								    	<input class="col-md-9 form-control" size="13" type="text" name="date1" value="' . $data_ini . '" >		    	
								    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>	    	
							    	</div>
								</td>
								<td>&nbsp;</td>
								<td>
							   	<div class="input-group date" id="dp2" data-date="' . $data_fin . '" data-date-format="yyyy-mm-dd">
								    	<input class="col-md-9 form-control" size="13" type="text" name="date2" value="' . $data_fin . '" >		    	
								    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>	    	
							    	</div>
								</td>
								<td>&nbsp;</td>
							</tr>
						</table> ';
											//echo $entidade;
											?>

											<script language="Javascript">
												$('#dp1').datepicker('update');
												$('#dp2').datepicker('update');
											</script>
										</td>
										<td style="margin-top:2px;">
											<?php
											echo dropdown($name, $options, $selected);
											?>
										</td>
									</tr>
									<tr>
										<td height="15px"></td>
									</tr>
									<tr>
										<td colspan="2" align="center" style="">
											<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar"><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult', 'dashboard'); ?></button>
											<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='graf_usuario.php'"> <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean', 'dashboard'); ?> </button>
										</td>
										</td>
									</tr>
								</table>
								<?php Html::closeForm(); ?>
								<!-- </form> -->
						</div>
					</div>

					<!-- DIV's -->

					<script type="text/javascript">
						$(document).ready(function() {
							$("#sel_tec").select2({
								dropdownAutoWidth: true
							});
						});
					</script>

					<?php

					if (isset($_REQUEST['con'])) {
						$con = $_REQUEST['con'];
					} else {
						$con = '';
					}

					if ($con == "1") {

						if (!isset($_POST['date1'])) {
							$data_ini2 = $_GET['date1'];
							$data_fin2 = $_GET['date2'];
						} else {
							$data_ini2 = $_POST['date1'];
							$data_fin2 = $_POST['date2'];
						}

						if ($id_tec == 0) {
							echo '<script language="javascript"> alert(" ' . __('Select a requester', 'dashboard') . ' "); </script>';
							echo '<script language="javascript"> location.href="graf_usuario.php"; </script>';
						}

						// nome do tecnico
						$sql_nm = "
SELECT DISTINCT glpi_users.`id` AS id , glpi_users.firstname AS name, glpi_users.`realname` AS sname, glpi_users.picture
FROM `glpi_users` , glpi_tickets_users
WHERE glpi_tickets_users.users_id = glpi_users.id
AND glpi_users.id = " . $id_tec . "
AND glpi_tickets_users.type = 1
ORDER BY glpi_users.firstname ASC ";

						$result_nm = $DB->query($sql_nm);
						$tec_name = $DB->fetch_assoc($result_nm);


						if ($data_ini == $data_fin) {
							$datas = "LIKE '" . $data_ini . "%'";
						} else {
							$datas = "BETWEEN '" . $data_ini . " 00:00:00' AND '" . $data_fin . " 23:59:59'";
						}

						$query_total = "SELECT count(*) AS total
FROM glpi_tickets_users, glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date " . $datas . "
AND glpi_tickets_users.users_id = " . $id_tec . "
AND glpi_tickets_users.type = 1
" . $entidade_age . "
AND glpi_tickets_users.tickets_id = glpi_tickets.id ";

						$result_total = $DB->query($query_total);
						$total_cham = $DB->fetch_assoc($result_total);

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
FROM glpi_tickets_users, glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date " . $datas . "
AND glpi_tickets_users.users_id = " . $id_tec . "
AND glpi_tickets_users.type = 1
" . $entidade_age . "
AND glpi_tickets_users.tickets_id = glpi_tickets.id ";

						$result_stat = $DB->query($query_stat);

						$new = $DB->result($result_stat, 0, 'new') + 0;
						$assig = $DB->result($result_stat, 0, 'assig') + 0;
						$plan = $DB->result($result_stat, 0, 'plan') + 0;
						$pend = $DB->result($result_stat, 0, 'pend') + 0;
						$solve = $DB->result($result_stat, 0, 'solve') + 0;
						$close = $DB->result($result_stat, 0, 'close') + 0;
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


						$query_contratos = "SELECT id, entities_id FROM glpi_entities WHERE id in (" . $sel_ent . ")";

						$result_contratos = $DB->query($query_contratos);
						$sel_ent_contratos = $result_contratos->fetch_all();

						$status_contratos = '';

						$entidades_sel = explode(',', $sel_ent);

						$exibir = false;
						foreach ($sel_ent_contratos as $item) {
							if ($item[0] == 17 || $item[1] == 17) {
								$exibir = true;
							}
						}

						if ($exibir) {
							$status_contratos = "<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#F9E79F'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer5' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Valida????o de TR</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#A2D9CE'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer6' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Publica????o</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#1A5276'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer7' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Parecer de Habilita????o</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#85929E'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer8' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Valida????o T??cnica</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#1F618D'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer9' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Resultados</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#FADBD8'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer10' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Jur??dico</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#FADBD8'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer11' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Valida????o Interna</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#AED6F1'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer12' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Envio de Contrato</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#F7DC6F'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer13' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Homologa????o</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#F5CBA7'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer14' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Formaliza????o</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#A3E4D7'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer15' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Atribuido</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#A3E4D7'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer16' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Pendente Unidade</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#A3E4D7'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer17' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Publica????o de Errata</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#A3E4D7'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer18' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Prorroga????o</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#A3E4D7'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer19' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Dilig??ncia</b></span>
									</div>
									</div>
								</div>
							</div>
							<div class='col-sm-3 col-md-3'>
								<div class='dashbox shad panel panel-default db-yellow'>
									<div class='panel-body_2'>
									<div class='panel-left yellow yellowbg' style = 'margin-top: -5px; margin-left: -5px;'>
											<i class='fa fa-file fa-3x fa2' style='color:#A3E4D7'></i>
									</div>
									<div class='panel-right'>
											<div id='odometer15' class='odometer' style='font-size: 22px; margin-top: 1px;'>   </div><p></p>
										<span class='date'><b>Recurso</b></span>
									</div>
									</div>
								</div>
							</div>
							";

						}


						echo '<div id="entidade2" class="col-md-12 fluid" style="margin-bottom: 15px;">';
						echo '<div id="name"  style="margin-top: 15px;">
<span><img class="avatar2" width="43px" height="45px" src="' . User::getURLForPicture($tec_name["picture"]) . '"></img>&nbsp;&nbsp;</span>
<span>' . $tec_name['name'] . ' ' . $tec_name['sname'] . '</span> - <span> ' . $total_cham['total'] . ' ' . __('Tickets', 'dashboard') . '</span></div>
	
<div class="row" style="margin: 10px 0px 0 0;" >	
<div style="margin-top: 20px; height: 45px;">
				<!-- COLUMN 1 -->															
				  <div class="col-sm-3 col-md-3 stat" >
					 <div class="dashbox shad panel panel-default db-blue">
						<div class="panel-body_2">
						   <div class="panel-left red bluebg" style = "margin-top: -5px; margin-left: -5px;">
								<i class="fa fa-tags fa-3x fa2"></i>
						   </div>
						   <div class="panel-right">
						     <div id="odometer1" class="odometer" style="font-size: 22px; margin-top: 1px;">  </div><p></p>
            				<span class="chamado">' . __('Tickets', 'dashboard') . '</span><br>
            				<span class="date" style="font-size: 16px;"><b>' . _x('status', 'New') . ' + ' . __('Assigned') . '</b></span>												
						   </div>
						</div>
					 </div>
				  </div>
				  
				  <div class="col-sm-3 col-md-3">
					 <div class="dashbox shad panel panel-default db-orange">
						<div class="panel-body_2">
						   <div class="panel-left orange orangebg " style = "margin-top: -5px; margin-left: -5px;">
								<i class="fa fa-clock-o fa-3x fa2"></i>
						   </div>
						   <div class="panel-right">										 
							<div id="odometer2" class="odometer" style="font-size: 22px; margin-top: 1px;">   </div><p></p>
            				<span class="chamado">' . __('Tickets', 'dashboard') . '</span><br>
            				<span class="date"><b>' . __('Pending') . '</b></span>
						   </div>
						</div>
					 </div>
				  </div>																		
         								
				  <div class="col-sm-3 col-md-3">
					 <div class="dashbox shad panel panel-default db-red">
						<div class="panel-body_2">
						   <div class="panel-left yellow redbg" style = "margin-top: -5px; margin-left: -5px;">
								<i class="fa fa-check-square fa-3x fa2"></i>
						   </div>
						   <div class="panel-right">
								<div id="odometer3" class="odometer" style="font-size: 22px; margin-top: 1px;">   </div><p></p>
            				<span class="chamado">' . __('Tickets', 'dashboard') . '</span><br>
            				<span class="date"><b>' . __('Solved', 'dashboard') . '</b></span>
						   </div>										   
						</div>
					 </div>
				  </div>
				  <div class="col-sm-3 col-md-3">
					 <div class="dashbox shad panel panel-default db-yellow">
						<div class="panel-body_2">
						   <div class="panel-left yellow yellowbg" style = "margin-top: -5px; margin-left: -5px;">
								<i class="fa fa-times-circle fa-3x fa2"></i>
						   </div>
				   		<div class="panel-right">
								<div id="odometer4" class="odometer" style="font-size: 22px; margin-top: 1px;">   </div><p></p>
            				<span class="chamado">' . __('Tickets', 'dashboard') . '</span><br>
            				<span class="date"><b>' . __('Closed', 'dashboard') . '</b></span>
						   </div>
						</div>
					 </div>
				  </div>			
				  '
				  . $status_contratos .

				  '															                          				                           							
		</div>  		
</div>
</div>';
					?>

						<script type="text/javascript">
							window.odometerOptions = {
								format: '( ddd).dd'
							};

							setTimeout(function() {
								odometer1.innerHTML = <?php echo $new + $assig + $plan; ?>;
								odometer2.innerHTML = <?php echo $pend; ?>;
								odometer3.innerHTML = <?php echo $solve; ?>;
								odometer4.innerHTML = <?php echo $close; ?>;
								if (<?php echo ($exibir ? 1 : 0) ?>) {
									console.log("entrou")
									odometer5.innerHTML = <?php echo $validacao_tr; ?>;
									odometer6.innerHTML = <?php echo $publicacao; ?>;
									odometer7.innerHTML = <?php echo $parecer_habilitacao; ?>;
									odometer8.innerHTML = <?php echo $validacao_tecnica; ?>;
									odometer9.innerHTML = <?php echo $resultados; ?>;
									odometer10.innerHTML = <?php echo $homologacao; ?>;
									odometer11.innerHTML = <?php echo $juridico; ?>;
									odometer12.innerHTML = <?php echo $validacao_interna; ?>;
									odometer13.innerHTML = <?php echo $envio_contrato; ?>;
									odometer14.innerHTML = <?php echo $formalizacao; ?>;
									odometer15.innerHTML = <?php echo $atribuido; ?>;
									odometer16.innerHTML = <?php echo $pendente_unidade; ?>;
									odometer17.innerHTML = <?php echo $publicacao_errata; ?>;
									odometer18.innerHTML = <?php echo $prorrogacao; ?>;
									odometer19.innerHTML = <?php echo $diligencia; ?>;
									odometer20.innerHTML = <?php echo $recurso; ?>;

								}
							}, 1000);
						</script>

						<div id="graf_linhas" class="col-md-12 col-sm12" style="height: 450px; margin-top: 25px;">
							<?php include("./inc/graflinhas_user.inc.php"); ?>
						</div>

						<div id="graf2" class="col-md-6 col-sm-6">
							<?php include("./inc/grafpie_stat_user.inc.php"); ?>
						</div>

						<div id="graf_tipo" class="col-md-6 col-sm-6" style="margin-left: 0%;">
							<?php include("./inc/grafpie_tipo_user.inc.php");  ?>
						</div>

						<div id="graf4" class="col-md-12 col-sm-12" style="height: 450px; margin-left: 0px;">
							<?php include("./inc/grafcat_user.inc.php"); ?>
						</div>

					<?php
					}
					?>

				</div>
			</div>
		</div>
	</div>
	</div>

	<!-- Highcharts export xls, csv -->
	<script src="../js/export-csv.js"></script>

</body>

</html>