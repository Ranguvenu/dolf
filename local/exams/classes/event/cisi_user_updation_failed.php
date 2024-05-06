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
 * Event for successful MFA authorization.
 *
 * @package     factor_kvbiometric
 * @author      eAbyas
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_exams\event;

defined('MOODLE_INTERNAL') || die();

/**
 * 
 *
 * @property-read array $other {
 *      Extra information about event.
 * }
 *
 * @package     local_exams
 * @author      eAbyas
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class cisi_user_updation_failed extends \core\event\base {
     /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'local_exams';
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        global $DB;
        return  "CISI user updation was failed for the user having CISI id {$this->data['objectid']} and FA id {$this->data['userid']} due to following '{$this->other['results'][0]['message']}'";       
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('cisi_user_updation_failed', 'local_exams'); 
    }

    /**
     * Get URL related to the action
     *
     * @return \moodle_url
     */
    public function get_url() {
        global $DB;
       
    }

    /**
     * Return the legacy event log data.
     *
     * @return array|null
     */
    protected function get_legacy_logdata() {
      
    }   

    public static function get_objectid_mapping() {
      
    }

    public static function get_other_mapping() {
     
    }
}
