<?php
if(isset($_REQUEST['ent'])) {
	$id_ent = $_REQUEST['ent'];
	$indexw = "indexw.php?ent=".$id_ent;
	$indexb = "index.php?ent=".$id_ent;
	include "metrics_ent.inc.php";
}
	
elseif(isset($_REQUEST['grp'])) {
	$id_grp = $_REQUEST['grp'];
	$indexw = "indexw.php?grp=".$id_grp;
	$indexb = "index.php?grp=".$id_grp;
	include "metrics_grp.inc.php";
}

else {
	$id_grp = "";
	$indexw = "indexw.php";
	$indexb = "index.php";
	include "metrics.inc.php";
}

?>

<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <title>GLPI  -  <?php echo __('Dash Geral','dashboard'); ?></title>
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
	
	<script src="../js/highcharts.js" type="text/javascript" ></script>
	<!--<script src="../js/highcharts-3d.js" type="text/javascript" ></script>-->
	<script src="../js/themes/dark-unica.js" type="text/javascript" ></script>
	
	<script src="../js/modules/no-data-to-display.js" type="text/javascript" ></script>
	<script src="reload.js"></script>	
	<script src="reload_param.js"></script>	
    
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content= "120"/>  
		<script src="../../js/respond.min.js"></script>
		<script src="../../js/excanvas.min.js"></script>
	<![endif]-->
        
	<script>
		var themeColour = 'black';
	</script>
   <script src="controlfrog.js"></script>
    
<style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style></head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"> </script>
<body class="black" onload="reloadPage(); initSpark('<?php echo $quantm2; ?>'); initSparkDay('<?php echo $quantd2; ?>'); initGauge('0','100','<?php echo $gauge_val; ?>'); initPie('<?php echo $res_days; ?>'); initFunnel('<?php echo $sta_values; ?>','<?php echo $sta_labels; ?>'); initRag('<?php echo $types; ?>','<?php echo $rag_labels; ?>'); ">
	
<div class="container-fluid">	
<div class="cf-container cf-nav-active">

<div class="row-fluid" style="margin-top: 25px;">
<div class="col-lg-12" role="main">

<div class="row-status" >

<div style="height:120px;" class="col-lg-2 cf-item-status">
		<!--Display the time and date. For 12hr clock add class 'cf-td-12' to the 'cf-td' div -->
	<header>
		<p><span></span><?php echo __('Date'); ?> </p>
	</header>
	<div class="content">
		<div class="">
		<!-- <div class="cf-td cf-td-12"> -->
			<div class="cf-version metric-small" style="font-size:30px !important;"></div>
			<div class="cf-td-time metric hora"></div>
			<div class="cf-td-dd">
				<!--<p class="cf-td-day metric-small" ></p>
				<p class="cf-td-date metric-small" ></p>						
				-->
				<?php date_default_timezone_set('America/Sao_Paulo');
				  echo date('H:i:s').'<br>'; ?>
				<script type="text/javascript">
					var d_names = <?php echo '"'.$dia.'"' ; ?>;
					var m_names = <?php echo '"'.$mes.'"' ; ?>;							
					var d = new Date();
					var curr_day = d.getDay();
					var curr_date = d.getDate();
					var curr_month = d.getMonth();
					var curr_year = d.getFullYear();

					document.write("<span style='font-size:30px;'>" + curr_date + " " + m_names + " " + curr_year + "</span><br><span style='font-size:30px;'> Sede </span><br>" );		
				</script>

			</div>					
		</div>
	</div>
	</div> <!-- //end cf-item -->
<!--
	<div style="min-height: 0px;" class="col-lg-1 cf-item-status tickets new">
	<header>
		<p><span></span><?php echo _x('status','Hoje');?></p>
	</header>
	<div class="content" >
		<div class="metric5"><?php echo $c_hj; ?></div>
		<div class="metric-small5"></div>
	</div>
</div>
-->
<div style="min-height: 0px;" class="col-lg-2 cf-item-status tickets new">
	<header>
		<p><span></span><?php echo _x('status','Aguardando Atend.');?></p>
	</header>
	<div class="content" >
		<div class="metric5"><?php echo $new; ?></div>
		<div class="metric-small5"></div>
	</div>
</div>
<!--		
<div style="min-height: 100px;" class="col-lg-5 cf-item-status tickets assign">
	<header>
		<p><span></span><?php echo __('Assigned');?></p>
	</header>
	<div class="content">
		<div class="metric5"><?php echo $assigned;?></div>
		<div class="metric-small5"></div>
	</div>
</div>

<div style="min-height: 100px;" class="col-lg-5 cf-item-status tickets pending">
	<header>
		<p><span></span><?php echo __('Pending'); ?></p>
	</header>
	<div class="content">
		<div class="metric5"><?php echo $pend;?></div>
		<div class="metric-small5"></div>
	</div>
</div>
-->		

<div style="min-height: 100px;" class="col-lg-2 cf-item-status tickets pending">
	<header>
		<p><span></span><?php echo __('EM ATENDIMENTO'); ?></p>
	</header>
	<div class="content">
		<div class="metric5"><?php echo $pend+$assigned;?></div>
		<div class="metric-small5"></div>
	</div>
</div>

<?php

//Solved or closed ticktes					

	$notopen = $solved;
	$notopeny = $solvedy;
	$tit_notopen = __('Solucionado','dashboard');
	$count_notop = strlen($notopen);


?>
<div style="min-height:100px;" class="col-lg-2 cf-item-status tickets closed">
	<header>
		<p><span></span><?php echo $tit_notopen;?></p>
	</header>
	<div class="content">
		<div class="metric5"><?php echo $notopen;?></div>
		<?php 
			if($count_notop < 5) {
				echo "<div class='metric-small5'>";						
				echo  " </div>";
			}
		?>		
	</div>
