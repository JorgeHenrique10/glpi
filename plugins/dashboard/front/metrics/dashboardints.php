<?php
if (isset($_REQUEST['ent'])) {
	$id_ent = $_REQUEST['ent'];
	$indexw = "indexw.php?ent=" . $id_ent;
	$indexb = "index.php?ent=" . $id_ent;
	include "metrics_ent.inc.php";
} elseif (isset($_REQUEST['grp'])) {
	$id_grp = $_REQUEST['grp'];
	$indexw = "indexw.php?grp=" . $id_grp;
	$indexb = "index.php?grp=" . $id_grp;
	include "metrics_grp.inc.php";
} else {
	$id_grp = "";
	$indexw = "indexw.php";
	$indexb = "index.php";
	include "metrics.inc.php";
}

// AGUARDANDO ATENDIMENTO

$result = $DB->query("SELECT id,name,date_creation,time_to_resolve FROM glpi.glpi_tickets where status  =  1 and entities_id = 1 
and glpi_tickets.is_deleted = '0' and entities_id = 1  and glpi_tickets.is_deleted = '0' and year(date_creation) = year(now())
and month(date_creation) = month(now())
and day(date_creation) = day(now()) ; ");

/**

 */

$infosNew = $result->fetch_all(MYSQLI_ASSOC);

$result = $DB->query(
	"SELECT
glpi_tickets.id,
glpi_tickets.name,
glpi_tickets.date_creation,
glpi_tickets.time_to_resolve,
glpi.glpi_users.firstname,
glpi.glpi_users.realname,
glpi.glpi_tickets_users.type
FROM glpi.glpi_tickets
INNER JOIN glpi.glpi_tickets_users
INNER JOIN glpi.glpi_users
WHERE glpi_tickets.status  =  1
AND glpi_tickets.entities_id = 1 
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.id = glpi_tickets_users.tickets_id	
AND glpi_tickets_users.users_id = glpi_users.id
AND year(glpi_tickets.date_creation) = year(now())
AND month(glpi_tickets.date_creation) = month(now())
AND day(glpi_tickets.date_creation) = day(now()) 
"
);

/**

 */

$infosNewAll = $result->fetch_all(MYSQLI_ASSOC);

$tecnicosHtmlNew = [];

foreach ($infosNewAll as $info) {
	if (empty($tecnicosHtmlNew[$info['id']]) && $info['type'] == '2') {
		$tecnicosHtmlNew[$info['id']] = $info['firstname'] . " " . $info['realname'];
	} else if (!empty($tecnicosHtmlNew[$info['id']]) && $info['type'] == '2') {
		$tecnicosHtmlNew[$info['id']] .= ', ' . $info['firstname'] . " " . $info['realname'];
	}
}

// EM ATENDIMENTO

$result = $DB->query("SELECT id,status,name,date_creation,time_to_resolve FROM glpi.glpi_tickets where
(status  =  2 or status = 3 or status = 4) and entities_id = 1  and glpi_tickets.is_deleted = '0' and year(date_creation) = year(now())
and month(date_creation) = month(now())
and day(date_creation) = day(now()) ;");

$infosAtendimento = $result->fetch_all(MYSQLI_ASSOC);


$result = $DB->query(
	"SELECT
	glpi_tickets.id,
	glpi_tickets.name,
	glpi_tickets.date_creation,
	glpi_tickets.time_to_resolve,
	glpi.glpi_users.firstname,
	glpi.glpi_users.realname,
	glpi.glpi_tickets_users.type
	FROM glpi.glpi_tickets
	INNER JOIN glpi.glpi_tickets_users
	INNER JOIN glpi.glpi_users
	WHERE(glpi_tickets.status  =   2 or glpi_tickets.status = 3 or glpi_tickets.status = 4)
	AND glpi_tickets.entities_id = 1 
	AND glpi_tickets.is_deleted = 0
	AND glpi_tickets.id = glpi_tickets_users.tickets_id	
	AND glpi_tickets_users.users_id = glpi_users.id
	AND year(glpi_tickets.date_creation) = year(now())
	AND month(glpi_tickets.date_creation) = month(now())
	AND day(glpi_tickets.date_creation) = day(now()) 
"
);

/**

 */

$infosAtendimentoAll = $result->fetch_all(MYSQLI_ASSOC);

$tecnicosHtmlAtendimento = [];

foreach ($infosAtendimentoAll as $info) {
	if (empty($tecnicosHtmlAtendimento[$info['id']]) && $info['type'] == '2') {
		$tecnicosHtmlAtendimento[$info['id']] = $info['firstname'] . " " . $info['realname'];
	} else if (!empty($tecnicosHtmlAtendimento[$info['id']]) && $info['type'] == '2') {
		$tecnicosHtmlAtendimento[$info['id']] .= ', ' . $info['firstname'] . " " . $info['realname'];
	}
}






// SOLUCIONADO

$result = $DB->query("SELECT id,name,date_creation,time_to_resolve FROM glpi.glpi_tickets where status  =  5 and entities_id = 1 
and glpi_tickets.is_deleted = '0' and year(date_creation) = year(now())
and month(date_creation) = month(now())
and day(date_creation) = day(now()) ;");

$infosSolucionado = $result->fetch_all(MYSQLI_ASSOC);



$result = $DB->query(
	"SELECT
	glpi_tickets.id,
	glpi_tickets.name,
	glpi_tickets.date_creation,
	glpi_tickets.time_to_resolve,
	glpi.glpi_users.firstname,
	glpi.glpi_users.realname,
	glpi.glpi_tickets_users.type
	FROM glpi.glpi_tickets
	INNER JOIN glpi.glpi_tickets_users
	INNER JOIN glpi.glpi_users
	WHERE glpi_tickets.status = 5
	AND glpi_tickets.entities_id = 1 
	AND glpi_tickets.is_deleted = 0
	AND glpi_tickets.id = glpi_tickets_users.tickets_id	
	AND glpi_tickets_users.users_id = glpi_users.id
	AND year(glpi_tickets.date_creation) = year(now())
	AND month(glpi_tickets.date_creation) = month(now())
	AND day(glpi_tickets.date_creation) = day(now()) 
"
);

/**

 */

$infosSolucionadoAll = $result->fetch_all(MYSQLI_ASSOC);

$tecnicosHtmlSolucionado = [];

foreach ($infosSolucionadoAll as $info) {
	if (empty($tecnicosHtmlSolucionado[$info['id']]) && $info['type'] == '2') {
		$tecnicosHtmlSolucionado[$info['id']] = $info['firstname'] . " " . $info['realname'];
	} else if (!empty($tecnicosHtmlSolucionado[$info['id']]) && $info['type'] == '2') {
		$tecnicosHtmlSolucionado[$info['id']] .= ', ' . $info['firstname'] . " " . $info['realname'];
	}
}


// Total do dia

$result = $DB->query("SELECT id,name,date_creation,time_to_resolve FROM glpi.glpi_tickets where  (glpi_tickets.status = 1 or glpi_tickets.status = 2 or 
glpi_tickets.status = 3 or glpi_tickets.status = 4 
or glpi_tickets.status = 5
 or glpi_tickets.status = 6
 ) and entities_id = 1 
and glpi_tickets.is_deleted = '0'
and year(date_creation) = year(now())
and month(date_creation) = month(now())
and day(date_creation) = day(now());");

$infosTotal = $result->fetch_all(MYSQLI_ASSOC);



$result = $DB->query(
	"SELECT
	glpi_tickets.id,
	glpi_tickets.name,
	glpi_tickets.date_creation,
	glpi_tickets.time_to_resolve,
	glpi.glpi_users.firstname,
	glpi.glpi_users.realname,
	glpi.glpi_tickets_users.type
	FROM glpi.glpi_tickets
	INNER JOIN glpi.glpi_tickets_users
	INNER JOIN glpi.glpi_users
	WHERE (glpi_tickets.status = 1 or glpi_tickets.status = 2 or 
	glpi_tickets.status = 3 or glpi_tickets.status = 4 
	or glpi_tickets.status = 5
	 or glpi_tickets.status = 6
	 )
	AND glpi_tickets.entities_id = 1 
	AND glpi_tickets.is_deleted = 0
	AND glpi_tickets.id = glpi_tickets_users.tickets_id	
	AND glpi_tickets_users.users_id = glpi_users.id
	AND year(glpi_tickets.date_creation) = year(now())
	AND month(glpi_tickets.date_creation) = month(now())
	AND day(glpi_tickets.date_creation) = day(now()) 
"
);

/**

 */

$infosTotalAll = $result->fetch_all(MYSQLI_ASSOC);


$tecnicosHtmlTotal = [];

foreach ($infosTotalAll as $info) {
	if (empty($tecnicosHtmlTotal[$info['id']]) && $info['type'] == '2') {
		$tecnicosHtmlTotal[$info['id']] = $info['firstname'] . " " . $info['realname'];
	} else if (!empty($tecnicosHtmlTotal[$info['id']]) && $info['type'] == '2') {
		$tecnicosHtmlTotal[$info['id']] .= ', ' . $info['firstname'] . " " . $info['realname'];
	}
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<title>GLPI - <?php echo __('Dash Geral', 'dashboard'); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="../css/bootstrap.css" rel="stylesheet">
	<link href="controlfrog.css" rel="stylesheet" media="screen">
	<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />

	<script src="../js/jquery.js"></script>
	<script src="moment.js"></script>
	<script src="jquery.easypiechart.js"></script>
	<script src="gauge.js"></script>
	<script src="chart.js"></script>
	<script src="jquery-sparkline.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="controlfrog-plugins.js"></script>
	<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />

	<script src="../js/highcharts.js" type="text/javascript"></script>
	<!--<script src="../js/highcharts-3d.js" type="text/javascript" ></script>-->
	<script src="../js/themes/dark-unica.js" type="text/javascript"></script>

	<script src="../js/modules/no-data-to-display.js" type="text/javascript"></script>
	<script src="reload.js"></script>
	<script src="reload_param.js"></script>


	<script>
		var themeColour = 'black';
	</script>
	<script src="controlfrog.js"></script>

	<style type="text/css">
		.jqstooltip {
			position: absolute;
			left: 0px;
			top: 0px;
			visibility: hidden;
			background: rgb(0, 0, 0) transparent;
			background-color: rgba(0, 0, 0, 0.6);
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
			-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
			color: white;
			font: 10px arial, san serif;
			text-align: left;
			white-space: nowrap;
			padding: 5px;
			border: 1px solid white;
			z-index: 10000;
		}

		.jqsfield {
			color: white;
			font: 10px arial, san serif;
			text-align: left;
		}
	</style>
	<style>
		/*
		.tooltip2 {
			position: absolute;
			opacity: 0;
			transition: 200ms;
			top: 105%;
			left: 50%;
			background-color: rgba(0, 0, 0, .9);
			transform: translateX(-50%);
			padding: 20px;
			padding-top: 30px;
			width: 300px;
			height: 200px;
			color: #fff;
			font-size: 18px;
			border-radius: 5px;
			visibility: hidden;
		}

		.tooltip2::after {
			content: "";
			position: absolute;
			bottom: 100%;
			left: 50%;
			margin-left: -5px;
			border-width: 5px;
			border-style: solid;
			border-color: #555 transparent transparent transparent;
		}


		.metric5 {
			position: relative;
			cursor: pointer;
		}

		.metric5:hover .tooltip2 {
			opacity: 1;
			visibility: visible;
			z-index: 100;
		}

		*/




		.tooltip2 {
			display: inline-block;
			position: relative;
			text-align: left;
			cursor: pointer;
			flex-basis: content;
		}

		.tootiptr {
			cursor: pointer;
			position: relative;
		}

		.tooltip2 h3 {
			margin-bottom: 10px;
			margin-top: 0;
		}

		.tooltip2 .bottom {
			min-width: 200px;
			/*max-width:400px;*/
			top: 40px;
			left: 8%;
			position: absolute;
			top: 100%;
			height: 350px;
			width: 550px;
			transform: translate(-50%, 0);
			padding: 20px;
			color: #666666;
			background-color: rgba(0, 0, 0, 0.95);
			font-weight: normal;
			font-size: 13px;
			border-radius: 8px;
			position: absolute;
			z-index: 99999999;
			box-sizing: border-box;
			box-shadow: 0 1px 8px rgba(0, 0, 0, 0.5);
			display: flex;
			flex-direction: column;
			opacity: 0;
			visibility: hidden;
			transition: 200ms;
			text-transform: none;
		}


		#chamados-por-tecnico .right {
			min-width: 200px;
			/*max-width:400px;*/

			left: 102%;
			position: absolute;
			height: 350px;
			width: 500px;
			transform: translate(0, -50%);
			padding: 20px;
			color: #666666;
			background-color: rgba(0, 0, 0, 0.95);
			font-weight: normal;
			font-size: 13px;
			border-radius: 8px;
			position: absolute;
			z-index: 99999999;
			box-sizing: border-box;
			box-shadow: 0 1px 8px rgba(0, 0, 0, 0.5);
			display: flex;
			flex-direction: column;
			opacity: 0;
			visibility: hidden;
			transition: 200ms;

		}

		#tr-id1:hover #id1 {
			visibility: visible;
			opacity: 1;
		}

		#tr-id2:hover #id2 {
			visibility: visible;
			opacity: 1;
		}

		#tr-id3:hover #id3 {
			visibility: visible;
			opacity: 1;
		}

		#tr-id4:hover #id4 {
			visibility: visible;
			opacity: 1;
		}

		#tr-id5:hover #id5 {
			visibility: visible;
			opacity: 1;
		}


		.right {
			text-transform: none;
		}

		.tooltip2 .bottom h3,
		.right h3 {
			color: darkgray;
		}

		.tooltip2 .bottom p,
		.right p {
			color: #f2f2f2;
		}

		.tooltip2:hover .bottom {
			visibility: visible;
			opacity: 1;
		}





		.tooltip2 .bottom i {
			position: absolute;
			bottom: 100%;
			left: 50%;
			margin-left: -12px;
			width: 24px;
			height: 12px;
		}

		.tooltip2 .bottom i::after {
			content: '';
			position: absolute;
			width: 12px;
			height: 12px;
			left: 50%;
			transform: translate(-50%, 50%) rotate(45deg);
			background-color: rgba(0, 0, 0, 0.9);
			box-shadow: 0 1px 8px rgba(0, 0, 0, 0.5);
		}

		::-webkit-scrollbar {
			width: 12px;
		}

		/* Track */
		::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
			-webkit-border-radius: 10px;
			border-radius: 10px;
		}

		/* Handle */
		::-webkit-scrollbar-thumb {
			-webkit-border-radius: 10px;
			border-radius: 10px;
			background: rgba(242, 242, 242, 0.2);
			-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
		}

		::-webkit-scrollbar-thumb:window-inactive {
			background: rgba(242, 242, 242, 0.2);
		}

		.overflowy {
			margin-top: 10px;
			padding-right: 10px;
			display: flex;
			flex-direction: column;
			overflow-y: scroll;
		}

		.flexx {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			grid-gap: 20px;
			margin-bottom: 10px;
			align-items: center;
		}

		.flexx.border {
			padding-bottom: 10px;
			border-bottom: 0.5px solid rgba(242, 242, 242, 0.3);
		}

		.flexx.border:last-child {
			border-bottom: 0;
		}


		.flexx p {
			font-size: 14px;
			margin-bottom: 0;
		}

		.flexx h4 {
			font-size: 14px;
			margin-bottom: 0;
			color: #f2f2f2;
			font-weight: bold;
		}

		.flexx.tres {
			grid-template-columns: repeat(3, 1fr);
		}



		* {
			box-sizing: border-box;
			padding: 0;
			margin: 0;
		}

		html,
		body {
			height: 100%;
			background-color: #1f1d1d;
			color: darkgray;
			font-family: sans-serif;
			text-transform: uppercase;

		}

		.own-container {
			padding: 10px;
			display: flex;
			flex-direction: column;
			width: 100%;
			height: 100vh;
		}

		.own-row.first,
		.own-row.second,
		.own-row.third,
		.own-row.fourth {
			margin: 0;
			height: 100%;
		}

		#data,
		#aguardando-atendimento,
		#em-atendimento,
		#solucionado,
		#total-do-dia,
		#media {
			flex: 1;
			padding: 0 10px;
		}



	

		h2 {
			font-size: 30px;
			text-transform: none;
			font-weight: 400;
		}

	

		#chamados-por-tecnico {
			min-width: 398px;
			flex: 1.5;
		}

		#chamados-5dias {
			min-width: 398px;
			flex: 1.5;
		}

		#chamados-mes {
			min-width: 450px;
			flex: 3;
		}


		#por-tipo-mes {
			min-width: 398px;
			flex: 1.5;
		}

		#chamados-por-categoria {
			min-width: 480px;
			flex: 3.5;
		}

		#atendimento-no-prazo {

			flex: 1;
		}


		.own-row.second {
			flex: 35;
			display: flex;
			padding: 5px 0;
			flex-wrap: wrap;
		}

		.own-row.first,
		.own-row.third {
			display: flex;
			padding: 5px 0;
			flex-wrap: wrap;
			flex: 30;
		}

		.own-row.fourth {
			flex: 5;

			display: flex;
			justify-content: flex-end;
		}

		.card {
			margin-top: 10px;
			font-size: 22px;
			min-width: 265px;
			padding: 0 10px;
			position: relative;
		}



		header {
			padding-bottom: 10px;
			border-bottom: 1px solid #4f4f4f;
			margin-bottom: 10px;
		}

		header p {
			margin: 0;
		}

		.card h3 {
			margin: 0;
		}

		#hour {
			font-size: 17px;
			margin-bottom: 10px;
		}

		h3 {
			font-size: 78px;
			color: #f2f2f2;
		}

		h4 {
			font-weight: bold;
			text-transform: none;
			font-size: 20px;
		}

		.bottom>h4 {
			margin: 0px;
		}

		.rows-container1,
		.rows-container3 {
			flex: 3;

		}

		.rows-container2 {
			flex: 3.5;


		}

		.rows-container4 {
			flex: 0.5;


		}

		@media(max-width: 1400px) {


			.tooltip2 .bottom {

				/*max-width:400px;*/
				top: 40px;
				left: 8%;
				top: 100%;
				width: 400px;
				transform: translate(0, 0);

			}


			#chamados-por-tecnico .right {


				top: 40px;

				height: 350px;
				width: 400px;
				left: 0;


			}

		}
	</style>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"> </script>

