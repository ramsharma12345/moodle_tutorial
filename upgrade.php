<?php
/**
 * This file keeps track of upgrades to the listallcourses block
 *
 * @since 3.8
 * @package block_listallcourses
 * @copyright 2019 Jake Dallimore <jrhdallimore@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/db/upgradelib.php");

/**
 * Upgrade code for the listallcourses block.
 *
 * @param int $oldversion
 */
function xmldb_block_listallcourses_upgrade($oldversion) {
    global $DB, $CFG, $OUTPUT;  
    $dbman = $DB->get_manager(); 

    if ($oldversion < 2023022117) {        
        $table = new xmldb_table('block_listallcoures');
        $field = new xmldb_field('ip_address', XMLDB_TYPE_CHAR, '20', null, null, null, '0', 'userid');

        // Conditionally launch add field duration.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_block_savepoint(true, 2023022118, 'listallcourses', false);
    }  

    if ($oldversion < 2023022119) {        
        $table = new xmldb_table('block_listallcoures');
        $field = new xmldb_field('aceess_address', XMLDB_TYPE_CHAR, '20', null, null, null, '0', 'time_accessed');

        // Conditionally launch add field duration.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_block_savepoint(true, 2023022119, 'listallcourses', false);
    }  

    if ($oldversion < 2023022120) {        
        $table = new xmldb_table('block_listallcoures');
        $field = new xmldb_field('address', XMLDB_TYPE_CHAR, '20', null, null, null, '0', 'aceess_address');

        // Conditionally launch add field duration.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_block_savepoint(true, 2023022120, 'listallcourses', false);
    }  

    if ($oldversion < 2023022121) {        

            $table = new xmldb_table('block_listallcoures_test');

            // Adding fields to table block_listallcoures_test_results.
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('attemptid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table->add_field('subcontent', XMLDB_TYPE_CHAR, '128', null, null, null, null);
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table->add_field('interactiontype', XMLDB_TYPE_CHAR, '128', null, null, null, null);
            $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
            $table->add_field('correctpattern', XMLDB_TYPE_TEXT, null, null, null, null, null);
            $table->add_field('response', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
            $table->add_field('additionals', XMLDB_TYPE_TEXT, null, null, null, null, null);
            $table->add_field('rawscore', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('maxscore', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

            // Adding keys to table block_listallcoures_test_results.
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $table->add_key('fk_attemptid', XMLDB_KEY_FOREIGN, ['attemptid'], 'block_listallcoures_test', ['id']);

            // Adding indexes to table block_listallcoures_test_results.
            $table->add_index('attemptid-timecreated', XMLDB_INDEX_NOTUNIQUE, ['attemptid', 'timecreated']);

            // Conditionally launch create table for block_listallcoures_test_results.
            if (!$dbman->table_exists($table)) {
                $dbman->create_table($table);
            }
            upgrade_block_savepoint(true, 2023022121, 'listallcourses', false);
    }  

    if ($oldversion < 2023022122) {     

        // Changing the default of field timecreated on table h5pactivity to drop it.
        $table = new xmldb_table('block_listallcoures_test');
        $field = new xmldb_field('maxscore', XMLDB_TYPE_INTEGER, '14', null, XMLDB_NOTNULL, null, null);

        // Launch change of default for field timecreated.
        $dbman->change_field_default($table, $field);        
        upgrade_block_savepoint(true, 2023022122, 'listallcourses', false);
    }  

    if ($oldversion < 2023022123) {  

        // Changing the default of field timecreated on table h5pactivity to drop it.
        $table = new xmldb_table('block_listallcoures_test');
        $field = new xmldb_field('maxscore', XMLDB_TYPE_INTEGER, '14', null, XMLDB_NOTNULL, null, null);

        //  delete feild from table
        $dbman->drop_field($table, $field, $continue=true, $feedback=true);
   
        upgrade_block_savepoint(true, 2023022123, 'listallcourses', false);
    }  

    return true;
}
