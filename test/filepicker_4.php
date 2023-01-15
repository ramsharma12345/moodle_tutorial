form.php
------------------------
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

        // filemanager
        $maxbytes = get_max_upload_sizes();
        $mform->addElement('filemanager', 'shree_attachments', 'Attachment 1', null,
        array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
        'accepted_types' => '*', 'return_types'=> FILE_INTERNAL | FILE_EXTERNAL));
        

        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}


-----------------
  index.php
---------------
  
  
<?php
//include simplehtml_form.php

require_once('../config.php');
global $CFG, $DB, $USER, $OUTPUT;
require_once($CFG->dirroot . '/test/form.php');

$redirect = $CFG->wwwroot . '/test/index.php';

echo $OUTPUT->header();
//Instantiate simplehtml_form 
$mform = new simplehtml_form();

$idid = optional_param('id', 0, PARAM_INT);
$entry = new stdClass;
if ($idid < 1) {
  $entry->id = null;
} else {
  $entry = $DB->get_record('email_list', array('id' => $idid), '*', MUST_EXIST);
}
 

/////////// Display Uploaded Images...
 
$context = context_system::instance();  

$files11 = $DB->get_record_sql("select * from {files} where component = ? AND filearea=? AND itemid = ? AND filename!='.' ", array(
  'ram_component', 'ram_filearea', $entry->id
));

$component = $files11->component;
$filearea = $files11->filearea;
$filename =  $files11->filename;
$itemid = $entry->id;
   
$fullpath = $CFG->wwwroot."/pluginfile.php/$context->id/$component/$filearea/$entry->id/$filename" ; 
echo $fullpath ;

//////////////////
 
  

$draftitemid = file_get_submitted_draft_itemid('shree_attachments');

file_prepare_draft_area(
  $draftitemid,
  $context->id,
  'ram_component',
  'ram_filearea',
  $entry->id,
  array('subdirs' => 0,  'maxfiles' => 50)
);

$entry->shree_attachments = $draftitemid;


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

  // ... store or update $entry
  $insertID = $DB->insert_record('email_list', $data);

  // save file with required component and filearea. itemid = $insertID so that this can be identified.. with the id of insert into {email_list} table
  file_save_draft_area_files(
    $fromform->shree_attachments,
    $context->id,
    'ram_component',
    'ram_filearea',
    $insertID,
    array('subdirs' => 0,   'maxfiles' => 1)
  );


  redirect($redirect, 'Record have been added successfully.', null, \core\output\notification::NOTIFY_SUCCESS);

  // id, email, added_time, added_by     

} else {
  //Set default data (if any)
  $mform->set_data($entry);
  //displays the form
  $mform->display();
}
echo $OUTPUT->footer();

--------
  //changes in lib/filelib.php function name file_pluginfile()  inside else block of this function....
--------
  
  if ($component === 'ram_component') {  
      $fullpath = "/$context->id/$component/$filearea/".implode('/', $args);
      if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
          send_file_not_found();
      }
      \core\session\manager::write_close(); // Unlock session during file serving.
      send_stored_file($file, 60*60, 0, $forcedownload, $sendfileoptions);
  }

  
