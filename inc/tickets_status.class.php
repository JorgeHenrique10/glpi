<?php

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/// Class Ticket_User
class Tickets_Status extends CommonITILActor {

   // From CommonDBRelation
   static public $itemtype_1 = 'Ticket';
   static public $items_id_1 = 'tickets_id';
   static public $itemtype_2 = 'Status';
   static public $items_id_2 = 'users_id';



   public function getStatus() {
      global $DB;
      
      $query = "SELECT * FROM glpi_status_time";
      $result = $DB->query($query, $DB->error());
   
      $array = [];
      while ($item = $DB->fetch_assoc($result)) {
         $array[] = $item;
      }             

      return true;
   }

   public function getDataIniTicketAtual($ticket_id, $status_cod) {
      global $DB;
      
      $query = "SELECT MAX(data_inicio) as data
                FROM glpi_tickets_status 
                WHERE ticket_id = {$ticket_id} AND status_cod = {$status_cod} AND data_fim IS NULL";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);       

      return $data['data'];
   }


   public function alter_status($ticket_id, $status_cod, $data_inicio) {
      global $DB;
   
      try 
      {
            
            if ( ($status_cod == CommonITILObject::CLOSED || $status_cod == CommonITILObject::SOLVED) ) 
            {
               $query = "INSERT INTO glpi_tickets_status (ticket_id,status_cod,data_inicio,data_fim,data_cons)
               VALUES ({$ticket_id},{$status_cod},'{$data_inicio}', '{$data_inicio}', 0)";
               $DB->queryOrDie($query, $DB->error());

            }else{

               $query = "INSERT INTO glpi_tickets_status (ticket_id,status_cod,data_inicio)
               VALUES ({$ticket_id},{$status_cod},'{$data_inicio}')";
               $DB->queryOrDie($query, $DB->error());

            }        

      } catch (Exception $e) {
         echo "<br><span>".$e->getMessage()."</span><br>";
      }      
   }


   public function ant_status($ticket_id, $data_atual)
   {
      global $DB;
      
      try {
         
         $query = "SELECT * FROM glpi_tickets_status 
                   WHERE ticket_id = {$ticket_id} 
                   AND data_inicio = (SELECT MAX(data_inicio) FROM glpi_tickets_status WHERE ticket_id = {$ticket_id})";
         
         $status_ant =  $DB->fetch_assoc($DB->query($query, $DB->error()));

         if(isset($status_ant['id']) && $status_ant['data_fim'] == NULL)
         {
            $data_inicio = new DateTime($status_ant['data_inicio']);
            $data_fim = new DateTime($data_atual);
            
            // Resgata diferença entre as datas
            $dateInterval = $data_inicio->diff($data_fim);            


            $query_up = "UPDATE glpi_tickets_status 
                         SET data_fim = '{$data_atual}', data_cons = '{$dateInterval->d}'
                         WHERE id = {$status_ant['id']}";

            $DB->query($query_up, $DB->error());
         }
         
       } catch (Exception $e) {
          echo "<br><span>".$e->getMessage()."</span><br>";
       }

   }

   public function show_states($ticket_id) {
      global $DB;
      
      $query = "SELECT TS.ticket_id, name, status_cod ,TS.data_inicio
                FROM glpi_tickets_status AS TS 
                INNER JOIN glpi_status_time AS ST ON TS.status_cod= ST.cod_status
                WHERE TS.ticket_id = {$ticket_id}
                ORDER BY data_inicio";
      
      $result = $DB->query($query, $DB->error());
   
      $result_list = [];

      while ($item = $DB->fetch_assoc($result)) {

         $timestring = strtotime($item['data_inicio']);

         $result_list[$timestring . '_'. $item['status_cod']] = ['timestamp' => $timestring, 'label' => $item['name'], 'class' => 'checked'];
      }             

      return $result_list;
   }
   
   public function show_times($ticket_id) {
      global $DB;

      $query = "SELECT TS.ticket_id, name, status_cod ,TS.data_inicio, TS.data_fim
                FROM glpi_tickets_status AS TS 
                INNER JOIN glpi_status_time AS ST ON TS.status_cod= ST.cod_status
                WHERE TS.ticket_id = {$ticket_id}
                ORDER BY data_inicio";
      
      $result = $DB->query($query, $DB->error());
   
      $result_list = [];
      
      while ($item = $DB->fetch_assoc($result)) {

         $datetime1 = new DateTime($item['data_inicio']);
         $datetime2 = new DateTime($item['data_fim']);

         if(isset($item['data_fim'])){
            
            $interval = $datetime1->diff($datetime2);
            
            $result_list[] = ['timestamp'=> $interval, 'name'=>$item['name']];
         }
         
      }             

      return $result_list;
   }
}
