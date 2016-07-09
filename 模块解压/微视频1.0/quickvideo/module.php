<?php
/**
 * 微问卷
 */

defined('IN_IA') or exit('Access Denied');

class QuickVideoModule extends WeModule {
	public $table_reply = 'quickvideo_reply';
	public $table_tape = 'quickvideo_tape';

	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		if ($rid) {
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
			$tape = pdo_fetch("SELECT * FROM " . tablename($this->table_tape) ." WHERE `weid` = :weid AND `tape_id` = :tape_id", array(':weid'=>$_W['weid'], ':tape_id'=>$reply['tape_id']));
		}
		include $this->template('form');
	}

	//public function fieldsFormValidate($rid = 0) {
		//global $_W, $_GPC;
		//if (isset($_GPC['tape_id'])) {
		//	$tape_id = intval($_GPC['tape_id']);
		//	if (!empty($tape_id)) {
		//		$tape = pdo_fetch("SELECT * FROM ".tablename($this->table_tape)." WHERE `tape_id` = :tape_id", array(':tape_id' => $tape_id));
		//		if (!empty($tape)) {
		//			return;
		//		}
		//	}
		//}
		//return '没有选择合适的视频';
	//}

	public function fieldsFormSubmit($rid) {
		global $_GPC;
		if (isset($_GPC['tape_id'])) {
			$record = array('tape_id' => intval($_GPC['tape_id']), 'rid' => $rid);
			$reply = pdo_fetch("SELECT * FROM " .tablename($this->table_reply) . " WHERE `rid` = :rid", array(':rid' => $rid));
			if ($reply) {
				pdo_update($this->table_reply, $record, array('id' => $reply['id']));	
			} else {
				pdo_insert($this->table_reply, $record);	
			}
		}
	}

	public function ruleDeleted($rid) {
		pdo_delete($this->table_reply, array('rid' => $rid));
	}

	public function settingsDisplay($settings) {
		global $_GPC, $_W;
		if(checksubmit()) {
			$cfg = array(
				'def_video_cover' => $_GPC['def_video_cover'],
				'def_tape_cover' => $_GPC['def_tape_cover'],
			);
			if($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}
		if(!isset($settings['def_video_cover'])) {
			$settings['def_video_cover'] = '';
		}
		if(!isset($settings['def_tape_cover'])) {
			$settings['def_tape_cover'] = '';
		}
	
		include $this->template('setting');
	}

	private function getPicUrl($url) {
		global $_W;
		if (empty($url)) {
			$r = $_W['siteroot'] . "./addons/quickvideo/images/default_cover.jpg";
		} else {
			if(!preg_match('/^(http|https)/', $url)) {  //如果是相对路径
				$r = $_W['attachurl'] .  $url;
			} else {
				$r = $url;
			}   
		}
		return $r;
	}
}
