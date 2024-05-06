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
 * @author: eAbyas Info Solutions
 * @date: 2022
 */
namespace block_learnerscript\lsreports;
use block_learnerscript\local\pluginbase;
use stdClass;

class plugin_competencyfilters extends pluginbase {

    public function init() {
        $this->form = false;
        $this->unique = true; 
        $this->placeholder = true;
        $this->singleselection = true;
        $this->fullname = get_string('filtercompetencyfilters', 'block_learnerscript');
        $this->reporttypes = array('competencies');
        $this->filtertype = 'custom';
        $this->selecttype = 'advanced';
        if (!empty($this->reportclass->basicparams)) {
            foreach ($this->reportclass->basicparams as $basicparam) {
                if ($basicparam['name'] == 'competencyfilters') {
                    $this->filtertype = 'basic';
                }
            }
        }
    }

    public function summary($data) {
        return get_string('filtercompetencyfilters_summary', 'block_learnerscript');
    }

    public function execute($finalelements, $data, $filters) {

        $filtercompetencyfilters = isset($filters['filter_competencyfilters']) ? $filters['filter_competencyfilters'] : 0;
        if (!$filtertrainingtype) {
            return $finalelements;
        }

        if ($this->report->type != 'sql') {
            return array($filtercompetencyfilters);
        } else {
            if (preg_match("/%%FILTER_COMPETENCYFILTERS:([^%]+)%%/i", $finalelements, $output)) {
                $replace = ' AND ' . $output[1] . ' = ' . $filtertrainingtype;
                return str_replace('%%FILTER_COMPETENCYFILTERS:' . $output[1] . '%%', $replace, $finalelements);
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
            $components = (new \block_learnerscript\local\ls)->cr_unserialize($this->report->components);
        } else {
            $competencyfilterslist = array();
        }

        $competencyfilteroptions = array();
        if($selectoption){
            $competencyfilteroptions[0] = $this->singleselection ?
                get_string('filter_competencyfilters', 'block_learnerscript') : get_string('select') .' '. get_string('competencyfilters', 'block_learnerscript');
        }
        return $competencyfilteroptions;
    }
    public function selected_filter($selected) {
        $filterdata = $this->filter_data();
        return $filterdata[$selected];
    }
    public function print_filter(&$mform) {
        $competencyfilteroptions = $this->filter_data(); 
        if ((!$this->placeholder || $this->filtertype == 'basic') && COUNT($competencyfilteroptions) > 1) {
            unset($competencyfilteroptions[0]);
        }
        $select = $mform->addElement('select', 'filter_competencyfilters', get_string('competencyfilters', 'block_learnerscript'), $competencyfilteroptions, array('data-select2' => 1));
        $select->setHiddenLabel(true);
        $mform->setType('filter_competencyfilters', PARAM_INT);
    }

}