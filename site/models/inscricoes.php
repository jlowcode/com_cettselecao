<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cettselecao
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * CettSelecao Model
 *
 * @since  0.0.1
 */
class CettSelecaoModelInscricoes extends JModelItem
{
	public function getDados($input)
	{
		try 
		{
			$db = JFactory::getDbo();

			$columns =  "SELECT ".
						"a.id, ".
						"CONCAT(a.nome_completo,'<br/>','<b>CPF:</b> ',a.numero_cpf), ".
						"CONCAT(b.nome,'<br/>',d.nome,'<br/><b>',b.rede,'</b>') as 'escola', ".
						"CONCAT(c.nome,'<br/>',c.turno,', ',c.tipo,', ',c.modalidade) as 'curso', ".
						"DATE_FORMAT(a.date_time, '%d/%m/%Y %H:%i') as 'data_hora', ".
						"a.situacao, ".
						"CONCAT('<span class=\"btn_acoes_',a.id,'\"></span>') AS 'btn' ";

			$from_joins = "FROM matricula_0_matriculados a ".
					"LEFT JOIN matricula_0_escolas b ON b.id = a.escola ".
					"LEFT JOIN matricula_0_municipio d ON d.id = b.municipio ".
					"LEFT JOIN matricula_0_cursos c ON c.id = a.curso ";

			$where = "WHERE 1 > 0 ";

			$nome = trim($input->get('filtroNome',null,'Raw'));
			if(strlen($nome) > 0) {
				$where .= "AND a.nome_completo LIKE '".addslashes($nome)."%'";
			}
			$cpf = trim($input->get('filtroCpf',null,'Raw'));
			if(strlen($cpf) > 0) {
				$where .= "AND a.numero_cpf LIKE '".addslashes($cpf)."%'";
			}
			$email = trim($input->get('filtroEmail',null,'Raw'));
			if(strlen($email) > 0) {
				$where .= "AND a.email LIKE '".addslashes($email)."%'";
			}
			$curso = trim($input->get('filtroCurso',null,'Raw'));
			if(strlen($curso) > 0) {
				$where .= "AND c.nome LIKE '".addslashes($curso)."%'";
			}			
			$rede = trim($input->get('filtroRede',null,'Raw'));
			if(strlen($rede) > 0 && $rede != 'Todas') {
				$where .= "AND a.rede = '".addslashes($rede)."'";
			}
			$idEscola = trim($input->get('filtroEscola',null,'Raw'));
			if(strlen($idEscola) > 0 && $idEscola != 'Todas') {
				$where .= "AND a.escola = '".addslashes($idEscola)."'";
			}
			$idEdital = trim($input->get('filtroEdital',null,'Raw'));
			if(strlen($idEdital) > 0 && $idEdital != 'Todos') {
				$where .= "AND c.edital = '".addslashes($idEdital)."'";
			}
			$turno = trim($input->get('filtroTurno',null,'Raw'));
			if(strlen($turno) > 0 && $turno != 'Todos') {
				$where .= "AND c.turno = '".addslashes($turno)."'";
			}
			$tipo = trim($input->get('filtroTipo',null,'Raw'));
			if(strlen($tipo) > 0 && $tipo != 'Todos') {
				$where .= "AND c.tipo = '".addslashes($tipo)."'";
			}
			$modalidade = trim($input->get('filtroModalidade',null,'Raw'));
			if(strlen($modalidade) > 0 && $modalidade != 'Todas') {
				$where .= "AND c.modalidade = '".addslashes($modalidade)."'";
			}

			$pIni = implode('-',array_reverse(explode('/',trim(trim($input->get('filtroPeriodoIni',null,'Raw'))))));
			$pFim = implode('-',array_reverse(explode('/',trim(trim($input->get('filtroPeriodoFim',null,'Raw'))))));
			if(strlen($pIni) > 0 && strlen($pFim) > 0) {
				$where .= "AND DATE_FORMAT(a.date_time, '%Y-%m-%d') BETWEEN '".addslashes($pIni)."' AND '".addslashes($pIni)."' ";
			}



			/******************************************************************************
			 * Totalizador
			 ******************************************************************************/
			$db->setQuery(implode(' ',["SELECT COUNT(*) AS 'qtde' ",$from_joins,$where]));
			$rTotal = $db->loadAssocList();
			//*****************************************************************************/




			$results['iTotalDisplayRecords'] = $rTotal[0]['qtde'] ?? '0';
			$results['iTotalRecords'] = $rTotal[0]['qtde'] ?? '0';
			$results['sEcho'] = intval($input->get('sEcho')) ?? 0;

			$order = "ORDER BY a.id DESC ";

			$pIni = $input->get('iDisplayStart');
			$pQtd = $input->get('iDisplayLength');
			$limit = "LIMIT ".$pIni.",".$pQtd;


			$db->setQuery(implode(' ',[$columns,$from_joins,$where,$order,$limit]));

			//$db->setQuery($sql);

			//$results = $db->loadAssocList();
			$results['aaData'] = $db->loadRowList();
		}
		catch (Exception $e)
		{
			$msg = $e->getMessage();
			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
			$results['aaData'] = null;
		}

		return $results; 
	}

