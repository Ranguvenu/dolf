<?php
/**
 * This file is part of eAbyas
 *
 * Copyright eAbyas Info Solutons Pvt Ltd, India
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author eabyas  <info@eabyas.in>
 * @package Bizlms 
 * @subpackage local_projects
 */
namespace local_learningtracks\event;

defined('MOODLE_INTERNAL') || die();

class learning_track_updated extends \core\event\base {
    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'local_learningtracks';
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        global $DB;
        return "The user with id {$this->userid} is updated learning track. Learning track id {$this->objectid}.";
 
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('update_learning_track', 'local_learningtracks');
    }

    /**
     * Get URL related to the action
     *
     * @return \moodle_url
     */
    public function get_url() {
        global $DB;
        $url = new \moodle_url('/local/learningtracks/index.php');
        return $url;
    }

    public static function get_objectid_mapping() {
        return array('db' => 'local_learningtracks', 'restore' => 'local_learningtracks');
    }

    public static function get_other_mapping() {
        $othermapped = array();
        $othermapped['id'] = array('db' => 'local_learningtracks', 'restore' => 'local_learningtracks');

        return $othermapped;
    }
}