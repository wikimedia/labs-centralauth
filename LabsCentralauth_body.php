<?php
error_reporting(E_ALL | E_STRICT);

/**
 * LabsCentralauth_body - Body for the Special Page Special:LabsCentralauth
 *
 * @ingroup Extensions
 * @author Jan Luca <jan@toolserver.org>
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported or later
 */


class LabsCentralauth extends SpecialPage {

	const INST_FREE = 'free';
	const INST_IN_USE = 'in_use';

	public function __construct() {
		parent::__construct( 'LabsCentralauth' );
	}

	public function execute( $par ) {
		$this->setHeaders();

		$action = $this->getRequest()->getText( 'action', 'main' );

		# Handle the action
		switch( $action ) {
			case 'review':
				$this->review();
				break;
			case 'review_start':
				$this->review_start();
				break;
			case 'review_end':
				$this->review_end();
				break;
			case 'dev':
				$this->dev();
				break;
			case 'dev_start':
				$this->dev_start();
				break;
			case 'dev_end':
				$this->dev_end();
				break;
			case 'main':
			default:
				$this->main();
		}
	}

	private function main() {
		$this->getOutput()->addWikiMsg( 'labscentralauth-what' );
		$this->getOutput()->addHtml( Xml::openElement( 'ul' ) );
		$this->getOutput()->addHtml( Xml::openElement( 'li' ) );
		$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-main-review' )->escaped(),
			array(), array( 'action' => 'review' ) ) );
		$this->getOutput()->addHtml( Xml::closeElement( 'li' ) );
		$this->getOutput()->addHtml( Xml::openElement( 'li' ) );
		$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-main-dev' )->escaped(),
			array(), array( 'action' => 'dev' ) ) );
		$this->getOutput()->addHtml( Xml::closeElement( 'li' ) );
		$this->getOutput()->addHtml( Xml::closeElement( 'ul' ) );
	}

	private function review() {
		$fields = array();
		$fields['labscentralauth-review-id'] = Xml::input( 'change_id' );

		$select = new XmlSelect( 'time', 'time', 15 );
		$select->addOption( $this->msg( 'labscentralauth-review-15-minutes' )->escaped(), 15 );
		$select->addOption( $this->msg( 'labscentralauth-review-30-minutes' )->escaped(), 30 );
		$select->addOption( $this->msg( 'labscentralauth-review-1-hour' )->escaped(), 1 );
		$select->addOption( $this->msg( 'labscentralauth-review-2-hours' )->escaped(), 2 );
		$select->addOption( $this->msg( 'labscentralauth-review-3-hours' )->escaped(), 3 );
		$select->addOption( $this->msg( 'labscentralauth-review-6-hours' )->escaped(), 6 );
		$fields['labscentralauth-review-block-for'] = $select->getHTML();

		$this->getOutput()->addHtml( Xml::openElement( 'form', array( 'action' =>
			$this->getTitle()->getFullURL( array( 'action' => 'review_start' ) ), 'method' => 'post' ) ) );
		$this->getOutput()->addHtml( Xml::buildForm( $fields, 'labscentralauth-submit' ) );
		$this->getOutput()->addHtml( Html::hidden( 'type', 'review' ) );
		$this->getOutput()->addHtml( Xml::closeElement( 'form' ) );

		$this->getOutput()->addWikiText( '== '. $this->msg( 'labscentralauth-status' )->text() .' ==' );
		$this->getOutput()->addHtml( Xml::openElement( 'table', array( 'class' => 'wikitable' ) ) );
		$this->getOutput()->addHtml( Xml::openElement( 'thead' ) );
		$this->getOutput()->addHtml( Xml::openElement( 'tr' ) );
		$this->getOutput()->addHtml(
			Xml::openElement( 'th' ) . $this->msg( 'labscentralauth-wiki' )->escaped() . Xml::closeElement( 'th' ) .
			Xml::openElement( 'th' ) . $this->msg( 'labscentralauth-status' )->escaped() . Xml::closeElement( 'th' ) .
			Xml::openElement( 'th' ) . $this->msg( 'labscentralauth-user' )->escaped() . Xml::closeElement( 'th' ) .
			Xml::openElement( 'th' ) . $this->msg( 'labscentralauth-review-blocked-till' )->escaped() . Xml::closeElement( 'th' ) .
			Xml::openElement( 'th' ) . $this->msg( 'labscentralauth-actions' )->escaped() . Xml::closeElement( 'th' )
		);
		$this->getOutput()->addHtml( Xml::closeElement( 'tr' ) );
		$this->getOutput()->addHtml( Xml::closeElement( 'thead' ) );
		$this->getOutput()->addHtml( Xml::openElement( 'tbody' ) );

		$data = $this->select( 'review' );
		if ( count( $data ) ) {
			foreach( $data as $instance ) {
				$status = ($instance['status'] == self::INST_FREE)? $this->msg( 'labscentralauth-free' )->escaped() :
					$this->msg( 'labscentralauth-inuse' )->escaped();

				$user = ($instance['userid'] == null || $instance['userid'] == 'NULL')? '' :
					User::newFromId( $instance['userid'] );

				if ( $instance['time'] == null || $instance['time'] == 'NULL' ) {
					$time = '';
				} else {
					$userTz = $this->getUserTimezone( $instance['time'] );
					$time = ($instance['time'] == null || $instance['time'] == 'NULL')? '' :
						$this->getLang()->userTimeAndDate( $instance['time'], $this->getUser() ) .
						$this->msg( 'word-separator' )->text() . $this->msg( 'parentheses', $userTz )->text();
				}

				$actions = '';
				$username = '';
				if ( $user instanceof User ) {
					if ( $this->getUser()->getId() == $user->getId() ) {
						$actions .= Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-review-end' )->escaped(),
							array(), array( 'action' => 'review_end', 'instance' => $instance['name'] ) );
					} elseif ( $this->getUser()->isAllowed( 'labscentralauth-admin' ) ) {
						$actions .= Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-reset' )->escaped(),
							array(), array( 'action' => 'review_end', 'instance' => $instance['name'] ) );
					}

					$username = $user->getName();
				} elseif ( $time != '' ) {
					if ( $this->getUser()->isAllowed( 'labscentralauth-admin' ) ) {
						$actions .= Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-review-reset' )->escaped(),
							array(), array( 'action' => 'review_end', 'instance' => $instance['name'] ) );
					}
				}

				if ( $username == '' ) {
					$username = Xml::element( 'span', array( 'style' => 'font-style:italic;' ),
						$this->msg( 'labscentralauth-not-blocked' )->text() );
				} else {
					$username = htmlspecialchars( $username );
				}

				if ( $time == '' ) {
					$time = Xml::element( 'span', array( 'style' => 'font-style:italic;' ),
						$this->msg( 'labscentralauth-not-blocked' )->text() );
				} else {
					$time = htmlspecialchars( $time );
				}

				$this->getOutput()->addHtml( Xml::openElement( 'tr' ) );
				$this->getOutput()->addHtml(
					Xml::openElement( 'td' ) . htmlspecialchars( $instance['name'] ) . Xml::closeElement( 'td' ) .
					Xml::openElement( 'td' ) . $status . Xml::closeElement( 'td' ) .
					Xml::openElement( 'td' ) . $username . Xml::closeElement( 'td' ) .
					Xml::openElement( 'td' ) . $time . Xml::closeElement( 'td' ) .
					Xml::openElement( 'td' ) . $actions . Xml::closeElement( 'td' )
				);
				$this->getOutput()->addHtml( Xml::closeElement( 'tr' ) );
			}
		}

		$this->getOutput()->addHtml( Xml::closeElement( 'tbody' ) );
		$this->getOutput()->addHtml( Xml::closeElement( 'table' ) );
	}

	private function review_start() {
		if ( !$this->getUser()->isAllowed( 'labscentralauth-use-review' ) &&
			!$this->getUser()->isAllowed( 'labscentralauth-admin' ) ) {
			throw new PermissionsError( 'labscentralauth-use-review' );
		}

		global $wgLabsCentralauthGerrit, $wgLabsCentralauthGerritCentralAuthUrl;
		global $wgLabsCentralauthGerritProject;
		global $wgLabsCentralauthKey, $wgLabsCentralauthUser;

		$changeIdReq = $this->getRequest()->getText( 'change_id', null );
		$timeReq = $this->getRequest()->getInt( 'time', null );

		switch( $timeReq ) {
			case 1:
				$runtime = 1 * 60 * 60;
				break;
			case 2:
				$runtime = 2 * 60 * 60;
				break;
			case 3:
				$runtime = 3 * 60 * 60;
				break;
			case 6:
				$runtime = 6 * 60 * 60;
				break;
			case 30:
				$runtime = 30 * 60;
				break;
			case 15:
			default:
				$runtime = 15 * 60;
		}

		$data = $this->select( 'review' );
		foreach( $data as $instance ) {
			if ( $instance['status'] == self::INST_FREE ) {
				$out = array();
				$sshQuery = __DIR__.'/scripts/gerrit-query.sh '. $wgLabsCentralauthKey .' '. $wgLabsCentralauthUser .'@'.
					$wgLabsCentralauthGerrit .' '. $changeIdReq;
				$return = $this->exec( $instance['systempath'], $sshQuery, __METHOD__, $out );
				if ( !$return->isOK() ) {
					return false;
				} elseif ( !$return->isGood() ) {
					wfDebug( __METHOD__ . ', Line '. __LINE__ .': ' . $return->getValue() );
				}

				$reviewBranch = '';
				foreach( $out as $json ) {
					$query = json_decode( $json, true );

					if ( !isset( $query['currentPatchSet'] ) ) continue;

					if ( $query['project'] != $wgLabsCentralauthGerritProject ) {
						$this->getOutput()->addWikiMsg( 'labscentralauth-review-start-project-wrong', $changeIdReq,
							$wgLabsCentralauthGerritProject );
						$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
							array(), array( 'action' => 'review' ) ) );
						return false;
					}

					if ( !$query['open'] ) {
						$this->getOutput()->addWikiMsg( 'labscentralauth-review-start-not-open', $changeIdReq );
						$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
							array(), array( 'action' => 'review' ) ) );
						return false;
					}

					$reviewBranch = $query['currentPatchSet']['ref'];

					break;
				}

				if ( $reviewBranch == '' ) {
					$this->getOutput()->addWikiMsg( 'labscentralauth-review-start-id-invalid', $changeIdReq );
					$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
						array(), array( 'action' => 'review' ) ) );
					return false;
				}

				$out = array();
				$gitFetch = __DIR__.'/scripts/git-fetch.sh '. $instance['systempath'] .' '.
					$wgLabsCentralauthGerritCentralAuthUrl . ' ' . $reviewBranch;
				$this->exec( $instance['systempath'], $gitFetch, __METHOD__, $out );
				if ( !$return->isOK() ) {
					return false;
				} elseif ( !$return->isGood() ) {
					wfDebug( __METHOD__ . ', Line '. __LINE__ .': ' . $return->getValue() );
				}

				$time = wfTimestamp() + $runtime;

				$this->update( 'review', $instance['name'], self::INST_IN_USE, $this->getUser()->getId(), $time );

				$this->getOutput()->addWikiMsg( 'labscentralauth-review-start-successful', $instance['name'], $instance['url'] );
				$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
						array(), array( 'action' => 'review' ) ) );
				return true;
			}
		}

		$this->getOutput()->addWikiMsg( 'labscentralauth-review-start-no-free' );
		$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
			array(), array( 'action' => 'review' ) ) );
		$this->mail( 'No free review instance!', "Method: " . __METHOD__ . "\nTime: " . wfTimestamp(TS_DB) . " (UTC)\nUser: " .
			$this->getUser()->getName() );
		return false;
	}

	private function review_end() {
		if ( !$this->getUser()->isAllowed( 'labscentralauth-use-review' ) &&
			!$this->getUser()->isAllowed( 'labscentralauth-admin' ) ) {
			throw new PermissionsError( 'labscentralauth-use-review' );
		}

		$instanceReq = $this->getRequest()->getText( 'instance', null );
		$data = $this->select( 'review', $instanceReq );
		foreach( $data as $instance ) {
			if ( $instance['name'] == $instanceReq ) {
				if ( $instance['status'] == self::INST_FREE ) {
					$this->getOutput()->addWikiMsg( 'labscentralauth-review-end-not-in-use', $instanceReq );
					$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
						array(), array( 'action' => 'review' ) ) );
					return false;
				}

				if ( $instance['userid'] != $this->getUser()->getId() ) {
					if ( !$this->getUser()->isAllowed( 'labscentralauth-admin' ) ) {
						throw new PermissionsError( 'labscentralauth-admin' );
					}
				}

				$out = null;
				$gitBackToMaster = __DIR__.'/scripts/git-back-to-master.sh ' . $instance['systempath'];
				$return = $this->exec( $instance['systempath'], $gitBackToMaster, __METHOD__, $out );
				if ( !$return->isOK() ) {
					return false;
				} elseif ( !$return->isGood() ) {
					wfDebug( __METHOD__ . ': ' . $return->getValue() );
				}

				$this->update( 'review', $instance['name'], self::INST_FREE, null, null );

				$this->getOutput()->addWikiMsg( 'labscentralauth-review-end-successful', $instance['name'] );
				$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
					array(), array( 'action' => 'review' ) ) );
				return true;
			}
		}

		$this->getOutput()->addWikiMsg( 'labscentralauth-review-end-instance-unknown', $instanceReq );
		$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
			array(), array( 'action' => 'review' ) ) );
		return false;
	}

	private function dev() {
		$this->getOutput()->addWikiMsg( 'labscentralauth-what' );
		$this->getOutput()->addHtml( Xml::openElement( 'ul' ) );
		$this->getOutput()->addHtml( Xml::openElement( 'li' ) );
		$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-dev-start' )->escaped(),
			array(), array( 'action' => 'dev_start' ) ) );
		$this->getOutput()->addHtml( Xml::closeElement( 'li' ) );
		$this->getOutput()->addHtml( Xml::closeElement( 'ul' ) );

		$this->getOutput()->addWikiText( '== '. $this->msg( 'labscentralauth-status' )->text() .' ==' );
		$this->getOutput()->addHtml( Xml::openElement( 'table', array( 'class' => 'wikitable' ) ) );
		$this->getOutput()->addHtml( Xml::openElement( 'thead' ) );
		$this->getOutput()->addHtml( Xml::openElement( 'tr' ) );
		$this->getOutput()->addHtml(
			Xml::openElement( 'th' ) . $this->msg( 'labscentralauth-wiki' )->escaped() . Xml::closeElement( 'th' ) .
			Xml::openElement( 'th' ) . $this->msg( 'labscentralauth-status' )->escaped() . Xml::closeElement( 'th' ) .
			Xml::openElement( 'th' ) . $this->msg( 'labscentralauth-user' )->escaped() . Xml::closeElement( 'th' ) .
			Xml::openElement( 'th' ) . $this->msg( 'labscentralauth-actions' )->escaped() . Xml::closeElement( 'th' )
		);
		$this->getOutput()->addHtml( Xml::closeElement( 'tr' ) );
		$this->getOutput()->addHtml( Xml::closeElement( 'thead' ) );
		$this->getOutput()->addHtml( Xml::openElement( 'tbody' ) );

		$data = $this->select( 'dev' );
		if ( count( $data ) ) {
			foreach( $data as $instance ) {
				$status = ($instance['status'] == self::INST_FREE)? $this->msg( 'labscentralauth-free' )->escaped() :
					$this->msg( 'labscentralauth-inuse' )->escaped();

				$user = ($instance['userid'] == null || $instance['userid'] == 'NULL')? '' :
					User::newFromId( $instance['userid'] );

				$actions = '';
				$username = '';
				if ( $user instanceof User ) {
					if ( $this->getUser()->getId() == $user->getId() ) {
						$actions .= Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-dev-end' )->escaped(),
							array(), array( 'action' => 'dev_end', 'instance' => $instance['name'] ) );
					} elseif ( $this->getUser()->isAllowed( 'labscentralauth-admin' ) ) {
						$actions .= Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-reset' )->escaped(),
							array(), array( 'action' => 'dev_end', 'instance' => $instance['name'] ) );
					}

					$username = $user->getName();
				}

				if ( $username == '' ) {
					$username = Xml::element( 'span', array( 'style' => 'font-style:italic;' ),
						$this->msg( 'labscentralauth-not-blocked' )->text() );
				} else {
					$username = htmlspecialchars( $username );
				}

				$this->getOutput()->addHtml( Xml::openElement( 'tr' ) );
				$this->getOutput()->addHtml(
					Xml::openElement( 'td' ) . htmlspecialchars( $instance['name'] ) . Xml::closeElement( 'td' ) .
					Xml::openElement( 'td' ) . $status . Xml::closeElement( 'td' ) .
					Xml::openElement( 'td' ) . $username . Xml::closeElement( 'td' ) .
					Xml::openElement( 'td' ) . $actions . Xml::closeElement( 'td' )
				);
				$this->getOutput()->addHtml( Xml::closeElement( 'tr' ) );
			}
		}

		$this->getOutput()->addHtml( Xml::closeElement( 'tbody' ) );
		$this->getOutput()->addHtml( Xml::closeElement( 'table' ) );
	}

	private function dev_start() {
		if ( !$this->getUser()->isAllowed( 'labscentralauth-use-dev' ) &&
			!$this->getUser()->isAllowed( 'labscentralauth-admin' ) ) {
			throw new PermissionsError( 'labscentralauth-use-dev' );
		}

		$data = $this->select( 'dev' );
		foreach( $data as $instance ) {
			if ( $instance['status'] == self::INST_FREE ) {
				$newBranch = strtolower( $this->getUser()->getName() ) . wfTimestamp();

				$out = array();
				$gitCheckout = __DIR__.'/scripts/git-checkout-new-branch.sh '. $instance['systempath'] .' '.
					$newBranch;
				$return = $this->exec( $instance['systempath'], $sshQuery, __METHOD__, $out );
				if ( !$return->isOK() ) {
					return false;
				} elseif ( !$return->isGood() ) {
					wfDebug( __METHOD__ . ', Line '. __LINE__ .': ' . $return->getValue() );
				}

				$this->update( 'dev', $instance['name'], self::INST_IN_USE, $this->getUser()->getId(), 0 );

				$this->getOutput()->addWikiMsg( 'labscentralauth-dev-start-successful', $instance['name'], $instance['url'],
					$instance['systempath'], $newBranch );
				$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
						array(), array( 'action' => 'review' ) ) );
				return true;
			}
		}

		$this->getOutput()->addWikiMsg( 'labscentralauth-dev-start-no-free' );
		$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
			array(), array( 'action' => 'dev' ) ) );
		$this->mail( 'No free dev instance!', "Method: " . __METHOD__ . "\nTime: " . wfTimestamp(TS_DB) . " (UTC)\nUser: " .
			$this->getUser()->getName() );
		return false;
	}

	private function dev_end() {
		if ( !$this->getUser()->isAllowed( 'labscentralauth-use-dev' ) &&
			!$this->getUser()->isAllowed( 'labscentralauth-admin' ) ) {
			throw new PermissionsError( 'labscentralauth-use-dev' );
		}

		$instanceReq = $this->getRequest()->getText( 'instance', null );
		$data = $this->select( 'dev', $instanceReq );
		foreach( $data as $instance ) {
			if ( $instance['name'] == $instanceReq ) {
				if ( $instance['status'] == self::INST_FREE ) {
					$this->getOutput()->addWikiMsg( 'labscentralauth-dev-end-not-in-use', $instanceReq );
					$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
						array(), array( 'action' => 'dev' ) ) );
					return false;
				}

				if ( $instance['userid'] != $this->getUser()->getId() ) {
					if ( !$this->getUser()->isAllowed( 'labscentralauth-admin' ) ) {
						throw new PermissionsError( 'labscentralauth-admin' );
					}
				}

				$out = null;
				$gitBackToMaster = __DIR__.'/scripts/git-back-to-master.sh ' . $instance['systempath'];
				$return = $this->exec( $instance['systempath'], $gitBackToMaster, __METHOD__, $out );
				if ( !$return->isOK() ) {
					return false;
				} elseif ( !$return->isGood() ) {
					wfDebug( __METHOD__ . ': ' . $return->getValue() );
				}

				$this->update( 'dev', $instance['name'], self::INST_FREE, null, null );
				return true;
			}
		}

		$this->getOutput()->addWikiMsg( 'labscentralauth-dev-end-instance-unknown', $instanceReq );
		$this->getOutput()->addHtml( Linker::linkKnown( $this->getTitle(), $this->msg( 'labscentralauth-back' )->escaped(),
			array(), array( 'action' => 'dev' ) ) );
		return false;
	}

	private function select( $type = 'review', $instance = null ) {
		$dbr = wfGetDB( DB_SLAVE );

		$tables = array( 'labscentralauth' );
		$fields = array( 'lacwiki_instance', 'lacstatus', 'lacuserid', 'lactime', 'lacurl', 'lacsystempath' );
		$where = array( 'lactype' => $type );

		if ( $instance !== null ) {
			$where['lacwiki_instance'] = $instance;
		}

		$result = $dbr->select( $tables, $fields, $where, __METHOD__ );

		$return = array();
		foreach( $result as $row ) {
			$return[$row->lacwiki_instance] = array( 'name' => $row->lacwiki_instance );
			$return[$row->lacwiki_instance]['status'] = $row->lacstatus;
			$return[$row->lacwiki_instance]['userid'] = $row->lacuserid;
			$return[$row->lacwiki_instance]['time'] = $row->lactime;
			$return[$row->lacwiki_instance]['url'] = $row->lacurl;
			$return[$row->lacwiki_instance]['systempath'] = $row->lacsystempath;
		}

		return $return;
	}

	private function update( $type, $instance, $status, $userid = null, $time = null, $url = null, $systempath = null ) {
		$dbw = wfGetDB( DB_MASTER );

		$set = array( 'lacstatus' => $status, 'lacuserid' => $userid, 'lactime' => $time );
		$where = array( 'lactype' => $type, 'lacwiki_instance' => $instance );

		if ( $url !== null ) {
			$set['lacurl'] = $url;
		}

		if ( $systempath !== null ) {
			$set['lacsystempath'] = $systempath;
		}

		$result = $dbw->update( 'labscentralauth', $set, $where, __METHOD__ );
	}

	/**
	 * @param $timestamp int|string Unix timestamp
	 */
	private function getUserTimezone( $ts ) {
		$tz = $this->getUser()->getOption( 'timecorrection' );

		$data = explode( '|', $tz, 3 );

		$return = false;

		if ( $data[0] == 'ZoneInfo' ) {
			wfSuppressWarnings();
			$userTZ = timezone_open( $data[2] );
			wfRestoreWarnings();
			if ( $userTZ !== false ) {
				$date = date_create( '@'.$ts, timezone_open( 'UTC' ) );
				date_timezone_set( $date, $userTZ );
				$return = date_format( $date, 'T' );
			} else {
				$data[0] = 'Offset';
			}
		}

		$offset = 0;
		if ( $data[0] == 'System' || $tz == '' ) {
			# A Global offset in minutes.
			if ( isset( $wgLocaltimezone ) ) {
				$return = $wgLocaltimezone;
			} else {
				$return = date_default_timezone_get();
			}
		} elseif ( $data[0] == 'Offset' ) {
			$offset = intval( $data[1] );
		} else {
			$data = explode( ':', $tz );
			if ( count( $data ) == 2 ) {
				$data[0] = intval( $data[0] );
				$data[1] = intval( $data[1] );
				$offset = abs( $data[0] ) * 60 + $data[1];
				if ( $data[0] < 0 ) {
					$offset = -$offset;
				}
			} else {
				$offset = intval( $data[0] ) * 60;
			}
		}

		if ( $offset !== 0 ) {
			if ( $offset < 0 ) {
				$prefix = '-';
			} else {
				$prefix = '+';
			}

			$offset = abs( $offset );
			$hours = intval( floor( $offset / 60 ) );
			$hours = str_pad( $hours, 2, '0', STR_PAD_LEFT );
			$minutes = intval( round( ( ceil( $offset / 60 ) - $hours ) * 60 ) );
			$minutes = str_pad( $minutes, 2, '0', STR_PAD_LEFT );

			$return = $this->msg( 'timezone-utc' )->text() .
				$prefix . $hours . ':' . $minutes;
		} elseif ( $offset === 0 ) {
			$return = 'UTC';
		}

		$key = 'timezone-' . strtolower( trim( $return ) );
		$msg = wfMessage( $key );
		if ( $msg->exists() ) {
			$return = $msg->text();
		}

		return $return;
	}

	private function exec( $cwd, $cmd, $caller, &$output, $input = null ) {
		$descriptorspec = array(
			0 => array("pipe", "r"),
			1 => array("pipe", "w"),
			2 => array("pipe", "w")
		);

		$process = proc_open( $cmd, $descriptorspec, $pipes, $cwd );

		$error = '';
		if ( is_resource( $process ) ) {
			if ( $input !== null ) {
				fwrite( $pipes[0], $input );
			}
			fclose( $pipes[0] );

			if ( $output !== null ) {
				if ( is_array( $output ) ) {
					while( ( $buffer = fgets( $pipes[1] ) ) !== false ) {
						$output[] = trim( $buffer );
					}
				} else {
					$output = stream_get_contents( $pipes[1] );
				}
			}
			fclose( $pipes[1] );

			$error = stream_get_contents( $pipes[2] );
			fclose( $pipes[2] );
		} else {
			$this->getOutput()->showErrorPage( $this->msg( 'labscentralauth-error-page-title-fatal', __METHOD__ ),
				$this->msg( 'labscentralauth-exec-proc-open', $cmd, $cwd, $caller ) );
			return Status::newFatal( 'labscentralauth-exec-proc-open', $cmd, $cwd, $caller );
		}

		$return_value = proc_close($process);

		if ( $error != '' ) {
			$return = Status::newGood( $this->msg( 'labscentralauth-exec-stderr', $error )->inContentLanguage()->escaped() );
			$return->error( 'labscentralauth-exec-stderr', $error );
			return $return;
		} else {
			return Status::newGood();
		}
	}

	private function mail( $subject, $text = null, $to = 'roots' ) {
		global $wgLabsCentralauthRoots;

		if ( $text === null ) {
			$text = $subject;
		}
		if ( $to == 'roots' ) {
			foreach( $wgLabsCentralauthRoots as $root ) {
				$user = User::newFromName( $root );
				$user->sendMail( $subject, $text );
			}
		} else {
			$user = User::newFromName( $to );
			$user->sendMail( $subject, $text );
		}
	}
}