<body class="black" onload="reloadPage(); initSpark('<?php echo $quantm2; ?>'); initSparkDay('<?php echo $quantd2; ?>'); initGauge('0','100','<?php echo $gauge_val; ?>'); initPie('<?php echo $res_days; ?>'); initFunnel('<?php echo $sta_values; ?>','<?php echo $sta_labels; ?>'); initRag('<?php echo $types; ?>','<?php echo $rag_labels; ?>'); ">

	<div class="own-container">


		<div class="rows-container1">
			<div class="own-row first">
				<div class="card" id="data">
					<header>
						<p>
							Data
						</p>

					</header>
					<span id="hour">
						<?php date_default_timezone_set('America/Sao_Paulo');
						echo date('H:i:s');
						?>
					</span>
					<h2 class="data">
						<script type="text/javascript">
							var d_names = <?php echo '"' . $dia . '"'; ?>;
							var m_names = <?php echo '"' . $mes . '"'; ?>;
							var d = new Date();
							var curr_day = d.getDay();
							var curr_date = d.getDate();
							var curr_month = d.getMonth();
							var curr_year = d.getFullYear();
							document.write("<span style='font-size:30px;'>" + curr_date + " " + m_names + " " + curr_year + "</span><br><span style='font-size:30px;'> Sede </span><br>");
						</script>
					</h2>
				</div>

				<!-- //end cf-item -->
				<!--
	<div style="min-height: 0px;" class="col-lg-1 cf-item-status tickets new">
	<header>
		<p><span></span><?php echo _x('status', 'Hoje'); ?></p>
	</header>
	<div class="content" >
		<div class="metric5"><?php echo $c_hj; ?></div>
		<div class="metric-small5"></div>
	</div>
