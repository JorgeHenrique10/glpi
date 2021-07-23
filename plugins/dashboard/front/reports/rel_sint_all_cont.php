<?php

include("../../../../inc/includes.php");
include("../../../../inc/config.php");
include "../inc/functions.php";

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if (!empty($_POST['submit'])) {
	$data_ini =  $_POST['date1'];
	$data_fin = $_POST['date2'];
} else {
	$data_ini = date("Y-m-01");
	$data_fin = date("Y-m-d");
}

if (!isset($_POST["sel_date"])) {
	$id_date = $_GET["date"];
} else {
	$id_date = $_POST["sel_date"];
}

# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = " . $_SESSION['glpiID'] . "";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e, 0, 'value');

if ($sel_ent == '' || $sel_ent == -1) {

	//	$sel_ent = 0;
	$entities = $_SESSION['glpiactiveentities'];
	$ent = implode(",", $entities);

	$entidade = "AND glpi_tickets.entities_id IN (" . $ent . ") ";
	$entidade_d = "AND entities_id IN (" . $ent . ") ";
	$entidade_dw = "WHERE entities_id IN (" . $ent . ") ";
} else {
	$entidade = "AND glpi_tickets.entities_id IN (" . $sel_ent . ") ";
	$entidade_d = "AND entities_id IN (" . $sel_ent . ") ";
	$entidade_dw = "WHERE entities_id IN (" . $sel_ent . ") ";
}

?>
<html>

<head>
	<title> GLPI - <?php echo __('Summary Report', 'dashboard') ?> </title>
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

	<style type="text/css">
		select {
			width: 60px;
		}

		table.dataTable {
			empty-cells: show;
		}

		a:link,
		a:visited,
		a:active {
			text-decoration: none;
		}
	</style>

	<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-' . $_SESSION['style'] . '">';  ?>

</head>

