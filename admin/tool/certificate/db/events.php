<?php
// This file is part of the tool_certificate plugin for Moodle - http://moodle.org/
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
 * Events for tool_certificate.
 *
 * @package   tool_certificate
 * @copyright 2020 Moodle Pty Ltd <support@moodle.com>
 * @author    2020 Mikel Martín <mikel@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$observers = [
    [
        'eventname' => '\core\event\course_content_deleted',
        'callback' => tool_certificate_observer::class . '::on_course_content_deleted'
    ],
    // eabyas added 
    [
        'eventname'   => '\local_trainingprogram\event\trainingprogram_completion_updated',
        'callback'    => tool_certificate_observer::class . '::issue_trainingprogram_certificate',
    ],
    [
        'eventname'   => '\local_exams\event\exam_completion_updated',
        'callback'    => tool_certificate_observer::class . '::issue_exam_certificate',
    ],
    [
        'eventname'   => '\local_events\event\events_completions',
        'callback'    => tool_certificate_observer::class . '::issue_event_certificate',
    ],
    [
        'eventname'   => '\local_learningtracks\event\learningtracks_completion_updated',
        'callback'    => tool_certificate_observer::class . '::issue_learningtrack_certificate',
    ],
];
