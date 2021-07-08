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
	include "metrics_ent.inc.php";
} else {
	$id_grp = "";
	$indexw = "indexw.php";
	$indexb = "index.php";
	include "metrics_ent.inc.php";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<title>GLPI - <?php echo __('Metrics', 'dashboard'); ?></title>
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

	<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" rel="stylesheet">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>


	<!--[if lt IE 9]>
    <meta http-equiv="refresh" content= "120"/>  
		<script src="../../js/respond.min.js"></script>
		<script src="../../js/excanvas.min.js"></script>
	<![endif]-->

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

</head>

<body class="black" onload="reloadPage(); initSpark('<?php echo $quantm2; ?>'); initSparkDay('<?php echo $quantd2; ?>'); initGauge('0','100','<?php echo $gauge_val; ?>'); initPie('<?php echo $res_days; ?>'); initFunnel('<?php echo $sta_values; ?>','<?php echo $sta_labels; ?>'); initRag('<?php echo $types; ?>','<?php echo $rag_labels; ?>'); initSingle1('<?php echo $satisf; ?>');">

	<div class="cf-nav cf-nav-state-min">
		<a href="" class="cf-nav-toggle">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>

		<ul>
			<li class="cf-nav-shortcut">
				<a href="../index.php">
					<span class="cf-nav-min"><i class="fa fa-home"></i></span>
					<span class="cf-nav-max">Home</span>
				</a>
			</li>
			<li class="current cf-nav-shortcut">
				<a href="<?php echo $indexb; ?>" class="current active">
					<span class="cf-nav-min">B</span>
					<span class="cf-nav-max">Black</span>
				</a>
			</li>
			<li class="cf-nav-shortcut">
				<a href="<?php echo $indexw; ?>">
					<span class="cf-nav-min">W</span>
					<span class="cf-nav-max">White</span>
				</a>
			</li>
		</ul>
	</div>


	<div class="container-fluid">
		<div class="cf-container cf-nav-active">

			<div class="row-fluid" style="margin-top: 25px;">
				<div class="col-lg-12" role="main">


					<div class="row-status">
						<div class="carosel-root">
							<div id="div-carrosel" class="carosel">
								<!--NOVO -->
								<div style="min-height: 0px;" onclick="tooltip(1)" id="novoId" status="1" class="col-lg-5 cf-item-status tickets new carosel-item item-1">
									<header>
										<p><span></span><?php echo _x('status', 'New'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $new; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>

								<!-- ATRIBUÍDO -->
								<div style="min-height: 100px;" onclick="tooltip(2)" class="col-lg-5 cf-item-status tickets assign carosel-item item-2">
									<header>
										<p><span></span><?php echo __('Assigned'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $assigned; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>

								<!-- PENDENTE -->
								<div style="min-height: 100px;" onclick="tooltip(4)" class="col-lg-5 cf-item-status tickets pending carosel-item item-3">
									<header>
										<p><span></span><?php echo __('Pending'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $pend; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>

								<?php

								//Solved or closed ticktes					
								if ($solved > 0) {
									$notopen = $solved;
									$notopeny = $solvedy;
									$tit_notopen = __('Solved', 'dashboard');
									$count_notop = strlen($notopen);
								} else {
									$notopen = $closed;
									$notopeny = $closedy;
									$tit_notopen = __('Closed', 'dashboard');
									$count_notop = strlen($notopen);
								}

								?>

								<!-- CLOSED -->
								<div style="min-height:100px;" onclick="tooltip(6)" class="col-lg-5 cf-item-status tickets closed carosel-item item-4">
									<header>
										<p><span></span><?php echo $tit_notopen; ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $notopen; ?></div>
										<?php
										if ($count_notop < 5) {
											echo "<div class='metric-small5'>";
											echo  " </div>";
										}
										?>
									</div>
								</div>

								<!-- ATRIBUIDO -->
								<div style="min-height: 100px;" onclick="tooltip(23)" class="col-lg-5 cf-item-status tickets all carosel-item item-5">
									<header>
										<p><span></span><?php echo __('Atribuido'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $atribuido; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>

								<!-- Validacao TR -->
								<div style="min-height: 100px;" onclick="tooltip(13)" class="col-lg-5 cf-item-status tickets all carosel-item item-6">
									<header>
										<p><span></span><?php echo ('Validacao TR'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $validacao_tr; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>

								<!-- publicacao -->
								<div style="min-height: 100px;" onclick="tooltip(14)" class="col-lg-5 cf-item-status tickets all carosel-item item-7">
									<header>
										<p><span></span><?php echo ('Publicação'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $publicacao; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>
								<!-- parecer habilitacao -->
								<div style="min-height: 100px;" onclick="tooltip(15)" class="col-lg-5 cf-item-status tickets all carosel-item item-8">
									<header>
										<p><span></span><?php echo ('Parecer de Habilitação'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $parecer_habilitacao; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>
								<!-- Validação Técnica -->
								<div style="min-height: 100px;" onclick="tooltip(16)" class="col-lg-5 cf-item-status tickets all carosel-item item-9">
									<header>
										<p><span></span><?php echo ('Validação Técnica'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $validacao_tecnica; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>
								<!-- Resultados -->
								<div style="min-height: 100px;" onclick="tooltip(17)" class="col-lg-5 cf-item-status tickets all carosel-item item-10">
									<header>
										<p><span></span><?php echo ('Resultados'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $resultados; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>
								<!-- Homologação -->
								<div style="min-height: 100px;" onclick="tooltip(18)" class="col-lg-5 cf-item-status tickets all carosel-item item-11">
									<header>
										<p><span></span><?php echo ('Homologação'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $homologacao; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>
								<!-- Jurídico -->
								<div style="min-height: 100px;" onclick="tooltip(19)" class="col-lg-5 cf-item-status tickets all carosel-item item-12">
									<header>
										<p><span></span><?php echo ('Jurídico'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $juridico; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>
								<!-- validacao_interna -->
								<div style="min-height: 100px;" onclick="tooltip(20)" class="col-lg-5 cf-item-status tickets all carosel-item item-13">
									<header>
										<p><span></span><?php echo ('Validação Interna'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $validacao_interna; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>
								<!-- envio_contrato -->
								<div style="min-height: 100px;" onclick="tooltip(21)" class="col-lg-5 cf-item-status tickets all carosel-item item-14">
									<header>
										<p><span></span><?php echo ('Envio de Contrato'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $envio_contrato; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>

								<!-- formalizacao -->
								<div style="min-height: 100px;" onclick="tooltip(22)" class="col-lg-5 cf-item-status tickets all carosel-item item-15">
									<header>
										<p><span></span><?php echo ('Formalização'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $formalizacao; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>

								<!-- pendente_unidade -->
								<div style="min-height: 100px;" onclick="tooltip(24)" class="col-lg-5 cf-item-status tickets all carosel-item item-16">
									<header>
										<p><span></span><?php echo ('Pendente Unidade'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $pendente_unidade; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>

								<!-- publicacao_errata -->
								<div style="min-height: 100px;" onclick="tooltip(25)" class="col-lg-5 cf-item-status tickets all carosel-item item-17">
									<header>
										<p><span></span><?php echo ('Publicação Errata'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $publicacao_errata; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>

								<!-- prorrogacao -->
								<div style="min-height: 100px;" onclick="tooltip(26)" class="col-lg-5 cf-item-status tickets all carosel-item item-18">
									<header>
										<p><span></span><?php echo ('Prorrogação'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $prorrogacao; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>

								<!-- diligencia -->
								<div style="min-height: 100px;" onclick="tooltip(27)" class="col-lg-5 cf-item-status tickets all carosel-item item-19">
									<header>
										<p><span></span><?php echo ('Diligência'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $diligencia; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>


								<!-- recurso -->
								<div style="min-height: 100px;" onclick="tooltip(28)" class="col-lg-5 cf-item-status tickets all carosel-item item-20">
									<header>
										<p><span></span><?php echo ('Recurso'); ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $recurso; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>

								<!-- TOTAL -->
								<div style="min-height: 100px;" class="col-lg-5 cf-item-status tickets all carosel-item item-21">
									<header>
										<p><span></span><?php echo __('Total') . " (" . __('Opened', 'dashboard') . ")"; ?></p>
									</header>
									<div class="content">
										<div class="metric5"><?php echo $total; ?></div>
										<div class="metric-small5"></div>
									</div>
								</div>
							</div>
							<button type="button" id="buttonId1" class="carosel-nav carosel-nav-left">&lt;</button>
							<button type="button" id="buttonId2" class="carosel-nav carosel-nav-right">&gt;</button>
						</div>
					</div> <!-- fim row1 -->

					<div class="row" style="margin-top: 10px;">
						<div style="" class="col-lg-3 cf-item">
							<!--Display the time and date. For 12hr clock add class 'cf-td-12' to the 'cf-td' div -->
							<header>
								<p><span></span><?php echo __('Time') . " &amp; " . __('Date'); ?> </p>
							</header>
							<div class="content">
								<div class="cf-td">
									<!-- <div class="cf-td cf-td-12"> -->
									<div class="cf-version metric-small" style="font-size:30px !important;"><?php echo $actent; ?></div>
									<div class="cf-td-time metric hora"></div>
									<div class="cf-td-dd">
										<!--<p class="cf-td-day metric-small" ></p>
						<p class="cf-td-date metric-small" ></p>						
						-->
										<script type="text/javascript">
											var d_names = <?php echo '"' . $dia . '"'; ?>;
											var m_names = <?php echo '"' . $mes . '"'; ?>;
											var d = new Date();
											var curr_day = d.getDay();
											var curr_date = d.getDate();
											var curr_month = d.getMonth();
											var curr_year = d.getFullYear();

											document.write("<span style='font-size:26px; margin-top: -6px !important;'>" + d_names + "</span><br> <span style='font-size:26px;'>" + curr_date + " " + m_names + " " + curr_year + "</span><br>");
										</script>
										<span style="font-size:20px;"><?php echo __('Period') . ": " . $period_name ?></span>
									</div>
								</div>
							</div>
						</div> <!-- //end cf-item -->

						<div style="" class="col-lg-3 cf-item">
							<header>
								<p><span></span><?php echo __('Tickets Total', 'dashboard'); ?></p>
							</header>
							<div class="content">
								<div class="cf-svmc-sparkline">
									<div class="cf-svmc">
										<div class="metric total"></div>
										<div class="change metric-small">
											<div id="arrow"></div>
											<span class="large"></span>
										</div>
									</div>
									<div class="cf-sparkline clearfix" style="margin-top:15px;">
										<div id="spark-1" class="sparkline">
											<canvas height="90" width="235" style="display: inline-block; width: 235px; height: 90; vertical-align: top;"></canvas>
										</div>
										<div style="height: 117px;" class="sparkline-value">
											<div class="metric-small"></div>
										</div>
									</div>
								</div>
							</div>
						</div> <!-- //end cf-item -->

						<div style="min-height: 0px;" class="col-lg-3 cf-item">
							<header>
								<p><span></span><?php echo __('Today Tickets', 'dashboard'); ?> </p>
							</header>
							<div class="content">
								<div class="cf-svmc-sparkline">
									<div class="cf-svmc">
										<div class="metric total-month"><?php echo $today_tickets; ?></div>
										<div class="change metric-small daily">
											<div id="arrow-2"></div>
											<span class="large large-2"></span>
										</div>
									</div>
									<div class="cf-sparkline clearfix" style="margin-top:15px;">
										<div id="spark-2" class="sparkline">
											<canvas height="90" width="235" style="display: inline-block; width: 235px; height: 90; vertical-align: top;"></canvas>
										</div>
										<div style="height: 117px;" class="sparkline-value">
											<div class="metric-small"></div>
										</div>
									</div>
								</div>
							</div>
						</div> <!-- //end cf-item -->

						<div style="" class="col-lg-3 cf-item">
							<header>
								<p><span></span><?php echo _n('Ticket', 'Tickets', 2) . " " . __('Within', 'dashboard'); ?> - %</p>
							</header>
							<div class="content cf-gauge" id="cf-gauge-1">

								<div class="val-current">
									<div class="metric" id="cf-gauge-1-m"></div>
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
					</div> <!-- //end row 1 -->

					<div class="row row-fluid" style="margin-top:40px;">

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

						<div style="" class="col-lg-3 cf-item">
							<header>
								<p><span></span><?php echo _n('Ticket', 'Tickets', 2) . " " . __('by Type', 'dashboard'); ?></p>
							</header>
							<div class="content">
								<div id="cf-rag-1" class="cf-rag">
									<div class="cf-bars"></div>
									<div class="cf-figs "></div>
									<div class="cf-txts"></div>
								</div>
							</div>
						</div> <!-- //end cf-item -->


						<div style="" class="col-lg-3 cf-item">
							<header>
								<p><span></span><?php echo __('Resolution time'); ?></p>
							</header>
							<div class="content cf-piex" id="cf-pie-1" style="margin-left:0px;">
								<?php include("grafpie_time_geral.inc.php");  ?>
							</div>
						</div> <!-- //end cf-item -->

						<div style="" class="col-lg-3 cf-item">
							<header>

								<?php
								//satisfaction, or not		
								//$sat = 0;										
								if ($sat != 0) {
									echo "<p><span></span>" . __('Satisfaction') . "</p>";
								} else {
									echo "<p><span></span>Top 5 " . __('Technician') . "</p>";
								}
								?>
							</header>

							<div class="content cf-svp clearfix" id="svp-1">
								<?php
								//satisfaction, or not					
								if ($sat != 0) {
									echo '<div class="chart" data-percent="' . $satisf . '" style="margin-left: 20%;"> <span class="percent">' . $satisf . '</span><sup></sup> </div>';
								} else {
									echo '<div id="grafsat" class="content cf-piexx" style="margin-left:0px;">';
									include("grafbar_grupo.inc.php");
									echo ' </div>';
								}
								?>
							</div>
						</div> <!-- //end cf-item -->


						<!-- interval selector -->
						<div class="col-xs-3 col-sm-4 col-md-4 col-lg-1 form-group pull-right" style="float: right; width:125px;">
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


					</div> <!-- //end row -->
				</div> <!-- //end main -->
			</div> <!-- //end row -->
		</div> <!-- //end container -->

	</div>
	<div id="tooltipWrapper" onclick="document.getElementById('tooltipWrapper').style.display='none'" style="width:500px; height:500px; background-color: white;">
		<span id="spanStatusId"></span>
	</div>

</body>
<script>
	$(document).ready(function() {

		$(".carosel").slick({
			infinite: true,
			autoplay: true,
			autoplaySpeed: 5000,
			// this value should < total # of slides, otherwise the carousel won't slide at all
			slidesToShow: 6,
			slidesToScroll: 5,
			speed: 5000,
			dots: false,
			arrows: true,
			prevArrow: $("#buttonId1"),
			nextArrow: $("#buttonId2")
		});
	})
</script>

<style>
	.carosel-root {
		position: relative;
	}

	.carosel-nav {
		position: absolute;
		text-align: center;
		padding: 0 4px;
		border: 1px solid #000;
		border-radius: 70%;
		background: rgba(0, 0, 0, 0.3);
		top: 50%;
		color: #f4f4f4;
		cursor: pointer;
	}

	.carosel-nav-left {
		left: 3px;
	}

	.carosel-nav-right {
		right: 3px;
	}

	#tooltipWrapper {
		display: none;

	}
</style>
<script>
	function closesDiv() {
		document.getElement
	}

	function tooltip(id) {


		var status = id;
		var tooltipWrapper = document.getElementById("tooltipWrapper");
		console.log(status)
		var currentMousePos = {
			x: -1,
			y: -1
		};
		currentMousePos.x = event.pageX;
		currentMousePos.y = event.pageY;
		console.log(currentMousePos.x + " " + currentMousePos.y)
		tooltipWrapper.style.display = "block";
		tooltipWrapper.style.zIndex = 9999;
		tooltipWrapper.style.position = "absolute";
		tooltipWrapper.style.top = currentMousePos.y + "px";
		tooltipWrapper.style.left = currentMousePos.x + "px";
		tooltipWrapper.children[0].innerHTML = status;


		<?php

			$query = "select glpi_tickets.name, glpi_tickets.date_creation, glpi_tickets_status.data_inicio
			from glpi_tickets
			left join glpi_tickets_status on glpi_tickets.id = glpi_tickets_status.ticket_id
			where glpi_tickets.status = 4 and glpi_tickets.is_deleted = '0';"

		?>

	}
</script>

</html>