</div>
-->
				<div class="card" id="aguardando-atendimento">



					<header>
						<p>
							Aguardando Atend.
						</p>
					</header>
					<div class="tooltip2">
						<h3><?php echo $new; ?></h3>
						<div class="bottom">
							<h4>Aguardando Atendimento</h4>
							<div class="flexx" style="margin-top: 10px;">
								<h4>Nome</h4>
								<h4 style="margin-left: -10px;">Data de criação</h4>
								<h4 style="margin-left: -15px;">Data limite</h4>
								<h4 style="margin-left: -15px;">Técnicos</h4>
							</div>
							<div class="overflowy">

								<?php

								foreach ($infosNew as $info) {

								?>
									<div class="flexx border">
										<p><?php echo $info['name'] ?></p>
										<p><?php $date = new DateTime($info['date_creation']);
											echo $date->format('d / m / Y');
											?></p>
										<p>
											<?php $date = new DateTime($info['time_to_resolve']);
											echo $date->format('d / m / Y');
											?>
										</p>
										<p>
											<?php
											echo $tecnicosHtmlNew[$info['id']];
											?>
										</p>
									</div>
								<?php } ?>
							</div>


							<i></i>
						</div>
					</div>




				</div>
				<!--		
<div style="min-height: 100px;" class="col-lg-5 cf-item-status tickets assign">
	<header>
		<p><span></span><?php echo __('Assigned'); ?></p>
	</header>
	<div class="content">
		<div class="metric5"><?php echo $assigned; ?></div>
		<div class="metric-small5"></div>
	</div>
