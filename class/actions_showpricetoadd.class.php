<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2015 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/actions_showpricetoadd.class.php
 * \ingroup showpricetoadd
 * \brief   This file is an example hook overload class file
 *          Put some comments here
 */

/**
 * Class Actionsshowpricetoadd
 */
class Actionsshowpricetoadd
{
	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	function doActions($parameters, &$object, &$action, $hookmanager)
	{
		return 0;
	}
	
	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	function formCreateProductOptions($parameters, &$object, &$action, $hookmanager)
	{
		global $conf,$showpricetoadd;
		
		if (empty($showpricetoadd))
		{
			if (
			 (!empty($conf->global->SHOWPRICETOADD_PROPAL) && ( $parameters['currentcontext'] == 'propalcard' || in_array('propalcard', explode(':', $parameters['context'])) )) 
			 || (!empty($conf->global->SHOWPRICETOADD_ORDER) && ( $parameters['currentcontext'] == 'ordercard' || in_array('ordercard', explode(':', $parameters['context'])) ))
			 || (!empty($conf->global->SHOWPRICETOADD_INVOICE) && ( $parameters['currentcontext'] == 'invoicecard' || in_array('invoicecard', explode(':', $parameters['context'])) ))
			)
			{
				//echo '<script>alert("TOTO");</script>';
				$html = $this->_getScript();
				
				echo $html;
			}	
		}
		
		$showpricetoadd = 1;
		
		return 0;
	}

	private function _getScript()
	{
		global $langs;
		
		$html = '<script type="text/javascript">
						var spta_ajax_in_progress = 0;
					
						$(function() {
							if ($("#showpricetoadd").length == 0) {
								spta_constructHtml();
							}
						});
						
						function spta_constructHtml() {
							var td = $("#idprod").closest("td");
							var td_titre = td.closest("tr").prev("tr.liste_titre").children("td:first");
							
							td.attr("colspan", td.attr("colspan") - 1);
							td_titre.attr("colspan", td_titre.attr("colspan") - 1);
							
							td.after($("<td align=\'right\' id=\'td_showpricetoadd\'><input name=\'showpricetoadd\' id=\'showpricetoadd\' size=\'5\' /></td>"))
							td_titre.after($("<td align=\'right\' id=\'td_titre_showpricetoadd\'>'.$langs->transnoentities('PriceUHT').'</td>"));
							
							spta_bindEvent();
						}
			
						function spta_bindEvent() {
							$("#idprod").change(function(event) {
								spta_setPriceInInput(this);
							});
						}
						
						function spta_setPriceInInput(input) {
							
							console.log("ENTREE", spta_ajax_in_progress);
							
							if (spta_ajax_in_progress == 0)
							{
								spta_ajax_in_progress++;
								var fk_product = $(input).val();
							
								$.get("'.dol_buildpath('/showpricetoadd/script/interface.php', 1).'", {json:1, get:"priceProduct", fk_product:fk_product}, function(price) {
										
									$("#showpricetoadd").val(price);
									
									console.log(price);
									
									spta_ajax_in_progress--;
									console.log("SORTIE", spta_ajax_in_progress);
									
								}, "json");
								
							}
							
						}
						
					</script>
				';
				
			return $html;
	}
}