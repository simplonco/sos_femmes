<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

session_start();

class Detaills_logement extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->library('gc_dependent_select');
        $this->check_isvalidated();
        $this->load->model('navigation');
    }

    function _example_output($output = null) {
        $this->load->view('template', $output);
    }

    public function index() {
        try {

// General
            $crud = new grocery_CRUD();
            $crud->set_language("french");
            $crud->set_theme('datatables');
            //$crud->set_theme('twitter-bootstrap');
            $crud->set_table('sos_gen_logement_child');
            $crud->set_subject('Logement détaillé');

//relations
            $crud->set_relation('id_from_logement_parent', 'sos_gen_logement_parent', 'name_logement');
//Master/child relations
//Visual

            $crud->columns('name_logement_child', 'id_from_logement_parent');
            $crud->display_as('id_from_logement_parent', 'Logement');
            $crud->display_as('name_logement_child', 'Logement détaillé');
            $crud->order_by('name_logement_child', 'desc');
//unsets
//Requireds
            $crud->required_fields('name_logement_child', 'id_from_logement_parent');
//Callbacks
//field Types
// Actions
// Renders
            $output = $crud->render();
            $menu = new stdClass;
            $menu->n1 = true;
            $menu->lien3_1_3_2 = false;
            $menu->status = $this->session->userdata('status');
            $header = $this->navigation->home_f($menu);

            $data = array('output' => $output, 'header' => $header);

            $this->_example_output($data);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    private function check_isvalidated() {
        if (!$this->session->userdata('validated')) {
            redirect('login');
        }
    }

    public function do_logout() {
        $this->session->sess_destroy();
        redirect('login');
    }

}

?>
