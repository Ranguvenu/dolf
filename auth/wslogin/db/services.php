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
 * Web services for auth_wslogin.
 *
 * @package    auth_wslogin
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$functions = array(
    'auth_wslogin_request_login_url' => array(
        'classname'   => 'auth_wslogin_external',
        'methodname'  => 'request_login_url',
        'classpath'   => 'auth/wslogin/externallib.php',
        'description' => 'Return one time key based login URL',
        'type'        => 'write',
        'capabilities'  => 'auth/wslogin:generatekey',
    )
);

$services = array(
    'User authentication using web service' => array(
        'functions' => array ('auth_wslogin_request_login_url'),
        'restrictedusers' => 1,
        'enabled' => 1,
    )
);
