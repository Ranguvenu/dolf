<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * LearnerScript Reports
 * A Moodle block for creating customizable reports
 * @package blocks
 * @author: Sudharani Sadula
 * @date: 2023
 */
namespace block_learnerscript\lsreports;
use block_learnerscript\local\pluginbase;
use stdClass;

class plugin_registrationstartdate extends pluginbase {

    public function init() {
        $this->form = false;
        $this->unique = true; 
        $this->placeholder = true;
        $this->singleselection = true;
        $this->fullname = get_string('filterregistrationstartdate', 'block_learnerscript');
        $this->reporttypes = array('offerings');
        $this->filtertype = 'custom';
        if (!empty($this->reportclass->basicparams)) {
            foreach ($this->reportclass->basicparams as $basicparam) {
                if ($basicparam['name'] == 'registrationstartdate') {
                    $this->filtertype = 'basic';
                }
            }
        }
    }

    public function summary($data) {
        return get_string('filterregistrationstartdate_summary', 'block_learnerscript');
    }

    public function execute($finalelements, $data, $filters) {

        $filterregistrationstartdate = isset($filters['filter_registrationstartdate']) ? $filters['filter_registrationstartdate'] : 0;
        if (!$filterregistrationstartdate) {
            return $finalelements;
        }

        if ($this->report->type != 'sql') {
            return array($filterregistrationstartdate);
        } else {
            if (preg_match("/%%FILTER_REGISTRATIONSTARTDATE:([^%]+)%%/i", $finalelements, $output)) {
                $replace = ' AND ' . $output[1] . ' = ' . $filterregistrationstartdate;
                return str_replace('%%FILTER_REGISTRATIONSTARTDATE:' . $output[1] . '%%', $replace, $finalelements);
            }
        }
        return $finalelements;
    }
    public function filter_data($selectoption = true){
        global $DB, $CFG;
        $properties = new stdClass();
        $properties->courseid = SITEID;

        $reportclassname = 'block_learnerscript\lsreports\report_' . $this->report->type;
        $reportclass = new $reportclassname($this->report, $properties);

        if ($this->report->type != 'sql') {
            $component = (new \block_learnerscript\local\ls)->cr_unserialize($this->report->registrationstartdate);
        } else {
            $registrationstartdatelist = array();
        }

        $registrationstartdateoptions = array();
        if($selectoption){
            $registrationstartdateoptions[0] = $this->singleselection ?
                get_string('filter_registrationstartdate', 'block_learnerscript') : get_string('select') .' '. get_string('registrationstartdate', 'block_learnerscript');
        }

        if (empty($registrationstartdatelist)) {
            $registrationstartdateoptions = '';
        }
        return $registrationstartdateoptions;
    }
    public function selected_filter($selected) {
        $filterdata = $this->filter_data();
        return $filterdata[$selected];
    }
    public function print_filter(&$mform) {
        $registrationstartdateoptions = $this->filter_data(); 
        if ((!$this->placeholder || $this->filtertype == 'basic') && COUNT($registrationstartdateoptions) > 1) {
            unset($registrationstartdateoptions[0]);
        }
         $mform->addElement('date_selector', 'filter_registrationstartdate', get_string('registrationstartdate', 'block_learnerscript'),array('optional' => true));
         $mform->addElement('hidden',  'visible',  0);
        $mform->setType('visible', PARAM_INT);
    }

}