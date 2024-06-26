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
 * Forgot password page.
 *
 * @package    core
 * @subpackage auth
 * @copyright  2006 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_once('lib.php');

/**
 * Reset forgotten password form definition.
 *
 * @package    core
 * @subpackage auth
 * @copyright  2006 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class login_forgot_password_form extends moodleform {

    /**
     * Define the forgot password form.
     */
    function definition() {
        global $USER;

        $mform    = $this->_form;
        $mform->setDisableShortforms(true);

        // Hook for plugins to extend form definition.
        core_login_extend_forgot_password_form($mform);
        $mform->addElement('html', '<div class="forgotpassword p-4 pb-0">');

        // $mform->addElement('header', 'searchbyusername', get_string('searchbyusername'), '');
        $mform->addElement('html', '<div class="searchusername row justify-content-center align-items-center p-5">');
        $mform->addElement('html','<div class="col-8 p-0">');
        $purpose = user_edit_map_field_purpose($USER->id, 'username');
        $mform->addElement('text', 'username', get_string('searchbyusername'),'maxlength="100" size="40"' . $purpose);
        $mform->setType('username', PARAM_RAW);
        $mform->addElement('html','</div>');
        $mform->addElement('html','<div class="col-4 p-0">');
        $submitlabel = get_string('search');
        $mform->addElement('submit', 'submitbuttonusername', $submitlabel);
        $mform->addElement('html','</div>');
        $mform->addElement('html','</div>');

        // $mform->addElement('header', 'searchbyemail', get_string('searchbyemail'), '');
        $mform->addElement('html', '<div class=" searchemailname row justify-content-center align-items-center p-5">');
        $mform->addElement('html','<div class="col-8 p-0">');
        $purpose = user_edit_map_field_purpose($USER->id, 'email');
        $mform->addElement('text', 'email',  get_string('searchbyemail'), 'maxlength="100" size="40"' . $purpose);
        $mform->setType('email', PARAM_RAW_TRIMMED);
        $mform->addElement('html','</div>');
        $mform->addElement('html','<div class="col-4 p-0">');
        $submitlabel = get_string('search');
        $mform->addElement('submit', 'submitbuttonemail', $submitlabel);
        $mform->addElement('html','</div>');
        $mform->addElement('html','</div>');
        $mform->addElement('html','</div>');

    }

    /**
     * Validate user input from the forgot password form.
     * @param array $data array of submitted form fields.
     * @param array $files submitted with the form.
     * @return array errors occuring during validation.
     */
    function validation($data, $files) {

        $errors = parent::validation($data, $files);

        // Extend validation for any form extensions from plugins.
        $errors = array_merge($errors, core_login_validate_extend_forgot_password_form($data));

        $errors += core_login_validate_forgot_password_data($data);

        return $errors;
    }

}
