<?php

require_once 'dailyscheduledjob.civix.php';
use CRM_Dailyscheduledjob_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function dailyscheduledjob_civicrm_config(&$config) {
  _dailyscheduledjob_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function dailyscheduledjob_civicrm_xmlMenu(&$files) {
  _dailyscheduledjob_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function dailyscheduledjob_civicrm_install() {
  _dailyscheduledjob_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function dailyscheduledjob_civicrm_postInstall() {
  _dailyscheduledjob_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function dailyscheduledjob_civicrm_uninstall() {
  _dailyscheduledjob_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function dailyscheduledjob_civicrm_enable() {
  _dailyscheduledjob_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function dailyscheduledjob_civicrm_disable() {
  _dailyscheduledjob_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function dailyscheduledjob_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _dailyscheduledjob_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function dailyscheduledjob_civicrm_managed(&$entities) {
  _dailyscheduledjob_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function dailyscheduledjob_civicrm_caseTypes(&$caseTypes) {
  _dailyscheduledjob_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function dailyscheduledjob_civicrm_angularModules(&$angularModules) {
  _dailyscheduledjob_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function dailyscheduledjob_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _dailyscheduledjob_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function dailyscheduledjob_civicrm_entityTypes(&$entityTypes) {
  _dailyscheduledjob_civix_civicrm_entityTypes($entityTypes);
}

function dailyscheduledjob_civicrm_preJob($job, $params) {
  /*Civi::log()->debug('dailyscheduledjob_civicrm_preJob', array(
    'job' => $job,
    'params' => $params,
    'dbdate' => CRM_Utils_Date::currentDBDate(),
  ));*/
}

function dailyscheduledjob_civicrm_postJob($job, $params, $result) {
  /*Civi::log()->debug('dailyscheduledjob_civicrm_postJob', array(
    'job' => $job,
    'params' => $params,
    'result' => $result,
    'dbdate' => CRM_Utils_Date::currentDBDate(),
  ));*/

  if ($job->run_frequency == 'Daily' && !empty($job->scheduled_run_date)) {
    CRM_Core_DAO::executeQuery('
      UPDATE civicrm_job SET scheduled_run_date = %1 WHERE id = %2
    ', array(
      '1' => array(date('Y-m-d H:i:s', strtotime('+1 day', strtotime($job->scheduled_run_date))), 'String'),
      '2' => array($job->id, 'Integer'),
    ));

    $check = CRM_Core_DAO::singleValueQuery("SELECT scheduled_run_date FROM civicrm_job WHERE id = {$job->id}");
  }
}
