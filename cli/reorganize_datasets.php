<?php

namespace CKAN\Manager;

use CKAN\OrganizationList;

/**
 * http://www.data.gov/app/themes/roots-nextdatagov/assets/Json/fed_agency.json
 */
define('ORGANIZATION_TO_TAG', 'General Services Administration');

/**
 * Make it TRUE, if you want datasets to be marked as PRIVATE
 */
define('MARK_PRIVATE', true);

require_once dirname(__DIR__) . '/inc/common.php';

/**
 * Get organization terms, including all children, as Array
 */
$OrgList = new OrganizationList(AGENCIES_LIST_URL);
$termsArray = $OrgList->getTreeArrayFor(ORGANIZATION_TO_TAG);

/**
 * sometimes there is no parent term (ex. Department of Labor)
 */
if (!defined('PARENT_TERM')) {
    define('PARENT_TERM', '_');
}

/**
 * Create results dir for logs
 */
$results_dir = CKANMNGR_RESULTS_DIR . date('/Ymd-His') . '_LEGACY_' . PARENT_TERM;
mkdir($results_dir);

$CkanManager = new CkanManager(CKAN_API_URL, CKAN_API_KEY);
//$CkanManager = new CkanManager(CKAN_STAGING_API_URL, CKAN_STAGING_API_KEY);

$CkanManager->resultsDir = $results_dir;

$CkanManager->reorganizeDatasets(ORGANIZATION_TO_TAG, $termsArray, CKANMNGR_BACKUP_DIR);

// show running time on finish
timer();