</div>

<div style="min-height: 100px;" class="col-lg-5 cf-item-status tickets pending">
	<header>
		<p><span></span><?php echo __('Pending'); ?></p>
	</header>
	<div class="content">
		<div class="metric5"><?php echo $pend; ?></div>
		<div class="metric-small5"></div>
	</div>
</div>
-->

				<div class="card" id="em-atendimento">

					<header>
						<p>
							Em Atendimento
						</p>
					</header>

					<div class="tooltip2">
						<h3><?php echo $pend + $assigned; ?></h3>
						<div class="bottom">
							<h4>Em Atendimento</h4>
							<div class="flexx" style="margin-top: 10px;">
								<h4>Nome</h4>
								<h4 style="margin-left: -10px;">Data de criação</h4>
								<h4 style="margin-left: -15px;">Data limite</h4>
								<h4 style="margin-left: -15px;">Técnicos</h4>
							</div>
							<div class="overflowy">

								<?php
								foreach ($infosAtendimento as $info) {

								?>
									<div class="flexx border">
										<p><?php echo $info['name'] ?></p>
										<p><?php $date = new DateTime($info['date_creation']);
											echo $date->format('d / m / Y');
											?></p>
										<p>
											<?php $date = new DateTime($info['time_to_resolve']);
											echo $date->format('d / m / Y');
											?>
										</p>
										<p>
											<?php
											echo $tecnicosHtmlAtendimento[$info['id']];
											?>
										</p>
									</div>

								<?php } ?>
							</div>


							<i></i>
						</div>
					</div>

				</div>

				<?php

				//Solved or closed ticktes					

				$notopen = $solved;
				$notopeny = $solvedy;
				$tit_notopen = __('Solucionado', 'dashboard');
				$count_notop = strlen($notopen);


				?>
				<div class="card" id="solucionado">
					<header>
						<p>Solucionado</p>
					</header>

					<div class="tooltip2">
						<h3><?php echo  $notopen; ?></h3>
						<div class="bottom">
							<h4>Solucionado</h4>
							<div class="flexx" style="margin-top: 10px;">
								<h4>Nome</h4>
								<h4 style="margin-left: -10px;">Data de criação</h4>
								<h4 style="margin-left: -15px;">Data limite</h4>
								<h4 style="margin-left: -15px;">Técnicos</h4>

							</div>
							<div class="overflowy">

								<?php
								foreach ($infosSolucionado as $info) {

								?>
									<div class="flexx border">
										<p><?php echo $info['name'] ?></p>
										<p><?php $date = new DateTime($info['date_creation']);
											echo $date->format('d / m / Y');
											?></p>
										<p>
											<?php $date = new DateTime($info['time_to_resolve']);
											echo $date->format('d / m / Y');
											?>
										</p>
										<p>
											<?php
											echo $tecnicosHtmlSolucionado[$info['id']];
											?>
										</p>

									</div>

								<?php } ?>
							</div>


							<i></i>
						</div>
					</div>
				</div>

				<div class="card" id="total-do-dia">
					<header>
						<p>Total do dia</p>
					</header>
					<div class="tooltip2">
						<h3><?php echo  $total; ?></h3>
						<div class="bottom">
							<h4>Total do dia</h4>
							<div class="flexx" style="margin-top: 10px;">
								<h4>Nome</h4>
								<h4 style="margin-left: -10px;">Data de criação</h4>
								<h4 style="margin-left: -15px;">Data limite</h4>
								<h4 style="margin-left: -15px;">Técnicos</h4>
							</div>
							<div class="overflowy">

								<?php
								foreach ($infosTotal as $info) {
								?>
									<div class="flexx border">
										<p><?php echo $info['name'] ?></p>
										<p><?php $date = new DateTime($info['date_creation']);
											echo $date->format('d / m / Y');
											?></p>
										<p>
											<?php $date = new DateTime($info['time_to_resolve']);
											echo $date->format('d / m / Y');
											?>
										</p>
										<p>
											<?php
											echo $tecnicosHtmlTotal[$info['id']];
											?>
										</p>

									</div>

								<?php } ?>
							</div>


							<i></i>
						</div>
					</div>




				</div>

				<div class="card" id="media">
					<header>
						<p>Média diária do mês</p>
					</header>

					<h3><?php if (empty(substr($media_diaria, 0, 2))) {
							echo 0;
						} else {
							echo round($media_diaria);
						}; ?>
					</h3>
				</div>

			</div> <!-- fim row1 -->
		</div>
		<div class="rows-container2">
			<div class="own-row second">
				<div class="card" id="chamados-por-tecnico">
					<header>
						<p>Chamados por Técnicos</p>
					</header>
					<?php
					$status = "('5','6')";
					$query_tec = "
					SELECT DISTINCT glpi_users.id AS id, glpi_users.`firstname` AS name, glpi_users.`realname` AS sname, count(glpi_tickets_users.tickets_id) AS tick
						FROM `glpi_users` , glpi_tickets_users, glpi_tickets
						WHERE glpi_tickets_users.users_id = glpi_users.id
						AND glpi_tickets_users.type = 2
						AND glpi_tickets.is_deleted = 0
						AND glpi_tickets.id = glpi_tickets_users.tickets_id						
						AND glpi_tickets.status NOT IN " . $status . "
						AND glpi_tickets.entities_id = 1
						GROUP BY `glpi_users`.`firstname` 
						ORDER BY tick DESC
						LIMIT 5 ";

					$result_tec = $DB->query($query_tec);

					?>
					<table style="font-size:15px" id="open_tickets" class="table table-hover table-condensed">
						<tr>
							<th style="text-align: center;"><?php echo __('Technician', 'dashboard'); ?></th>
							<th style="text-align: center;">
								<?php echo __('Open Tickets', 'dashboard'); ?>
							</th>
						</tr>

						<?php
						$tecnicosIdsString = '';
						$tecnicosIds = [];

						while ($row = $DB->fetch_assoc($result_tec)) {
							if (empty($tecnicosIdsString)) {
								$tecnicosIdsString = $row['id'];
							} else {
								$tecnicosIdsString .=  ',' . $row['id'];
							}
							array_push($tecnicosIds, $row['id']);

							$tableRows .= "<tr class='tr-tecnico' id='" . $row['id'] . "' style='cursor: pointer;'><td>" . $row['name'] . " " . $row['sname'] . "</td><td style='text-align: center;' >" . $row['tick'] . "</td></tr>";
						}



						$query_tec_infos = "
						SELECT
						glpi_users.id as  user_id,
						glpi_tickets.id ,
						glpi_tickets.name,
						glpi_tickets.date_creation,
						glpi_tickets.time_to_resolve
						FROM glpi_tickets
						INNER JOIN glpi_tickets_users
						INNER JOIN glpi_users
						WHERE glpi_users.id  IN " . "(" . $tecnicosIdsString . ")" . "
						AND  GLPI_TICKETS.STATUS NOT IN ( 5 , 6)
						AND glpi_tickets.entities_id = 1 
						AND glpi_tickets.is_deleted = 0
						AND glpi_tickets.id = glpi_tickets_users.tickets_id	
						AND glpi_tickets_users.users_id = glpi_users.id
						AND glpi_tickets_users.type = 2";

					

						$queryTecFetchAll = $DB->query($query_tec);
						$dadosTec = $queryTecFetchAll->fetch_all(MYSQLI_ASSOC);

						$dadosTecInfosFetchAll = $DB->query($query_tec_infos);
						$dadosTecInfos = $dadosTecInfosFetchAll->fetch_all(MYSQLI_ASSOC);
						$i = 1;
						foreach ($dadosTec as $rowDadosTec) { ?>
							<tr style='cursor: pointer; position:relative;' id='<?php echo "tr-id" . $i ?>'>
								<td><?php echo $rowDadosTec['name'] . " " . $rowDadosTec['sname'] ?> </td>
								<td style='text-align: center;'><?php echo  $rowDadosTec['tick']  ?></td>
								<td>
									<div class="right" id='<?php echo "id" . $i++ ?>'>
										<h4><?php echo $rowDadosTec['name'] . " " . $rowDadosTec['sname']  ?></h4>
										<div class="flexx tres" style="margin-top: 10px;">
											<h4>Nome</h4>
											<h4 style="margin-left: -10px;">Data de criação</h4>
											<h4 style="margin-left: -15px;">Data limite</h4>
										</div>
										<div class="overflowy">

											<?php
											foreach ($dadosTecInfos as $rowDadosTecInfos) {
												if ($rowDadosTec['id'] == $rowDadosTecInfos['user_id']) {

											?>
													<div class="flexx border tres">
														<p><?php echo $rowDadosTecInfos['name'] ?></p>
														<p><?php $date = new DateTime($rowDadosTecInfos['date_creation']);
															echo $date->format('d / m / Y');
															?></p>
														<p>
															<?php $date = new DateTime($rowDadosTecInfos['time_to_resolve']);
															echo $date->format('d / m / Y');
															?>
														</p>


													</div>

												<?php } ?>



											<?php }
											?>
										</div>
									</div>
								</td>
							</tr>


						<?php } ?>
					</table>
				</div>

				<div class="card" id="chamados-5dias">
					<header>
						<p>Chamados últimos 5 dias</p>
					</header>
					<?php include "barchartlast5day.php"; ?>
					<canvas class="stackedBar" height="210px"></canvas>
					<script type='text/javascript'>
						var ctx = document.getElementsByClassName("stackedBar");
						var stackedBar = new Chart(ctx, {
							type: 'bar',
							data: {
								labels: <?php echo $datas; ?>,
								datasets: [{
										label: 'Incidente',
										borderColor: 'rgba(255,255,255,1)',
										borderWidth: 1,
										backgroundColor: 'rgba(56, 73, 99,0.5)',
										data: [<?php echo $quanti2; ?>]
									},
									{
										label: 'Requisição',
										borderColor: 'rgba(255,255,255,1)',
										borderWidth: 1,
										backgroundColor: 'rgba(109, 164, 252,0.5)',

										data: [<?php echo $quanta2; ?>]
									}
								]
							},
							options: {
								scales: {
									xAxes: [{
										stacked: true
									}],
									yAxes: [{
										stacked: true
									}]
								}
							}
						});
					</script>
				</div> <!-- //end cf-item -->

				<div class="card" id="chamados-mes">
					<header>
						<p><?php echo __('CHAMADOS POR MÊS', 'dashboard'); ?> </p>
					</header>

					<canvas id="line-chart" height="95px"></canvas>
					<!--                    
                    <script>
                        Chart.defaults.global.legend.display = false;
                        var ctx = document.getElementsByClassName("line-chart");
                        var chartGraph = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels:["JAN","FEV","MAR","ABR","MAI","JUN","JUL","AGO","SET","OUT","NOV","DEZ"],
                                datasets:[{
                                    
                                    data:[75,100,125,130,120,110,90,95,100,95,92,130],
                                    borderWidth:2,
                                    borderColor:'rgba(255,255,255,1)',
                                    backgroundColor: 'transparent'
                                }]
                            }
                        });
                    </script>	
                    -->

				</div> <!-- //end cf-item -->








			</div>
		</div> <!-- //end row 1 -->

		<div class="rows-container3">
			<div class="own-row third">
				<!--						
			<div style="" class="col-lg-3 cf-item">
				<header>
					<p><span></span><?php echo __('Tickets by Source', 'dashboard'); ?></p>
				</header>
				<div class="content">
					<div id="cf-funnel-1" class="cf-funnelx" style="margin-top: -15px;">
						<?php include("grafpie_origem.inc.php");  ?>
					</div>
				</div>
			</div> <!-- //end cf-item -->

				<div class="card" id="por-tipo-mes">
					<header>
						<p>Por tipo mês</p>
					</header>

					<div id="cf-rag-1" class="cf-rag">
						<div class="cf-bars"></div>
						<div class="cf-figs "></div>
						<div class="cf-txts"></div>
					</div>

				</div> <!-- //end cf-item -->


				<div class="card" id="chamados-por-categoria">
					<header>
						<p><?php echo __('CHAMADOS POR CATEGORIA - MÊS ATUAL'); ?></p>
					</header>
					<canvas height="90px" id="categorias"></canvas>
				</div> <!-- //end cf-item -->

				<div style="height: 340px;" class="card" id="atendimento-no-prazo">
					<header>
						<p><?php echo _n('Atend.', 'Atend.', 2) . " " . __('Within', 'dashboard'); ?> %</p>
					</header>
					<div class="content cf-gauge" id="cf-gauge-1">
						<div class="val-current">
							<div class="metric" id="cf-gauge-1-m">%</div>
						</div>
						<div class="canvas">
							<canvas height="180" width="285" id="cf-gauge-1-g"></canvas>
						</div>
						<div class="val-min">
							<div class="metric-small"></div>
						</div>
						<div class="val-max">
							<div class="metric-small"></div>
						</div>

					</div>
				</div> <!-- //end cf-item -->



			</div>
		</div> <!-- //end row -->
		<div class="rows-container4">
			<div class="own-row fourth">
				<!-- interval selector -->
				<div class="col-xs-3 col-sm-4 col-md-4 col-lg-1 form-group pull-right" style="width:125px;">
					<select id="reload_selecter" class="form-control pull-right">
						<option value="30">30s</option>
						<option value="45">45s</option>
						<option value="60">60s</option>
						<option value="120">120s</option>
						<option value="240">240s</option>
						<option value="300">300s</option>
					</select>
				</div>
				<div>
					<button id="reload_page" type="button" class="btn btn-default pull-right">
						<i class="glyphicon glyphicon-refresh"></i><text id="countDownTimer"></text>
					</button>
				</div>
				<!-- interval selector -->
			</div>
		</div>
	</div>
	<script type="text/javascript" src="app.js"></script>
	<script type="text/javascript" src="app2.js"></script>
</body>

</html>