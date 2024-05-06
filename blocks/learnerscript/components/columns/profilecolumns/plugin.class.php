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
 * @author: Jahnavi Nanduri
 * @date: 2023
 */
namespace block_learnerscript\lsreports;
use block_learnerscript\local\pluginbase;
use block_learnerscript\local\ls;
use html_writer;
use context_system;

class plugin_profilecolumns extends pluginbase {

    public function init() {
        $this->fullname = get_string('profilecolumns', 'block_learnerscript');
        $this->type = 'undefined';
        $this->form = true;
        $this->reporttypes = array('examprofiles');
    }

    public function summary($data) {
        return format_string($data->columname);
    }

    public function colformat($data) {
        $align = (isset($data->align)) ? $data->align : '';
        $size = (isset($data->size)) ? $data->size : '';
        $wrap = (isset($data->wrap)) ? $data->wrap : '';
        return array($align, $size, $wrap);
    }

    public function execute($data, $row, $user, $courseid, $starttime = 0, $endtime = 0) {
        global $DB, $CFG;
        $context = context_system::instance();
        switch ($data->column) {
            case 'enrollments':
                if(!isset($row->enrollments) && isset($data->subquery)){
                    $enrollments =  $DB->get_field_sql($data->subquery);
                }else{
                    $enrollments = $row->{$data->column};
                }
                if (!empty($enrollments)) { 
                    if(is_siteadmin() || has_capability('local/organization:manage_trainingofficial',$context) 
                           || has_capability('local/organization:manage_organizationofficial',$context)){
                        $enrollments = html_writer::link("$CFG->wwwroot/local/exams/examusers.php?id=$row->profileexamid", $enrollments, array("target" => "_blank"));
                    } else if(has_capability('local/organization:manage_trainee', $context) || has_capability('local/organization:manage_trainer', $context)) {
                        $enrollments = $enrollments;
                    }
                } else {
                    $enrollments = 0;
                } 
                $row->{$data->column} = $enrollments; 
            break;
            case 'completions':
                if(!isset($row->completions) && isset($data->subquery)){
                    $completions =  $DB->get_field_sql($data->subquery);
                }else{
                    $completions = $row->{$data->column};
                }
                $row->{$data->column} = !empty($completions) ? $completions : 0; 
            break;
        }
        return (isset($row->{$data->column}))? $row->{$data->column} : ' -- ';
    }
}
