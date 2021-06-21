<?php

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/// Class Ticket_User
class Ticket_Status_Time extends CommonITILActor {

   // From CommonDBRelation
   /*static public $itemtype_1 = 'Ticket';
   static public $items_id_1 = 'tickets_id';
   static public $itemtype_2 = 'Status';
   static public $items_id_2 = 'users_id';*/



   public function getStatus($cod_status, $ticket_id) {
      global $DB;
      $dias = 0;
      
      try 
      {

         $query = "SELECT * FROM glpi_status_time WHERE cod_status = {$cod_status}";
         $result = $DB->fetch_assoc($DB->query($query));

         $query_tickets = "SELECT * FROM glpi_tickets_status WHERE ticket_id = {$ticket_id} AND  status_cod = {$cod_status}";
         $result_tickets = ($DB->query($query_tickets));
         if($result_tickets->num_rows >= 0){
            
            while ($item = $DB->fetch_assoc($result_tickets)) {

               $dias = $dias + $item['data_cons'];
            }

            $dif = ($result['time'] - $dias) <= 0 ? 0: $result['time'] - $dias;
            
            return $dif; 
         }
         
         return $result['time'];

      } catch (Exception $e) {
         echo "<br><span>".$e->getMessage()."</span><br>";
      }
      
   }


   public function getStatusCod($cod_status) {
      global $DB;
      $dias = 0;
      
      try 
      {

         $query = "SELECT * FROM glpi_status_time WHERE cod_status = {$cod_status}";
         $result = $DB->fetch_assoc($DB->query($query));         
         
         if ($result) {
            return $result['time'];
         }

      } catch (Exception $e) {
         echo "<br><span>".$e->getMessage()."</span><br>";
      }
      
   }
   
}