</div>

<div style="min-height: 100px;" class="col-lg-2 cf-item-status tickets all">				
	<header>
		<p><span></span><?php echo __('Total do Dia')."";?></p>
	</header>
	<div class="content">
		<div class="metric5"><?php echo $total;?></div>
		<div class="metric-small5"></div>			
	</div>
</div>

<div style="min-height: 100px;" class="col-lg-2 cf-item-status tickets all">				
	<header>
		<p><span></span><?php echo __('Média Diaria do Mês')."";?></p>
	</header>
	<div class="content">
		<div class="metric5"><?php echo substr($media_diaria,0,2);?></div>
		<div class="metric-small5"></div>			
	</div>
</div>
</div> <!-- fim row1 -->
<br><br>
<div class="row" style="margin-top: 10px;">	
        <div class="col-lg-3 cf-item">
            <header>
                <p><span></span>CHAMADOS POR TÉCNINO</p>
            </header>
            <div class="content">
			<?php         
				$status = "('5','6')";                       
					$query_tec = "
					SELECT DISTINCT glpi_users.id AS id, glpi_users.`firstname` AS name, glpi_users.`realname` AS sname, count(glpi_tickets_users.tickets_id) AS tick
						FROM `glpi_users` , glpi_tickets_users, glpi_tickets
						WHERE glpi_tickets_users.users_id = glpi_users.id
						AND glpi_tickets_users.type = 2
						AND glpi_tickets.is_deleted = 0
						AND glpi_tickets.id = glpi_tickets_users.tickets_id						
						AND glpi_tickets.status NOT IN ".$status."
						GROUP BY `glpi_users`.`firstname` 
						ORDER BY tick DESC
						LIMIT 5 ";
					
					$result_tec = $DB->query($query_tec);			                        
				?>    
				<table id="open_tickets" class="table table-hover table-condensed">
					<tr>
						<th style="text-align: center;"><?php echo __('Technician','dashboard'); ?></th>
						<th style="text-align: center;">
							<?php echo __('Open Tickets','dashboard'); ?>
						</th>
					</tr>
							
			<?php
				while($row = $DB->fetch_assoc($result_tec)) 
				{					
					echo "<tr><td>".$row['name']." ".$row['sname']."</td><td style='text-align: center;' >".$row['tick']."</td></tr>";											
				}				
			?>                                       
			</table> 
            </div>
        </div>

		<div style="" class="col-lg-3 cf-item">
				<header>
					<p><span></span><?php echo __('CHAMADOS ÚLTIMOS 5 DIAS','dashboard');?></p>
				</header>
				<div class="content">
                <?php include ("barchartlast5day.php"); ?>
                <canvas class="stackedBar" height="210px" ></canvas>
                    <script type='text/javascript'>
                                var ctx = document.getElementsByClassName("stackedBar");
                                var stackedBar = new Chart(ctx,{
                                    type: 'bar',
                                    data: {
                                        labels:<?php echo $datas; ?>,
                                        datasets:[{
                                            label:'Incidente',
                                            borderColor:'rgba(255,255,255,1)',
                                            borderWidth: 1,
                                            backgroundColor:'rgba(56, 73, 99,0.5)',
                                            data:[<?php echo $quanti2; ?>]
                                        },
                                        {
                                            label: 'Requisição',
                                            borderColor:'rgba(255,255,255,1)',
                                            borderWidth: 1,
                                            backgroundColor:'rgba(109, 164, 252,0.5)',

                                            data:[<?php echo $quanta2; ?>]
                                        }]
                                    },
                                    options:{
                                        scales:{
                                            xAxes:[{
                                                stacked:true
                                            }],
                                            yAxes:[{
                                                stacked:true
                                            }]
                                        }
                                    }
                                });

                    </script>				
				</div>
			</div> <!-- //end cf-item -->
			
			<div style="min-height: 0px;" class="col-lg-6 cf-item">
				<header>
					<p><span></span><?php echo __('CHAMADOS POR MÊS','dashboard'); ?> </p>
				</header>
				<div class="content">							
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
				</div>
			</div> <!-- //end cf-item -->								








		</div> <!-- //end row 1 -->
	
	<div class="row row-fluid" style="">														
<!--						
			<div style="" class="col-lg-3 cf-item">
				<header>
					<p><span></span><?php echo __('Tickets by Source','dashboard') ;?></p>
				</header>
				<div class="content">
					<div id="cf-funnel-1" class="cf-funnelx" style="margin-top: -15px;">
						<?php include ("grafpie_origem.inc.php");  ?>
					</div>
				</div>
			</div> <!-- //end cf-item -->
			
			<div style="" class="col-lg-3 cf-item">
					<header>
						<p><span></span><?php echo 'Por Tipo Mês' ;?></p>
					</header>
					<div class="content"  heigh="50">					
						<div id="cf-rag-1" class="cf-rag" >						
							<div class="cf-bars"></div>
								<div class="cf-figs "></div>
									<div class="cf-txts"></div> 
						</div>
					</div>				
			</div> 	<!-- //end cf-item -->	

			
			<div style="" class="col-lg-7 cf-item">
				<header>
					<p><span></span><?php echo __('CHAMADOS POR CATEGORIA - MÊS ATUAL') ;?></p>
				</header><br>
                <canvas id="categorias" height="90px"></canvas>
			</div> <!-- //end cf-item -->	
					
			<div style="" class="col-lg-2 cf-item">
				<header>
					<p style="font-size:16px; margin-top:11px;"><span></span><?php echo _n('Atend.','Atend.',2)." ".__('Within','dashboard');?> %</p>
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
<script type="text/javascript" src="app.js"></script>
<script type="text/javascript" src="app2.js"></script>
</body>
</html>
