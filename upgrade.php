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

    return true;
}
