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
    private $ids_group_admin = [7,8];
    private $ids_group_users = [18,19,20,21,25,26];

    public function filter() {
        $input = JFactory::getApplication()->input;

        $model = $this->getModel();
		$records = $model->getDados($input);
    
        echo json_encode($records);
    }

    public function access() {
        echo json_encode($this->config());
    }

    public function escolas() {
        $input = JFactory::getApplication()->input;
		$paramsData = $input->get('params', array(), 'ARRAY');

        $model = $this->getModel();
		$records = $model->getEscolas($this->config(),$paramsData);
    
        echo json_encode($records);
    }

    public function editais() {
        $input = JFactory::getApplication()->input;
		$paramsData = $input->get('params', array(), 'ARRAY');

        $model = $this->getModel();
		$records = $model->getEditais($paramsData);
    
        echo json_encode($records);
    }

    private function config() {
        $user   = JFactory::getUser();
        $groups = $user->get('groups');

        $config = [];

        $config['id_user'] = $user->get('id');
        $config['groups']  = $user->get('groups');
        $config['access']['admin'] = false;
        $config['access']['efg'] = false;
        $config['access']['cotec'] = false;
        $config['access']['goiastec'] = false;
        $config['access']['juventude'] = false;

        $config['rede'] = [];

        foreach($groups as $id) {
            if(in_array($id,$this->ids_group_admin)) {
                $config['access']['admin'] = true;
                $config['rede'] = ['EFG','COTEC','GOIASTEC','JUVENTUDE'];
            }

            switch($id){
                case 19:
                    $config['access']['efg'] = true;
                    if(sizeof($config['rede']) != 4)
                        $config['rede'][] = 'EFG';
                    break;
                case 20:
                    $config['access']['cotec'] = true;
                    if(sizeof($config['rede']) != 4)
                        $config['rede'][] = 'COTEC';
                    break;
                case 25:
                    $config['access']['goiastec'] = true;
                    if(sizeof($config['rede']) != 4)
                        $config['rede'][] = 'GOIASTEC';
                    break;
                case 26:
                    $config['access']['juventude'] = true;
                    if(sizeof($config['rede']) != 4)
                        $config['rede'][] = 'JUVENTUDE';
                    break;
            }
        }

        return $config;
    }
}