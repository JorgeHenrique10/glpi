<?php
/**
 * @version $Id: softwareversion.class.php 539 2019-07-02 16:54:00Z yllen $
 -------------------------------------------------------------------------
 LICENSE

 This file is part of PDF plugin for GLPI.

 PDF is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 PDF is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with Reports. If not, see <http://www.gnu.org/licenses/>.

 @package   pdf
 @authors   Nelly Mahu-Lasson, Remi Collet
 @copyright Copyright (c) 2009-2019 PDF plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://forge.glpi-project.org/projects/pdf
 @link      http://www.glpi-project.org/
 @since     2009
 --------------------------------------------------------------------------
*/


class PluginPdfSoftwareVersion extends PluginPdfCommon {


   static $rightname = "plugin_pdf";


   function __construct(CommonGLPI $obj=NULL) {
      $this->obj = ($obj ? $obj : new SoftwareVersion());
   }


   static function pdfMain(PluginPdfSimplePDF $pdf, SoftwareVersion $version) {

      $ID = $version->getField('id');

      $pdf->setColumnsSize(100);
      $pdf->displayTitle('<b><i>'.sprintf(__('%1$s: %2$s'), __('ID')."</i>", $ID."</b>"));

      $pdf->setColumnsSize(50,50);

      $pdf->displayLine(
         '<b><i>'.sprintf(__('%1$s: %2$s'), __('Name').'</i></b>', $version->fields['name']),
         '<b><i>'.sprintf(__('%1$s: %2$s'), _n('Software', 'Software', 2).'</i></b>',
                          Html::clean(Dropdown::getDropdownName('glpi_softwares',
                                                                $version->fields['softwares_id']))));

      $pdf->displayLine(
         '<b><i>'.sprintf(__('%1$s: %2$s'), __('Status').'</i></b>',
                          Html::clean(Dropdown::getDropdownName('glpi_states',
                                                                $version->fields['states_id']))),
         '<b><i>'.sprintf(__('%1$s: %2$s'), __('Operating system').'</i></b>',
                          Html::clean(Dropdown::getDropdownName('glpi_operatingsystems',
                                                                $version->fields['operatingsystems_id']))));

      $pdf->setColumnsSize(100);
      PluginPdfCommon::mainLine($pdf, $version, 'comment');
      $pdf->displaySpace();
   }


   static function pdfForSoftware(PluginPdfSimplePDF $pdf, Software $item){
      global $DB;

      $sID = $item->getField('id');

      $query = ['FIELDS'    => ['glpi_softwareversions.*',
                                'glpi_states.name AS sname',
                                'glpi_operatingsystems.name AS osname'],
                'FROM'      => 'glpi_softwareversions',
                'LEFT JOIN' => ['glpi_states'
                                => ['FKEY' => ['glpi_states'           => 'id',
                                               'glpi_softwareversions' => 'states_id']],
                                'glpi_operatingsystems'
                                => ['FKEY' => ['glpi_operatingsystems' => 'id',
                                               'glpi_softwareversions' => 'operatingsystems_id']]],
                'WHERE'      => ['softwares_id' => $sID],
                'ORDER'      => 'name'];

      $pdf->setColumnsSize(100);
      $title = '<b>'.SoftwareVersion::getTypeName(2).'</b>';

      if ($result = $DB->request($query)) {
         if (!count($result) ) {
            $pdf->displayTitle(sprintf(__('%1$s: %2$s'), $title, __('No item to display')));
         } else {
            $pdf->setColumnsSize(13,13,30,14,30);
            $pdf->displayTitle('<b><i>'.$title.'</i></b>',
                               '<b><i>'.__('Status').'</i></b>',
                               '<b><i>'.__('Operating system').'</i></b>',
                               '<b><i>'._n('Installation', 'Installations', 2).'</i></b>',
                               '<b><i>'.__('Comments').'</i></b>');
            $pdf->setColumnsAlign('left','left','left','right','left');

            for ($tot=$nb=0 ; $data=$result->next() ; $tot+=$nb) {
               $nb = Computer_SoftwareVersion::countForVersion($data['id']);
               $pdf->displayLine((empty($data['name'])?"(".$data['id'].")":$data['name']),
                                 $data['sname'], $data['osname'], $nb,
                                 str_replace(["\r","\n"]," ",$data['comment']));
            }
            $pdf->setColumnsAlign('left','right','left', 'right','left');
            $pdf->displayTitle('','',"<b>".sprintf(__('%1$s: %2$s'), __('Total')."</b>", ''),$tot, '');
         }
      }
      $pdf->displaySpace();
   }


   static function displayTabContentForPDF(PluginPdfSimplePDF $pdf, CommonGLPI $item, $tab) {

      switch ($tab) {
         case 'Computer_SoftwareVersion$1' :
            PluginPdfComputer_SoftwareVersion::pdfForVersionByEntity($pdf, $item);
            break;

         case 'Computer_SoftwareVersion$2' :
            PluginPdfComputer_SoftwareVersion::pdfForItem($pdf, $item);
            break;

         default :
            return false;
      }
      return true;
   }
}