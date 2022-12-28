
<?php 
//include simplehtml_form.php

require_once('../config.php');
global $CFG, $DB, $USER, $OUTPUT;
require_once($CFG->dirroot.'/test/form.php');

$redirect = $CFG->wwwroot.'/test/index.php';

echo $OUTPUT->header();
//Instantiate simplehtml_form 
$mform = new simplehtml_form();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    echo "You has clicked on cancel button.";
} else if ($fromform = $mform->get_data()) {

    // print_r($fromform);
    
    // your code for insert into database
    $data = new stdClass;
    $data->email = $fromform->email;
    $data->added_time = time();
    $data->added_by   = $USER->id;

    $DB->insert_record('email_list', $data);
    
    redirect($redirect, 'Record have been added successfully.', null, \core\output\notification::NOTIFY_SUCCESS);

    // id, email, added_time, added_by     
   
} else {
  //Set default data (if any)
  $mform->set_data($toform);
  //displays the form
  $mform->display();
}
