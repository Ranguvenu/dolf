<?php
require_once('../../config.php');
global $DB, $CFG, $OUTPUT,$USER, $PAGE;
redirect($CFG->wwwroot.'/404.php');
// $systemcontext = context_system::instance();
// $PAGE->set_context($systemcontext);
// $PAGE->set_title("404 Error");
// $PAGE->set_url('/theme/404.php');
// $PAGE->set_pagelayout('base');
// echo $OUTPUT->header();
// $data = [
//  'dashboardurl'=>$CFG->wwwroot
// ];
// echo $OUTPUT->render_from_template('theme_academy/404',$data);
// echo $OUTPUT->footer();