	public function getDadosCsv($params)
	{
		try 
		{
			$db = JFactory::getDbo();

			$columns =  "SELECT ";

			if(trim($params['rede']) == '') {
				$columns .= "* ";
			} else {		
				$columns .= "a.id, ".
							"a.nome_completo, ".
							"a.numero_cpf, ".
							"a.rede, ".
							"d.nome as 'escola', ".
							"CONCAT(c.nome,'<br/>',c.turno,', ',c.tipo,', ',c.modalidade) as 'curso', ".
							"DATE_FORMAT(a.date_time, '%d/%m/%Y %H:%i') as 'data_hora', ".
							"a.situacao ";
			}

			$from_joins = "FROM matricula_0_matriculados a ".
					"LEFT JOIN matricula_0_escolas b ON b.id = a.escola ".
					"LEFT JOIN matricula_0_municipio d ON d.id = b.municipio ".
					"LEFT JOIN matricula_0_cursos c ON c.id = a.curso ";

			$where = "WHERE 1 > 0 ";

			$nome = trim($params['nome']);
			if(strlen($nome) > 0) {
				$where .= "AND a.nome_completo LIKE '".addslashes($nome)."%'";
			}
			$cpf = trim($params['cpf']);
			if(strlen($cpf) > 0) {
				$where .= "AND a.numero_cpf LIKE '".addslashes($cpf)."%'";
			}
			$email = trim($params['email']);
			if(strlen($email) > 0) {
				$where .= "AND a.email LIKE '".addslashes($email)."%'";
			}
			$curso = trim($params['curso']);
			if(strlen($curso) > 0) {
				$where .= "AND c.nome LIKE '".addslashes($curso)."%'";
			}			
			$rede = trim($params['rede']);
			if(strlen($rede) > 0 && $rede != 'todas-as-redes' && $rede != 'Todas') {
				$where .= "AND a.rede = '".addslashes($rede)."'";
			}
			$idEscola = trim($params['escola']);
			if(strlen($idEscola) > 0 && $idEscola != 'Todas') {
				$where .= "AND a.escola = '".addslashes($idEscola)."'";
			}
			$idEdital = trim($params['edital']);
			if(strlen($idEdital) > 0 && $idEdital != 'Todos') {
				$where .= "AND c.edital = '".addslashes($idEdital)."'";
			}
			$turno = trim($params['turno']);
			if(strlen($turno) > 0 && $turno != 'Todos') {
				$where .= "AND c.turno = '".addslashes($turno)."'";
			}
			$tipo = trim($params['tipo']);
			if(strlen($tipo) > 0 && $tipo != 'Todos') {
				$where .= "AND c.tipo = '".addslashes($tipo)."'";
			}
			$modalidade = trim($params['modalidade']);
			if(strlen($modalidade) > 0 && $modalidade != 'Todas') {
				$where .= "AND c.modalidade = '".addslashes($modalidade)."'";
			}

			$pIni = implode('-',array_reverse(explode('/',trim($params['pIni']))));
			$pFim = implode('-',array_reverse(explode('/',trim($params['pFim']))));
			if(strlen($pIni) > 0 && strlen($pFim) > 0) {
				$where .= "AND DATE_FORMAT(a.date_time, '%Y-%m-%d') BETWEEN '".addslashes($pIni)."' AND '".addslashes($pIni)."' ";
			}

			$order = "ORDER BY a.id DESC ";
			$db->setQuery(implode(' ',[$columns,$from_joins,$where,$order]));

			$results = $db->loadAssocList();
		}
		catch (Exception $e)
		{
			$msg = $e->getMessage();
			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
			$results = null;
		}

		return $results; 
	}

	public function getEscolas($config, $params)
	{
		try 
		{
			$db = JFactory::getDbo();

			$rede = [];
			if(isset($params['rede']) && trim($params['rede']) != "")
				$rede[] = addslashes($params['rede']);
			else
				$rede = $config['rede'];

			$sql = "SELECT rede, id, nome, administrador ".
					"FROM matricula_0_escolas ".
					"WHERE rede IN('".implode("','",$config['rede'])."') AND situacao = 'Ativo' ORDER BY rede, nome";
			if($config['access']['admin'] !== true){		
				$sql = "SELECT  id, nome, administrador FROM matricula_0_escolas ".
						"WHERE situacao = 'Ativo' AND rede IN('".implode("','",$rede)."') AND administrador = ".$params['id_user']." ".
						"ORDER BY rede, nome";
			}
			
			$db->setQuery($sql);

			$results = $db->loadAssocList();
		}
		catch (Exception $e)
		{
			$msg = $e->getMessage();
			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
			$results = null;
		}

		return $results; 
	}

	public function getEditais($params)
	{
		try 
		{
			$db = JFactory::getDbo();

			$sql = "SELECT a.id, a.nome ".
				"FROM matricula_0_editais a ".
				"WHERE a.situacao = 'Ativo' ";

			if(isset($params['escola']) && trim($params['escola']) != '') {
				$sql .= "AND a.escola = ".addslashes(trim($params['escola']))." ";
			}

			$sql .= "ORDER BY a.id DESC";
			
			$db->setQuery($sql);

			$results = $db->loadAssocList();
		}
		catch (Exception $e)
		{
			$msg = $e->getMessage();
			JFactory::getApplication()->enqueueMessage($msg, 'error'); 
			$results = null;
		}

		return $results; 
	}
}