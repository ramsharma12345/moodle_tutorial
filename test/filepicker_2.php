forms.php
-------------------------------
<?php 
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class simplehtml_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
       
        $mform = $this->_form; // Don't forget the underscore! 

        $mform->addElement('text', 'email', get_string('email')); // Add elements to your form.
        $mform->setType('email', PARAM_NOTAGS);                   // Set type of element.
        $mform->setDefault('email', 'Please enter email');        // Default value.

        // add filepicker to upload file..
        $mform->addElement('filepicker', 'shree_file', get_string('file'), null,
                   array('maxbytes' => 11111111111111, 'accepted_types' => array('.vtt1')));

        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}


----------------------
  index.php
---------------------
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

    $file = $mform->get_new_filename('shree_file');
    $fullpath = "upload/".$file;
    $success = $mform->save_file('shree_file', $fullpath,true);
    // echo $fullpath; die;
    if(!$success){
      echo "Oops! something went wrong!";      
    }
    $data->file_path   = $fullpath;
    $DB->insert_record('email_list', $data);
    
    redirect($redirect, 'Record have been added successfully.', null, \core\output\notification::NOTIFY_SUCCESS);

    // id, email, added_time, added_by     
   
} else {
  //Set default data (if any)
  $mform->set_data($toform);
  //displays the form
  $mform->display();
}
echo $OUTPUT->footer();

-----
  DB
-----  
CREATE TABLE IF NOT EXISTS `mdl_email_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) DEFAULT NULL,
  `added_time` bigint(20) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
