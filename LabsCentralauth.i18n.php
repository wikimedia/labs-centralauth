<?php
/**
 * Internationalisation file for LabsCentralauth extension.
 *
 * @file
 * @ingroup Extensions
 */

$messages = array();

/** English
 * @author Jan Luca
 */
$messages['en'] = array(
	'labscentralauth' => 'LabsCentralAuth',
	'labscentralauth-desc' => 'Extension for CentralAuth-project',
	'right-labscentralauth-use-review' => 'Use LabsCentralauth-review instances',
	'right-labscentralauth-use-dev' => 'Use LabsCentralauth-dev instances',
	'right-labscentralauth-admin' => 'Control LabsCentralauth instances',
	'action-labscentralauth-use-review' => 'use LabsCentralauth-review instances',
	'action-labscentralauth-use-dev' => 'use LabsCentralauth-dev instances',
	'action-labscentralauth-admin' => 'control LabsCentralauth instances',
	'labscentralauth-what' => 'What do you want to do?',
	'labscentralauth-submit' => 'Submit',
	'labscentralauth-main-review' => 'Review a Gerrit change',
	'labscentralauth-main-dev' => 'Develop CentralAuth',
	'labscentralauth-review-id' => 'Gerrit Change-ID:',
	'labscentralauth-review-time' => 'Time for Review',
	'labscentralauth-review-15-minutes' => '15 Minutes',
	'labscentralauth-review-30-minutes' => '30 Minutes',
	'labscentralauth-review-1-hour' => '1 Hour',
	'labscentralauth-review-2-hours' => '2 Hours',
	'labscentralauth-review-3-hours' => '3 Hours',
	'labscentralauth-review-6-hours' => '6 Hours',
	'labscentralauth-review-block-for' => 'Use instance for',
	'labscentralauth-review-blocked-till' => 'Usage expires',
	'labscentralauth-review-end' => 'Finish review',
	'labscentralauth-review-start-successful' => 'The change was successful loaded to the instance "$1". You can start your review under this URL now: [$2 $2].',
	'labscentralauth-review-start-no-free' => 'There is no free review-instance at moment. Please try later.',
	'labscentralauth-review-start-project-wrong' => 'The Gerrit Change-ID "$1" is not part of the project "$2".',
	'labscentralauth-review-start-not-open' => 'The Gerrit Change-ID "$1" is not open anymore (maybe already merged?).',
	'labscentralauth-review-start-id-invalid' => 'The Gerrit Change-ID "$1" seems to be invalid. Maybe a copy-and-paste-bug?',
	'labscentralauth-review-end-successful' => 'You successfully released the instance "$1".',
	'labscentralauth-review-end-not-in-use' => 'Review instance "$1" is not in use.',
	'labscentralauth-review-end-instance-unknown' => 'Review instance "$1" is unknown.',
	'labscentralauth-back' => 'Back',
	'labscentralauth-not-blocked' => '(not in use)',
	'labscentralauth-status' => 'Status',
	'labscentralauth-wiki' => 'Wiki instance',
	'labscentralauth-user' => 'Wiki user',
	'labscentralauth-actions' => 'Actions',
	'labscentralauth-free' => 'Free',
	'labscentralauth-inuse' => 'In use',
	'labscentralauth-reset' => 'Reset instance',
	'labscentralauth-dev-start' => 'Start developing',
	'labscentralauth-dev-start-no-free' => 'There is no free dev-instance at moment. Please try again later.',
	'labscentralauth-dev-start-successful' => 'You successfully got the instance "$1" to use for developing. The URL is [$2 $2] and the path with the files of CentralAuth is "$3". The Git-branch is "$4".',
	'labscentralauth-dev-end' => 'End developing',
	'labscentralauth-dev-end-not-in-use' => 'Dev instance "$1" is not in use.',
	'labscentralauth-dev-end-instance-unknown' => 'Dev instance "$1" is unknown.',
	'labscentralauth-error-page-title' => 'Error in $1',
	'labscentralauth-error-page-title-fatal' => 'Fatal error in $1',
	'labscentralauth-exec-proc-open' => 'Output of proc_open() for command "$1" and systempath "$2" is not a resource. Caller was $3',
	'labscentralauth-exec-stderr' => 'STDERR not empty: $1',
);