<body style="background-color: #e5e5e5;">
	<div id='content'>
		<div id='container-fluid' style="margin: 0px 5% 0px 5%;">
			<div id="charts" class="fluid chart">
				<div id="pad-wrapper">

					<div id="head-rel" class="fluid">
						<style type="text/css">
							a:link,
							a:visited,
							a:active {
								text-decoration: none
							}

							a:hover {
								color: #000099;
							}
						</style>

						<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:20px;"></i><span></span></a>

						<div id="titulo_rel"> <?php echo __('Summary Report', 'dashboard') . '  ' . __('', 'dashboard') ?> </div>
						<div id="datas-tec" class="span12 fluid">
							<form id="form1" name="form1" class="form_rel" method="post" action="rel_sint_all_cont.php?con=1">
								<table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef">
									<tr>
										<td style="width: 310px;">
											<?php
											$url = $_SERVER['REQUEST_URI'];
											$arr_url = explode("?", $url);
											$url2 = $arr_url[0];

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
											?>

											<script language="Javascript">
												$('#dp1').datepicker('update');
												$('#dp2').datepicker('update');
											</script>

										</td>

										<td style="margin-top:2px;">
											<?php

											$arr_date = array(
												__('Select', 'dashboard'),
												__('Current month', 'dashboard'),
												__('Last 7 days', 'dashboard'),
												__('Last 15 days', 'dashboard'),
												__('Last 30 days', 'dashboard'),
												__('Last 3 months', 'dashboard'),
											);

											$name = 'sel_date';
											$options = $arr_date;
											$selected = $id_date;

											echo dropdown($name, $options, $selected);
											?>

										</td>
									</tr>
									<tr>
										<td height="15px"></td>
									</tr>
									<tr>
										<td colspan="2" align="center">
											<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar"><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult', 'dashboard'); ?> </button>
											<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'"><i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean', 'dashboard'); ?> </button>
										</td>
									</tr>
								</table>
								<?php Html::closeForm(); ?>
								<!-- </form> -->
						</div>
					</div>

				</div>


				<?php

				function get_total_days($start, $end, $holidays = [], $weekends = ['Sat', 'Sun']){

					$start = new \DateTime($start);
					$end   = new \DateTime($end);
					$end->modify('+1 day');

					$total_days = $end->diff($start)->days;
					$period = new \DatePeriod($start, new \DateInterval('P1D'), $end);

					foreach($period as $dt) {
						if (in_array($dt->format('D'),  $weekends) || in_array($dt->format('Y-m-d'), $holidays)){
							$total_days--;
						}
					}
					return $total_days;
				}

				if (isset($_GET['con'])) {
					$con = $_GET['con'];
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

					if ($data_ini2 == $data_fin2) {
						$sel_date = "LIKE '" . $data_ini2 . "%'";
					} else {
						$sel_date = "BETWEEN '" . $data_ini2 . " 00:00:00' AND '" . $data_fin2 . " 23:59:59'";
					}

					// do select
					$post_date = $_POST["sel_date"];

					if (!isset($post_date) or $post_date == "0") {
						$sel_date = $sel_date;
					} else {
						$sel_date = $_POST["sel_date"];
					}

					switch ($post_date) {

						case ("1"):
							$data_ini2 = date('Y-m-01');
							$data_fin2 = date('Y-m-d');
							$sel_date = "BETWEEN '" . $data_ini2 . " 00:00:00' AND '" . $data_fin2 . " 23:59:59'";
							break;
						case ("2"):
							$data_ini2 = date('Y-m-d', strtotime('-1 week'));
							$sel_date = "BETWEEN '" . $data_ini2 . " 00:00:00' AND '" . $data_fin2 . " 23:59:59'";
							break;
						case ("3"):
							$data_ini2 = date('Y-m-d', strtotime('-15 day'));
							$sel_date = "BETWEEN '" . $data_ini2 . " 00:00:00' AND '" . $data_fin2 . " 23:59:59'";
							break;
						case ("4"):
							$data_ini2 = date('Y-m-d', strtotime('-1 month'));
							$sel_date = "BETWEEN '" . $data_ini2 . " 00:00:00' AND '" . $data_fin2 . " 23:59:59'";
							break;
						case ("5"):
							$data_ini2 = date('Y-m-d', strtotime('-3 month'));
							$sel_date = "BETWEEN '" . $data_ini2 . " 00:00:00' AND '" . $data_fin2 . " 23:59:59'";
							break;
					}


					// Chamados
					$sql_cham = "SELECT glpi_tickets.id AS id, glpi_tickets.name AS descr, glpi_tickets.date AS date,
		 glpi_tickets.solvedate AS solvedate, glpi_tickets.status AS status
		FROM glpi_tickets
		WHERE glpi_tickets.solvedate " . $sel_date . "
		AND glpi_tickets.is_deleted = 0		
		" . $entidade . "
		ORDER BY id DESC ";

					$result_cham = $DB->query($sql_cham);
					$chamados = $DB->fetch_assoc($result_cham);


					//quant de chamados
					$sql_cham2 =
						"SELECT count(id) AS total, AVG(close_delay_stat) AS avgtime
		FROM glpi_tickets
		WHERE glpi_tickets.is_deleted = 0 		
		AND date " . $sel_date . "		
		" . $entidade . " ";

					$result_cham2 = $DB->query($sql_cham2);
					$conta_cham = $DB->fetch_assoc($result_cham2);

					$total_cham = $conta_cham['total'];
					//$numdias = $conta_cham['numdias'];


					if ($total_cham > 0) {

						//date diff
						$numdias = round(abs(strtotime($data_fin2) - strtotime($data_ini2)) / 86400, 0);

						//tecnico
						$sql_tec = "SELECT count(glpi_tickets.id) AS conta, glpi_users.firstname AS name, glpi_users.realname AS sname
			FROM `glpi_tickets_users` , glpi_tickets, glpi_users
			WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
			AND glpi_tickets.date " . $sel_date . "
			AND glpi_tickets_users.`users_id` = glpi_users.id
			AND glpi_tickets_users.type = 2
			" . $entidade . " 
			GROUP BY name
			ORDER BY conta DESC ";

						$result_tec = $DB->query($sql_tec);

						//requester
						$sql_req =
							"SELECT count( glpi_tickets.id ) AS conta, glpi_tickets_users.`users_id` AS id,  glpi_users.firstname AS name, glpi_users.realname AS sname
			FROM `glpi_tickets_users`, glpi_tickets, glpi_users
			WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
			AND glpi_tickets.date " . $sel_date . "
			AND glpi_tickets_users.`users_id` = glpi_users.id
			AND glpi_tickets_users.type = 1
			AND glpi_tickets.is_deleted = 0
			" . $entidade . "
			GROUP BY `users_id`
			ORDER BY conta DESC ";

						$result_req = $DB->query($sql_req);

						//avg time
						$sql_time =
							"SELECT count(id) AS total, AVG(close_delay_stat) AS avgtime
			FROM glpi_tickets
			WHERE solvedate " . $sel_date . "			
			AND glpi_tickets.is_deleted = 0			
			" . $entidade . " ";

						$result_time = $DB->query($sql_time);
						$time_cham = $DB->fetch_assoc($result_time);

						$avgtime = $time_cham['avgtime'];

						//AND glpi_tickets.solvedate IS NOT NULL
						//count by status
						$query_stat = "
			SELECT
			SUM(case when glpi_tickets.status = 1 then 1 else 0 end) AS new,
			SUM(case when glpi_tickets.status = 2 then 1 else 0 end) AS assig,
			SUM(case when glpi_tickets.status = 3 then 1 else 0 end) AS plan,
			SUM(case when glpi_tickets.status = 4 then 1 else 0 end) AS pend,
			SUM(case when glpi_tickets.status = 5 then 1 else 0 end) AS solve,
			SUM(case when glpi_tickets.status = 6 then 1 else 0 end) AS close,
			SUM(case when glpi_tickets.status = 12 then 1 else 0 end) AS qualificacao,			
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
			AND glpi_tickets.date " . $sel_date . "			
			" . $entidade . "";

						$result_stat = $DB->query($query_stat);
						//print_r($result_stat->fetch_array());
						$new = $DB->result($result_stat, 0, 'new') + 0;
						$assig = $DB->result($result_stat, 0, 'assig') + 0;
						$plan = $DB->result($result_stat, 0, 'plan') + 0;
						$pend = $DB->result($result_stat, 0, 'pend') + 0;
						$solve = $DB->result($result_stat, 0, 'solve') + 0;
						$close = $DB->result($result_stat, 0, 'close') + 0;
						$atribuido = $DB->result($result_stat, 0, 'atribuido') + 0;
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
						$pendente_unidade = $DB->result($result_stat, 0, 'pendente_unidade') + 0;
						$publicacao_errata = $DB->result($result_stat, 0, 'publicacao_errata') + 0;
						$prorrogacao = $DB->result($result_stat, 0, 'prorrogacao') + 0;
						$diligencia = $DB->result($result_stat, 0, 'diligencia') + 0;
						$recurso = $DB->result($result_stat, 0, 'recurso') + 0;

						//count by type
						$query_type = "
			SELECT
			SUM(case when glpi_tickets.type = 1 then 1 else 0 end) AS incident,
			SUM(case when glpi_tickets.type = 2 then 1 else 0 end) AS request
			FROM glpi_tickets
			WHERE glpi_tickets.is_deleted = '0'
			AND glpi_tickets.date " . $sel_date . "			
			" . $entidade . "";

						$result_type = $DB->query($query_type);

						$incident = $DB->result($result_type, 0, 'incident');
						$request = $DB->result($result_type, 0, 'request');

						//select groups
						$sql_grp =
							"SELECT count(glpi_tickets.id) AS conta, glpi_groups.name AS name
			FROM `glpi_groups_tickets`, glpi_tickets, glpi_groups
			WHERE glpi_groups_tickets.`groups_id` = glpi_groups.id
			AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.date " . $sel_date . "
			" . $entidade . "
			GROUP BY name
			ORDER BY conta DESC
			LIMIT 5 ";

			//Total de Chamados Contratos
			$sql_sla_contratos = 
			"SELECT 
				COUNT(IF(glpi_tickets.itilcategories_id = 197, glpi_tickets.itilcategories_id, NULL)) AS distrato,
				COUNT(IF(glpi_tickets.itilcategories_id = 191, glpi_tickets.itilcategories_id, NULL)) AS dispensa,
				COUNT(IF(glpi_tickets.itilcategories_id = 190, glpi_tickets.itilcategories_id, NULL)) AS cotacao,
				COUNT(IF(glpi_tickets.itilcategories_id = 189, glpi_tickets.itilcategories_id, NULL)) AS aditivo,
				COUNT(IF(glpi_tickets.itilcategories_id = 197 && datediff(if(solvedate is null, now(), solvedate), date) <= 20 , glpi_tickets.itilcategories_id, NULL)) AS distrato_prazo,
				COUNT(IF(glpi_tickets.itilcategories_id = 191 && datediff(if(solvedate is null, now(), solvedate), date) <= 20, glpi_tickets.itilcategories_id, NULL)) AS dispensa_prazo,
				COUNT(IF(glpi_tickets.itilcategories_id = 190 && datediff(if(solvedate is null, now(), solvedate), date) <= 41, glpi_tickets.itilcategories_id, NULL)) AS cotacao_prazo,
				COUNT(IF(glpi_tickets.itilcategories_id = 189 && datediff(if(solvedate is null, now(), solvedate), date) <= 20, glpi_tickets.itilcategories_id, NULL)) AS aditivo_prazo
			FROM glpi_tickets
			WHERE glpi_tickets.is_deleted = 0
			AND glpi_tickets.date ".$sel_date."
			".$entidade;

			$result_sla_contrato = $DB->query($sql_sla_contratos);		
			$conta_cons_contrato = $DB->numrows($result_sla_contrato);

			$distrato = $DB->result($result_sla_contrato, 0, 'distrato');
			$dispensa = $DB->result($result_sla_contrato, 0, 'dispensa');
			$cotacao = $DB->result($result_sla_contrato, 0, 'cotacao');
			$aditivo = $DB->result($result_sla_contrato, 0, 'aditivo');
			$distrato_prazo = $DB->result($result_sla_contrato, 0, 'distrato_prazo');
			$dispensa_prazo = $DB->result($result_sla_contrato, 0, 'dispensa_prazo');
			$cotacao_prazo = $DB->result($result_sla_contrato, 0, 'cotacao_prazo');
			$aditivo_prazo = $DB->result($result_sla_contrato, 0, 'aditivo_prazo');

			//Total de Chamados Fechado Contratos
			$sql_sla_contratos = 
			"SELECT 
				COUNT(IF(glpi_tickets.itilcategories_id = 197, glpi_tickets.itilcategories_id, NULL)) AS distrato_fechado,
				COUNT(IF(glpi_tickets.itilcategories_id = 191, glpi_tickets.itilcategories_id, NULL)) AS dispensa_fechado,
				COUNT(IF(glpi_tickets.itilcategories_id = 190, glpi_tickets.itilcategories_id, NULL)) AS cotacao_fechado,
				COUNT(IF(glpi_tickets.itilcategories_id = 189, glpi_tickets.itilcategories_id, NULL)) AS aditivo_fechado
			FROM glpi_tickets
			WHERE glpi_tickets.is_deleted = 0
			AND glpi_tickets.solvedate ".$sel_date."
			".$entidade;

			$result_sla_contrato = $DB->query($sql_sla_contratos);		
			$conta_cons_contrato = $DB->numrows($result_sla_contrato);

			$distrato_fechado = $DB->result($result_sla_contrato, 0, 'distrato_fechado');
			$dispensa_fechado = $DB->result($result_sla_contrato, 0, 'dispensa_fechado');
			$cotacao_fechado = $DB->result($result_sla_contrato, 0, 'cotacao_fechado');
			$aditivo_fechado = $DB->result($result_sla_contrato, 0, 'aditivo_fechado');

			//Total de Chamados Aberto Contratos
			$sql_sla_contratos = 
			"SELECT 
				COUNT(IF(glpi_tickets.itilcategories_id = 197, glpi_tickets.itilcategories_id, NULL)) AS distrato_aberto,
				COUNT(IF(glpi_tickets.itilcategories_id = 191, glpi_tickets.itilcategories_id, NULL)) AS dispensa_aberto,
				COUNT(IF(glpi_tickets.itilcategories_id = 190, glpi_tickets.itilcategories_id, NULL)) AS cotacao_aberto,
				COUNT(IF(glpi_tickets.itilcategories_id = 189, glpi_tickets.itilcategories_id, NULL)) AS aditivo_aberto
			FROM glpi_tickets
			WHERE glpi_tickets.is_deleted = 0
			AND glpi_tickets.date ".$sel_date."
			".$entidade;

			$result_sla_contrato = $DB->query($sql_sla_contratos);		
			$conta_cons_contrato = $DB->numrows($result_sla_contrato);

			$distrato_aberto = $DB->result($result_sla_contrato, 0, 'distrato_aberto');
			$dispensa_aberto = $DB->result($result_sla_contrato, 0, 'dispensa_aberto');
			$cotacao_aberto = $DB->result($result_sla_contrato, 0, 'cotacao_aberto');
			$aditivo_aberto = $DB->result($result_sla_contrato, 0, 'aditivo_aberto');

			//Médias de Dias
				$sql_sla_contratos_dias_distrato = "
					SELECT AVG(TOTAL_WEEKDAYS(if(solvedate is null, now(), solvedate), date)) dias
					FROM glpi_tickets
					WHERE glpi_tickets.is_deleted = 0
					AND glpi_tickets.itilcategories_id = 197
					AND glpi_tickets.solvedate ".$sel_date."
					".$entidade;

				$sql_sla_contratos_dias_dispensa = "
					SELECT AVG(TOTAL_WEEKDAYS(if(solvedate is null, now(), solvedate), date)) dias
					FROM glpi_tickets
					WHERE glpi_tickets.is_deleted = 0
					AND glpi_tickets.itilcategories_id = 191
					AND glpi_tickets.solvedate ".$sel_date."
					".$entidade;

				$sql_sla_contratos_dias_cotacao = "
					SELECT AVG(TOTAL_WEEKDAYS(if(solvedate is null, now(), solvedate), date)) dias
					FROM glpi_tickets
					WHERE glpi_tickets.is_deleted = 0
					AND glpi_tickets.itilcategories_id = 190
					AND glpi_tickets.solvedate ".$sel_date."
					".$entidade;

				$sql_sla_contratos_dias_aditivo = "
					SELECT AVG(TOTAL_WEEKDAYS(if(solvedate is null, now(), solvedate), date)) dias
					FROM glpi_tickets
					WHERE glpi_tickets.is_deleted = 0
					AND glpi_tickets.itilcategories_id = 189
					AND glpi_tickets.solvedate ".$sel_date."
					".$entidade;

				$result_dias_distrato = $DB->query($sql_sla_contratos_dias_distrato);
				$result_dias_dispensa = $DB->query($sql_sla_contratos_dias_dispensa);
				$result_dias_cotacao = $DB->query($sql_sla_contratos_dias_cotacao);
				$result_dias_aditivo = $DB->query($sql_sla_contratos_dias_aditivo);
				
				$dias_distrato = (int) $DB->result($result_dias_distrato, 0, 'dias');
				$dias_dispensa = (int) $DB->result($result_dias_dispensa, 0, 'dias');
				$dias_cotacao = (int) $DB->result($result_dias_cotacao, 0, 'dias');
				$dias_aditivo = (int) $DB->result($result_dias_aditivo, 0, 'dias');

						$result_grp = $DB->query($sql_grp);

						//logo						
						if (file_exists('../../../../pics/logoverde.svg')) {
							$logo = "../../../../pics/logoverde.svg";
							$imgsize = "width:200px; height:100px;";
						}
						//else {
						if (!file_exists('../../../../pics/logoverde.svg')) {
							if ($CFG_GLPI['version'] >= 0.90) {
								$logo = "../../../../pics/logo-glpi-login.png";
								$imgsize = "background-color:#000;";
							} else {
								$logo = "../../../../pics/logo-glpi-login.png";
								$imgsize = "";
							}
						}

						//Calculo para cotação
						$query_chamados = "
		SELECT DISTINCT ticket_id
		FROM glpi_tickets_status 
		INNER JOIN glpi_tickets on glpi_tickets.id = glpi_tickets_status.ticket_id
		WHERE glpi_tickets.solvedate $sel_date
		AND glpi_tickets.is_deleted = 0
		AND glpi_tickets.itilcategories_id = 190
		$entidade
	";

	

						$query_cont = "
		SELECT count(DISTINCT ticket_id) as total from glpi_tickets_status
		INNER JOIN glpi_tickets on glpi_tickets.id = glpi_tickets_status.ticket_id
		WHERE glpi_tickets.solvedate $sel_date
		AND glpi_tickets.is_deleted = 0
		AND glpi_tickets.itilcategories_id = 190
		$entidade
	";
	

						$result_cham_cont = $DB->query($query_cont)->fetch_assoc();
						$result_cham_contratos = $DB->query($query_chamados);

						$qtd_dias_cotacao_1 = 0;
						$qtd_dias_cotacao_2 = 0;


						foreach ($result_cham_contratos as $chamado) {

							$query_dias_etapa1 = "SELECT TOTAL_WEEKDAYS(
					(CASE WHEN (SELECT min(data_inicio) FROM glpi_tickets_status WHERE status_cod = 19 AND ticket_id = " . $chamado['ticket_id'] . " ) IS NULL
						THEN NOW() 
						ELSE (SELECT min(data_inicio) FROM glpi_tickets_status WHERE status_cod = 19 AND ticket_id = " . $chamado['ticket_id'] . ") 
					END),
					(CASE WHEN (SELECT max(data_fim) FROM glpi_tickets_status WHERE status_cod = 18 AND ticket_id = " . $chamado['ticket_id'] . ") IS NULL
						THEN NOW() 
						ELSE (SELECT max(data_fim) FROM glpi_tickets_status WHERE status_cod = 18 AND ticket_id = " . $chamado['ticket_id'] . ") 
					END)
				) dias";
				

							$query_dias_etapa2 = "SELECT TOTAL_WEEKDAYS(
					(CASE WHEN (SELECT min(data_inicio) FROM glpi_tickets_status WHERE status_cod = 5 AND ticket_id = " . $chamado['ticket_id'] . ") IS NULL
						THEN NOW() 
						ELSE (SELECT min(data_inicio) FROM glpi_tickets_status WHERE status_cod = 5 AND ticket_id = " . $chamado['ticket_id'] . ") 
					END),
					(CASE WHEN (SELECT max(data_inicio) FROM glpi_tickets_status WHERE status_cod = 20 AND ticket_id = " . $chamado['ticket_id'] . ") IS NULL
						THEN NOW() 
						ELSE (SELECT max(data_inicio) FROM glpi_tickets_status WHERE status_cod = 20 AND ticket_id = " . $chamado['ticket_id'] . ") 
					END)
				) dias";


							$result_etapa1 = $DB->query($query_dias_etapa1)->fetch_assoc();
							$result_etapa2 = $DB->query($query_dias_etapa2)->fetch_assoc();

							$qtd_dias_cotacao_1 = intval($qtd_dias_cotacao_1) + intval($result_etapa1['dias']);
							$qtd_dias_cotacao_2 = intval($qtd_dias_cotacao_2) + intval($result_etapa2['dias']);
						}

						//Calculo para dispensa
						$query_chamados_dispensa = "
		SELECT DISTINCT ticket_id
		FROM glpi_tickets_status 
		INNER JOIN glpi_tickets on glpi_tickets.id = glpi_tickets_status.ticket_id
		WHERE glpi_tickets.solvedate $sel_date
		AND glpi_tickets.is_deleted = 0
		AND glpi_tickets.itilcategories_id = 191
		$entidade
	";

						$query_cont_dispensa = "
		SELECT count(DISTINCT ticket_id) as total from glpi_tickets_status
		INNER JOIN glpi_tickets on glpi_tickets.id = glpi_tickets_status.ticket_id
		WHERE glpi_tickets.solvedate $sel_date
		AND glpi_tickets.is_deleted = 0
		AND glpi_tickets.itilcategories_id = 191
		$entidade
	";
						$result_cham_dispensa_cont = $DB->query($query_cont_dispensa)->fetch_assoc();
						$result_cham_dispensa_contratos = $DB->query($query_chamados_dispensa);

						$qtd_dias_dispensa_1 = 0;
						$qtd_dias_dispensa_2 = 0;

						foreach ($result_cham_dispensa_contratos as $chamado) {


							$query_dias_etapa1 = "SELECT TOTAL_WEEKDAYS(
					(CASE WHEN (SELECT min(data_inicio) FROM glpi_tickets_status WHERE status_cod = 19 AND ticket_id = " . $chamado['ticket_id'] . " ) IS NULL
						THEN NOW() 
						ELSE (SELECT min(data_inicio) FROM glpi_tickets_status WHERE status_cod = 19 AND ticket_id = " . $chamado['ticket_id'] . ") 
					END),
					(CASE WHEN (SELECT max(data_fim) FROM glpi_tickets_status WHERE status_cod = 2 AND ticket_id = " . $chamado['ticket_id'] . ") IS NULL
						THEN NOW() 
						ELSE (SELECT max(data_fim) FROM glpi_tickets_status WHERE status_cod = 2 AND ticket_id = " . $chamado['ticket_id'] . ") 
					END)
				) dias";

							$query_dias_etapa2 = "SELECT TOTAL_WEEKDAYS(
					(CASE WHEN (SELECT min(data_inicio) FROM glpi_tickets_status WHERE status_cod = 5 AND ticket_id = " . $chamado['ticket_id'] . ") IS NULL
						THEN NOW() 
						ELSE (SELECT min(data_inicio) FROM glpi_tickets_status WHERE status_cod = 5 AND ticket_id = " . $chamado['ticket_id'] . ") 
					END),
					(CASE WHEN (SELECT max(data_inicio) FROM glpi_tickets_status WHERE status_cod = 20 AND ticket_id = " . $chamado['ticket_id'] . ") IS NULL
						THEN NOW() 
						ELSE (SELECT max(data_inicio) FROM glpi_tickets_status WHERE status_cod = 20 AND ticket_id = " . $chamado['ticket_id'] . ") 
					END)
				) dias";

							$result_etapa1 = $DB->query($query_dias_etapa1)->fetch_assoc();
							$result_etapa2 = $DB->query($query_dias_etapa2)->fetch_assoc();

							$qtd_dias_dispensa_1 = intval($qtd_dias_dispensa_1) + intval($result_etapa1['dias']);
							$qtd_dias_dispensa_2 = intval($qtd_dias_dispensa_2) + intval($result_etapa2['dias']);
						}

						//Calculo para aditivo contrato
						$query_chamados_aditivo = "
		SELECT * 
		FROM glpi_tickets 
		WHERE glpi_tickets.solvedate $sel_date
		AND glpi_tickets.is_deleted = 0
		AND glpi_tickets.itilcategories_id = 189
		AND glpi_tickets.solvedate is not null
		$entidade
	";

						$query_cont_aditivo = "
		SELECT count(DISTINCT glpi_tickets.id) as total 
		from glpi_tickets
		WHERE glpi_tickets.solvedate $sel_date
		AND glpi_tickets.is_deleted = 0
		AND glpi_tickets.itilcategories_id = 189
		AND glpi_tickets.solvedate is not null
		$entidade
	";

		$result_cham_aditivo_cont = $DB->query($query_cont_aditivo)->fetch_assoc();
		$result_cham_aditivo_contratos = $DB->query($query_chamados_aditivo);
		$qtd_dias_aditivo = 0;
		$entrouif = 0;
		$entrouelse = 0;
		//$qtd_dias_aditivo_2 = 0;
		//print_r($result_cham_aditivo_contratos);exit();
		foreach ($result_cham_aditivo_contratos as $chamado) {

			//print_r($chamado['content']);
			$content = explode(' Insira Data de Inicio :', $chamado['content']);
			$data_inicio_aditivo = date('Y-m-d H:i:s', strtotime(substr($content[1], 16, 10)));
			$data_fim_aditivo = $chamado['solvedate'];

			$datetime1 = new DateTime($data_inicio_aditivo);
			$datetime2 = new DateTime($data_fim_aditivo);

			// print_r(substr($content[1], 16, 10)); print_r(' <--> ');  print_r($data_inicio_aditivo); print_r(' <--> '); print_r($data_fim_aditivo); print_r(': ');
			// print_r($diferenca); print_r(' <--> ');  print_r($chamado['id']);
			// print_r('<br>');
			$matches;
			$regex = "/[0-9]{2}\-[0-9]{2}\-[0-9]{4}/";
			$title = 'svvkldjkljdsklvjkldvlksdjv> 10-01-2000 ssgdfdsgsdgdgsdg';
			preg_match_all ($regex, $chamado['content'], $matches);
			
			$diferenca = get_total_days($matches[0][0], $data_fim_aditivo);

			// $diferenca = date_diff($datetime1, $datetime2);
			// if ($data_inicio_aditivo >= $data_fim_aditivo) {
			// 	$entrouif++;
			// 	$qtd_dias_aditivo = $qtd_dias_aditivo + $diferenca->d;
			// } else {
			// 	$entrouelse++;
			// 	$qtd_dias_aditivo = $qtd_dias_aditivo - $diferenca->d;
			// }

			if ($data_inicio_aditivo >= $data_fim_aditivo) {
				$entrouif++;
				$qtd_dias_aditivo = $qtd_dias_aditivo + $diferenca;
			} else {
				$entrouelse++;
				$qtd_dias_aditivo = $qtd_dias_aditivo - $diferenca;
			}
		}

		//print_r($qtd_dias_aditivo); exit;


		

		//________________________________________________________________________________________________________________________________________________________________________________________________________________________

		$aditivos_renovados = (($qtd_dias_cotacao_2 + $qtd_dias_cotacao_1) + ($qtd_dias_dispensa_2 + $qtd_dias_dispensa_1)) / ($result_cham_cont['total'] + $result_cham_dispensa_cont['total']);

		$aditivos_renovados = number_format($aditivos_renovados, 2, ',', ' ');
		$aditivos_dias = $qtd_dias_aditivo / $result_cham_aditivo_cont['total'];
		$aditivos_dias = number_format($aditivos_dias, 2, ',', ' ');
		//________________________________________________________________________________________________________________________________________________________________________________________________________________________


		//Calculo Leadtime
		$query_stat_lead_time = "
		SELECT
		SUM(case when glpi_tickets_status.status_cod = 1 then glpi_tickets_status.data_cons else 0 end) AS new,
		SUM(case when glpi_tickets_status.status_cod = 2 then glpi_tickets_status.data_cons else 0 end) AS assig,
		SUM(case when glpi_tickets_status.status_cod = 3 then glpi_tickets_status.data_cons else 0 end) AS plan,
		SUM(case when glpi_tickets_status.status_cod = 4 then glpi_tickets_status.data_cons else 0 end) AS pend,
		SUM(case when glpi_tickets_status.status_cod = 5 then glpi_tickets_status.data_cons else 0 end) AS solve,
		SUM(case when glpi_tickets_status.status_cod = 6 then glpi_tickets_status.data_cons else 0 end) AS close,
		SUM(case when glpi_tickets_status.status_cod = 12 then glpi_tickets_status.data_cons else 0 end) AS qualificacao,
		SUM(case when glpi_tickets_status.status_cod = 23 then glpi_tickets_status.data_cons else 0 end) AS atribuido,
		SUM(case when glpi_tickets_status.status_cod = 13 then glpi_tickets_status.data_cons else 0 end) AS validacao_tr,
		SUM(case when glpi_tickets_status.status_cod = 14 then glpi_tickets_status.data_cons else 0 end) AS publicacao,
		SUM(case when glpi_tickets_status.status_cod = 15 then glpi_tickets_status.data_cons else 0 end) AS parecer_habilitacao,
		SUM(case when glpi_tickets_status.status_cod = 16 then glpi_tickets_status.data_cons else 0 end) AS validacao_tecnica,
		SUM(case when glpi_tickets_status.status_cod = 17 then glpi_tickets_status.data_cons else 0 end) AS resultados,
		SUM(case when glpi_tickets_status.status_cod = 18 then glpi_tickets_status.data_cons else 0 end) AS homologacao,
		SUM(case when glpi_tickets_status.status_cod = 19 then glpi_tickets_status.data_cons else 0 end) AS juridico,
		SUM(case when glpi_tickets_status.status_cod = 20 then glpi_tickets_status.data_cons else 0 end) AS validacao_interna,
		SUM(case when glpi_tickets_status.status_cod = 21 then glpi_tickets_status.data_cons else 0 end) AS envio_contrato,
		SUM(case when glpi_tickets_status.status_cod = 22 then glpi_tickets_status.data_cons else 0 end) AS formalizacao,
		SUM(case when glpi_tickets_status.status_cod = 24 then glpi_tickets_status.data_cons else 0 end) AS pendente_unidade,
		SUM(case when glpi_tickets_status.status_cod = 25 then glpi_tickets_status.data_cons else 0 end) AS publicacao_errata,
		SUM(case when glpi_tickets_status.status_cod = 26 then glpi_tickets_status.data_cons else 0 end) AS prorrogacao,
		SUM(case when glpi_tickets_status.status_cod = 27 then glpi_tickets_status.data_cons else 0 end) AS diligencia,
		SUM(case when glpi_tickets_status.status_cod = 28 then glpi_tickets_status.data_cons else 0 end) AS recurso,

		count( IF(glpi_tickets_status.status_cod=1, glpi_tickets_status.id, NULL)  ) AS new_count,
		count( IF(glpi_tickets_status.status_cod=2, glpi_tickets_status.id, NULL)  ) AS assig_count,
		count( IF(glpi_tickets_status.status_cod=3, glpi_tickets_status.id, NULL)  ) AS plan_count,
		count( IF(glpi_tickets_status.status_cod=4, glpi_tickets_status.id, NULL)  ) AS pend_count,
		count( IF(glpi_tickets_status.status_cod=5, glpi_tickets_status.id, NULL)  ) AS solve_count,
		count( IF(glpi_tickets_status.status_cod=6, glpi_tickets_status.id, NULL)  ) AS close_count,
		count( IF(glpi_tickets_status.status_cod=12, glpi_tickets_status.id, NULL) ) AS qualificacao_count,
		count( IF(glpi_tickets_status.status_cod=23, glpi_tickets_status.id, NULL) ) AS atribuido_count,
		count( IF(glpi_tickets_status.status_cod=13, glpi_tickets_status.id, NULL) ) AS validacao_tr_count,
		count( IF(glpi_tickets_status.status_cod=14, glpi_tickets_status.id, NULL) ) AS publicacao_count,
		count( IF(glpi_tickets_status.status_cod=15, glpi_tickets_status.id, NULL) ) AS parecer_habilitacao_count,
		count( IF(glpi_tickets_status.status_cod=16, glpi_tickets_status.id, NULL) ) AS validacao_tecnica_count,
		count( IF(glpi_tickets_status.status_cod=17, glpi_tickets_status.id, NULL) ) AS resultados_count,
		count( IF(glpi_tickets_status.status_cod=18, glpi_tickets_status.id, NULL) ) AS homologacao_count,
		count( IF(glpi_tickets_status.status_cod=19, glpi_tickets_status.id, NULL) ) AS juridico_count,
		count( IF(glpi_tickets_status.status_cod=20, glpi_tickets_status.id, NULL) ) AS validacao_interna_count,
		count( IF(glpi_tickets_status.status_cod=21, glpi_tickets_status.id, NULL) ) AS envio_contrato_count,
		count( IF(glpi_tickets_status.status_cod=22, glpi_tickets_status.id, NULL) ) AS formalizacao_count,
		count( IF(glpi_tickets_status.status_cod=24, glpi_tickets_status.id, NULL) ) AS pendente_unidade_count,
		count( IF(glpi_tickets_status.status_cod=25, glpi_tickets_status.id, NULL) ) AS publicacao_errata_count,
		count( IF(glpi_tickets_status.status_cod=26, glpi_tickets_status.id, NULL) ) AS prorrogacao_count,
		count( IF(glpi_tickets_status.status_cod=27, glpi_tickets_status.id, NULL) ) AS diligencia_count,
		count( IF(glpi_tickets_status.status_cod=28, glpi_tickets_status.id, NULL) ) AS recurso_count

		FROM glpi_tickets_status
		INNER JOIN glpi_tickets on glpi_tickets.id = glpi_tickets_status.ticket_id
		INNER JOIN glpi_itilcategories on glpi_tickets.itilcategories_id = glpi_itilcategories.id
		WHERE glpi_tickets.is_deleted = '0'
		AND glpi_tickets_status.data_fim is not null
		AND glpi_itilcategories.id = 190
		AND glpi_tickets.solvedate " . $sel_date . "			
		" . $entidade . "";
		
		$result_stat_lead_time = $DB->query($query_stat_lead_time);

		$query_contratos = "SELECT id, entities_id FROM glpi_entities WHERE id in (" . $sel_ent . ")";

		$result_contratos = $DB->query($query_contratos);
		$sel_ent_contratos = $result_contratos->fetch_all();
	
		$mostrar = false;
		foreach ($sel_ent_contratos as $item) {
			if ($item[0] == 17 || $item[1] == 17) {
				$mostrar = true;
			}
		}
		

		$new_lead = number_format((($DB->result($result_stat_lead_time, 0, 'new') + 0) / ($DB->result($result_stat_lead_time, 0, 'new_count') + 0)), 2, ',', ' ');
		$assig_lead = number_format((($DB->result($result_stat_lead_time, 0, 'assig') + 0) / ($DB->result($result_stat_lead_time, 0, 'assig_count') + 0)), 2, ',', ' ');
		$plan_lead = number_format((($DB->result($result_stat_lead_time, 0, 'plan') + 0) / ($DB->result($result_stat_lead_time, 0, 'plan_count') + 0)), 2, ',', ' ');
		$pend_lead = number_format((($DB->result($result_stat_lead_time, 0, 'pend') + 0) / ($DB->result($result_stat_lead_time, 0, 'pend_count') + 0)), 2, ',', ' ');
		$solve_lead = number_format((($DB->result($result_stat_lead_time, 0, 'solve') + 0) / ($DB->result($result_stat_lead_time, 0, 'solve_count') + 0)), 2, ',', ' ');
		$close_lead = number_format((($DB->result($result_stat_lead_time, 0, 'close') + 0) / ($DB->result($result_stat_lead_time, 0, 'close_count') + 0)), 2, ',', ' ');
		$atribuido_lead = number_format((($DB->result($result_stat_lead_time, 0, 'atribuido') + 0) / ($DB->result($result_stat_lead_time, 0, 'atribuido_count') + 0)), 2, ',', ' ');
		$validacao_tr_lead = number_format((($DB->result($result_stat_lead_time, 0, 'validacao_tr') + 0) / ($DB->result($result_stat_lead_time, 0, 'validacao_tr_count') + 0)), 2, ',', ' ');
		$publicacao_lead = number_format((($DB->result($result_stat_lead_time, 0, 'publicacao') + 0) / ($DB->result($result_stat_lead_time, 0, 'publicacao_count') + 0)), 2, ',', ' ');
		$parecer_habilitacao_lead = number_format((($DB->result($result_stat_lead_time, 0, 'parecer_habilitacao') + 0) / ($DB->result($result_stat_lead_time, 0, 'parecer_habilitacao_count') + 0)), 2, ',', ' ');
		$validacao_tecnica_lead = number_format((($DB->result($result_stat_lead_time, 0, 'validacao_tecnica') + 0) / ($DB->result($result_stat_lead_time, 0, 'validacao_tecnica_count') + 0)), 2, ',', ' ');
		$resultados_lead = number_format((($DB->result($result_stat_lead_time, 0, 'resultados') + 0) / ($DB->result($result_stat_lead_time, 0, 'resultados_count') + 0)), 2, ',', ' ');
		$homologacao_lead = number_format((($DB->result($result_stat_lead_time, 0, 'homologacao') + 0) / ($DB->result($result_stat_lead_time, 0, 'homologacao_count') + 0)), 2, ',', ' ');
		$juridico_lead = number_format((($DB->result($result_stat_lead_time, 0, 'juridico') + 0) / ($DB->result($result_stat_lead_time, 0, 'juridico_count') + 0)), 2, ',', ' ');
		$validacao_interna_lead = number_format((($DB->result($result_stat_lead_time, 0, 'validacao_interna') + 0) / ($DB->result($result_stat_lead_time, 0, 'validacao_interna_count') + 0)), 2, ',', ' ');
		$envio_contrato_lead = number_format((($DB->result($result_stat_lead_time, 0, 'envio_contrato') + 0) / ($DB->result($result_stat_lead_time, 0, 'envio_contrato_count') + 0)), 2, ',', ' ');
		$formalizacao_lead = number_format((($DB->result($result_stat_lead_time, 0, 'formalizacao') + 0) / ($DB->result($result_stat_lead_time, 0, 'formalizacao_count') + 0)), 2, ',', ' ');
		$pendente_unidade_lead = number_format((($DB->result($result_stat_lead_time, 0, 'pendente_unidade') + 0) / ($DB->result($result_stat_lead_time, 0, 'pendente_unidade_count') + 0)), 2, ',', ' ');
		$publicacao_errata_lead = number_format((($DB->result($result_stat_lead_time, 0, 'publicacao_errata') + 0) / ($DB->result($result_stat_lead_time, 0, 'publicacao_errata_count') + 0)), 2, ',', ' ');
		$prorrogacao_lead = number_format((($DB->result($result_stat_lead_time, 0, 'prorrogacao') + 0) / ($DB->result($result_stat_lead_time, 0, 'prorrogacao_count') + 0)), 2, ',', ' ');
		$diligencia_lead = number_format((($DB->result($result_stat_lead_time, 0, 'diligencia') + 0) / ($DB->result($result_stat_lead_time, 0, 'diligencia_count') + 0)), 2, ',', ' ');
		$recurso_lead = number_format((($DB->result($result_stat_lead_time, 0, 'recurso') + 0) / ($DB->result($result_stat_lead_time, 0, 'recurso_count') + 0)), 2, ',', ' ');

		//RETIRAR "NAN"
		$new_lead != 'nan' ? $new_lead : $new_lead = 0;
		$assig_lead != 'nan' ? $assig_lead : $assig_lead = 0;
		$plan_lead != 'nan' ? $plan_lead : $plan_lead = 0;
		$pend_lead != 'nan' ? $pend_lead : $pend_lead = 0;
		$solve_lead != 'nan' ? $solve_lead : $solve_lead = 0;
		$close_lead != 'nan' ? $close_lead : $close_lead = 0;
		$atribuido_lead != 'nan' ? $atribuido_lead : $atribuido_lead = 0;
		$validacao_tr_lead != 'nan' ? $validacao_tr_lead : $validacao_tr_lead = 0;
		$publicacao_lead != 'nan' ? $publicacao_lead : $publicacao_lead = 0;
		$parecer_habilitacao_lead != 'nan' ? $parecer_habilitacao_lead : $parecer_habilitacao_lead = 0;
		$validacao_tecnica_lead != 'nan' ? $validacao_tecnica_lead : $validacao_tecnica_lead = 0;
		$resultados_lead != 'nan' ? $resultados_lead : $resultados_lead = 0;
		$homologacao_lead != 'nan' ? $homologacao_lead : $homologacao_lead = 0;
		$juridico_lead != 'nan' ? $juridico_lead : $juridico_lead = 0;
		$validacao_interna_lead != 'nan' ? $validacao_interna_lead : $validacao_interna_lead = 0;
		$envio_contrato_lead != 'nan' ? $envio_contrato_lead : $envio_contrato_lead = 0;
		$formalizacao_lead != 'nan' ? $formalizacao_lead : $formalizacao_lead = 0;
		$pendente_unidade_lead != 'nan' ? $pendente_unidade_lead : $pendente_unidade_lead = 0;
		$publicacao_errata_lead != 'nan' ? $publicacao_errata_lead : $publicacao_errata_lead = 0;
		$prorrogacao_lead != 'nan' ? $prorrogacao_lead : $prorrogacao_lead = 0;
		$diligencia_lead != 'nan' ? $diligencia_lead : $diligencia_lead = 0;
		$recurso_lead != 'nan' ? $recurso_lead : $recurso_lead = 0;
		$aditivos_renovados != 'nan' ? $aditivos_renovados : $aditivos_renovados = 0;
		$aditivos_dias != 'nan' ? $aditivos_dias : $aditivos_dias = 0;



		$media_lead = ($new_lead + $assig_lead + $plan_lead + $pend_lead + $solve_lead + $close_lead + $atribuido_lead + $validacao_tr_lead + $publicacao_lead + $parecer_habilitacao_lead + $validacao_tecnica_lead + $resultados_lead + $homologacao_lead + $juridico_lead + $validacao_interna_lead + $envio_contrato_lead + $formalizacao_lead + $pendente_unidade_lead + $publicacao_errata_lead + $prorrogacao_lead + $diligencia_lead + $recurso_lead) / 22;

		$content = "
		<div class='well info_box fluid col-md-12 report' style='margin-left: -1px;'>	
 			<div class='btn-right'> <button class='btn btn-primary btn-sm' type='button' onclick=window.open(\"./rel_sint_all_pdf.php?con=1&date1=" . $data_ini2 . "&date2=" . $data_fin2 . "\",\"_blank\")>Export PDF</button>  </div>	
			
			 <div id='logo' class='fluid'>
				 <div class='col-md-2' ><img src='" . $logo . "' alt='GLPI' style='" . $imgsize . "'> </div>
				 <div class='col-md-8' style='height:120px; text-align:center; margin:auto;'><h3 style='vertical-align:middle;' >" . __('Summary Report', 'dashboard') . " </h3></div>
			 </div>
									
			 <table id='data' class='table table-condensed table-striped' style='font-size: 16px; width:55%; margin:auto; margin-top:5px; margin-bottom:25px;'>			
			 <tbody>				
			 <tr>
			 <td>" . __('Period', 'dashboard') . " </td>";

						if ($data_ini2 == $data_fin2) {
							$content .= "<td align='right'>" . conv_data($data_ini2) . "</td>";
						} else {
							$content .= "<td align='right'>" . conv_data($data_ini2) . " to " . conv_data($data_fin2) . "</td>";
						}

						$content .= "					
			 </tr>
			
			 <tr>
			 <td>" . __('Date') . " </td>
			 <td align='right'>" . conv_data_hora(date("Y-m-d H:i")) . "</td>			
			 </tr>
			 </tbody>
			 </table>			 

			 <table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
			 <thead>
			 <tr>
			 <th colspan='2' style='text-align:center; background:#286090; color:#fff;'>" . __('Tickets', 'dashboard') . "</th>						
			 </tr>
			 </thead>	

			 <tbody>			
			 <tr>
			 <td>" . __('Tickets Total', 'dashboard') . "</td>
			 <td align='right'>" . $total_cham . "</td>			
			 </tr>			
			
			 <tr>
			 <td>" . _n('Day', 'Days', 2) . "</td>
			 <td align='right'>" . $numdias . "</td>
			 </tr>				
			 <tr>
			 <td>" . __('Tickets', 'dashboard') . " " . __('By day') . " - " . __('Average') . "</td>
			 <td align='right'>" . round($total_cham / $numdias, 0) . "</td>
			 </tr>			
			 <tr>
			 <td>" . __('Average time to closure') . "</td>
			 <td align='right'>" . time_hrs($avgtime) . "</td>
			 </tr>			
			 <tr>
			 <td>" . ('% Contratos formalizados') . "</td>
			 <td align='right'>" . $aditivos_renovados . "</td>
			 </tr>		
			 <tr>
			 <td>" . ('Média de dias de aditivos renovados') . "</td>
			 <td align='right'>" . $aditivos_dias . "</td>
			 </tr>				
		    </tbody> </table>		   		    

			 <table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
			 <thead>
			 <tr>
			 <th colspan='3' style='text-align:center; background:#286090; color:#fff;'>" . __('Tickets by Status', 'dashboard') . "</th>						
			 </tr>
			 </thead>	

			 <tbody>
			 <tr>
			 <td align='left'> <b>Status</b> </td>
			 <td align='center'> <b>Total Chamados</b> </td>
			 <td align='center'> <b>Tempo Média Chamados</b> </td>
			 </tr>

			 <tr>
			 <td>" . _x('status', 'New') . "</td>
			 <td align='center'>" . $new . "</td>			
			 <td align='center'>" . $new_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . __('Assigned') . "</td>
			 <td align='center'>" . $assig . "</td>			
			 <td align='center'>" . $assig_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . __('Planned') . "</td>
			 <td align='center'>" . $plan . "</td>			
			 <td align='center'>" . $plan_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . __('Pending') . "</td>
			 <td align='center'>" . $pend . "</td>			
			 <td align='center'>" . $pend_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . __('Solved', 'dashboard') . "</td>
			 <td align='center'>" . $solve . "</td>			
			 <td align='center'>" . $solve_lead . "</td>			
			 </tr>	

			 <tr>
			 <td>" . __('Closed') . "</td>
			 <td align='center'>" . $close . "</td>			
			 <td align='center'>" . $close_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Atribuido' . "</td>
			 <td align='center'>" . $atribuido . "</td>			
			 <td align='center'>" . $atribuido_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Validacão TR' . "</td>
			 <td align='center'>" . $validacao_tr . "</td>			
			 <td align='center'>" . $validacao_tr_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Publicação' . "</td>
			 <td align='center'>" . $publicacao . "</td>			
			 <td align='center'>" . $publicacao_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Parecer Habilitação' . "</td>
			 <td align='center'>" . $parecer_habilitacao . "</td>			
			 <td align='center'>" . $parecer_habilitacao_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Validação Técnica' . "</td>
			 <td align='center'>" . $validacao_tecnica . "</td>			
			 <td align='center'>" . $validacao_tecnica_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Resultados' . "</td>
			 <td align='center'>" . $resultados . "</td>			
			 <td align='center'>" . $resultados_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Homologação' . "</td>
			 <td align='center'>" . $homologacao . "</td>			
			 <td align='center'>" . $homologacao_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Juridico' . "</td>
			 <td align='center'>" . $juridico . "</td>			
			 <td align='center'>" . $juridico_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Validação Interna' . "</td>
			 <td align='center'>" . $validacao_interna . "</td>			
			 <td align='center'>" . $validacao_interna_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Envio de Contrato' . "</td>
			 <td align='center'>" . $envio_contrato . "</td>			
			 <td align='center'>" . $envio_contrato_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Formalização' . "</td>
			 <td align='center'>" . $formalizacao . "</td>			
			 <td align='center'>" . $formalizacao_lead . "</td>			
			 </tr>
			 
			 <tr>
			 <td>" . 'Pendente Unidade' . "</td>
			 <td align='center'>" . $pendente_unidade . "</td>			
			 <td align='center'>" . $pendente_unidade_lead . "</td>			
			 </tr>
			 
			 <tr>
			 <td>" . 'Publicação de Errata' . "</td>
			 <td align='center'>" . $publicacao_errata . "</td>			
			 <td align='center'>" . $publicacao_errata_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Prorrogação' . "</td>
			 <td align='center'>" . $prorrogacao . "</td>			
			 <td align='center'>" . $prorrogacao_lead . "</td>			
			 </tr>
			 
			 <tr>
			 <td>" . 'Diligência' . "</td>
			 <td align='center'>" . $diligencia . "</td>			
			 <td align='center'>" . $diligencia_lead . "</td>			
			 </tr>

			 <tr>
			 <td>" . 'Recurso' . "</td>
			 <td align='center'>" . $recurso . "</td>			
			 <td align='center'>" . $recurso_lead . "</td>			
			 </tr>			 							
													
		    </tbody> </table>
			";

			if($mostrar)
			{
				$content .= "
					<table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
						<thead>
							<tr>
							<th colspan='6' style='text-align:center; background:#286090; color:#fff;'>Incidentes </th>										
							</tr>
						</thead>
						<tbody> 
							<tr>
								<td style='text-align:left; font-weight:bold; cursor:pointer;'> ". __('Solicitações') ." </td>
								<td style='font-weight:bold; text-align: center; cursor:pointer;'> ".__('Total de Chamados')." </td>
								<td style='text-align:center; font-weight:bold; cursor:pointer;'> ". __('Opened','dashboard') ."</td>
								<td style='text-align:center; font-weight:bold; cursor:pointer;'> ". __('Solved','dashboard') ."</td>	
								<td style='text-align:center; font-weight:bold; cursor:pointer;'> ". __('Within','dashboard') ."</td>							
								<td style='text-align:center; font-weight:bold; cursor:pointer;'> ". __('Leadtime (dias)','dashboard') ."</td>		
							</tr>
								<tr>
								<td style='vertical-align:middle;'> ". 'Cotação' ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $cotacao ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $cotacao_aberto ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $cotacao_fechado ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $cotacao_prazo ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $dias_cotacao ." </td>                        		
							</tr>
							<tr>
								<td style='vertical-align:middle;'> ". 'Dispensa de Cotação' ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $dispensa ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $dispensa_aberto ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $dispensa_fechado ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $dispensa_prazo ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $dias_dispensa ." </td>                        		
							</tr>
							<tr>
								<td style='vertical-align:middle;'> ". 'Aditivo' ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $aditivo ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $aditivo_aberto ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $aditivo_fechado ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $aditivo_prazo ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $dias_aditivo ." </td>                        		
							</tr>
							<tr>
								<td style='vertical-align:middle; '> ". 'Distrato' ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $distrato ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $distrato_aberto ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $distrato_fechado ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $distrato_prazo ." </td>
								<td style='vertical-align:middle; text-align:center;'> ". $dias_distrato ." </td>                        		
							</tr>
						</tbody>
					</table>
				";
			}

			if(!$mostrar)
			{	

				$content.= "
					<table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
					<thead>
					<tr>
					<th colspan='2' style='text-align:center; background:#286090; color:#fff;'>Top 5 - " . __('Tickets', 'dashboard') . " " . __('by Group', 'dashboard') . "</th>						
					</tr>
					</thead>	

					<tbody>";

								while ($row = $DB->fetch_assoc($result_grp)) {
									$content .= "<tr>
						<td>" . $row['name'] . "</td>
						<td align='right'>" . $row['conta'] . "</td>			
						</tr> ";
								}

								$content .= "	 					
					</tbody> </table>
				";
			} 			  			 
			$content.=	"	
				<table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
				<thead>
				<tr>
				<th colspan='2' style='text-align:center; background:#286090; color:#fff;'>" . __('Tickets', 'dashboard') . " " . __('by Technician', 'dashboard') . "</th>						
				</tr>
				</thead>	

				<tbody>";

							while ($row_tec = $DB->fetch_assoc($result_tec)) {
								$content .= "<tr>
					<td>" . $row_tec['name'] . " " . $row_tec['sname'] . "</td>
					<td align='right'>" . $row_tec['conta'] . "</td>			
					</tr> ";
							}
							$content .= "					
				</tbody> </table>

				<table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
				<thead>
				<tr>
				<th colspan='2' style='text-align:center; background:#286090; color:#fff;'>" . __('Tickets', 'dashboard') . " " . __('by Requester', 'dashboard') . "</th>						
				</tr>
				</thead>	

				<tbody>";

						while ($row_req = $DB->fetch_assoc($result_req)) {
							$content .= "<tr>
				 <td>" . $row_req['name'] . " " . $row_req['sname'] . "</td>
				 <td align='right'>" . $row_req['conta'] . "</td>			
				 </tr> ";
						}

						$content .= "</tbody></table></div> ";
					} else {
						$content = '';
						echo "
				<div id='nada_rel' class='well info_box fluid col-md-12'>
				<table class='table' style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
				<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
				<tr></tr>
				</table></div>";
					}
				} else {
					$content = '';
				}

				//output report
				echo $content;
				?>

				<script type="text/javascript" charset="utf-8">
					$(document).ready(function() {
						$("#sel1").select2({
							dropdownAutoWidth: true
						});
					});
				</script>
			</div>
		</div>
	</div>
	</div>
</body>

</html>