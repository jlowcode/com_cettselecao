<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cettselecao
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

class CettSelecaoControllerInscricoes extends JControllerForm
{   
    public function csv() {
        $input = JFactory::getApplication()->input;
        $params = $input->get('params',null,'Array');
        if($params['rede'] == '')
            $params['rede'] = 'todas-as-redes';

        $model = $this->getModel();
		$records = $model->getDadosCsv($params);

        if(isset($records) && sizeof($records) > 0) {
            $dados = [];
            $dados[] = [
                'Inscrição',
                'Nome Completo',
                'Numero do Cpf',
                'Rede',
                'Escola',
                'Curso',
                'Data/Hora',
                'Situação'
            ];
            foreach($records as $item){
                $dados[] = [
                    $item['id'],
                    $item['nome_completo'],
                    $item['numero_cpf'],
                    $item['rede'],
                    $item['escola'],
                    $item['curso'],
                    $item['data_hora'],
                    $item['situacao']
                ];
            }

            header('Cache-Control: max-age=0');
            header('Content-Description: File Transfer');
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename="incricoes-'.$params['rede'].'-'.date('Y-m-d-H-i-s').'.csv";');
            $arqSaida = fopen('php://output', 'w');
            foreach ($dados as $linha) {
                fputcsv($arqSaida, $linha, ';');
            }
        }
    }
}