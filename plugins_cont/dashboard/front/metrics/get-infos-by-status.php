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

$status = $_GET['status'];

$query = "select glpi_tickets.id, glpi_tickets.name, glpi_tickets.date_creation,  glpi_tickets.time_to_own, max(data_inicio)
			from glpi_tickets
			LEFT join glpi_tickets_status on (glpi_tickets.id = glpi_tickets_status.ticket_id and glpi_tickets_status.status_cod = glpi_tickets.status )
			where glpi_tickets.status = " . $status . " and glpi_tickets.is_deleted = '0'
			$period
			$entidade
			group by glpi_tickets.id, glpi_tickets.name, glpi_tickets.date_creation,  glpi_tickets.time_to_own";

$infosByStatus = $DB->query($query)->fetch_all(MYSQLI_ASSOC);

?>

<div class="tooltip-grid white">
    <h4>Nome</h4>
    <h4>Data de Criação</h4>
    <h4>Tempo de solução</h4>
    <h4>Data de Início</h4>
</div>
<div class="overflowy">
    <?php foreach ($infosByStatus as $row) { ?>
        <div class="tooltip-grid border">
            <p> <?php print_r($row['name']); ?> </p>
            <p> <?php print_r($row['date_creation']); ?> </p>
            <p> <?php print_r($row['time_to_own']); ?></p>
            <p> <?php print_r($row['max(data_inicio)']); ?></p>
        </div>
    <?php } ?>
</div>