<?php
/**
 * 开门大吉模块处理程序
 *
 * @author junsion
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class junsion_opendoorModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		$rid = $this->rule;
		$rule = pdo_fetch('select * from '.tablename($this->modulename.'_rule')." where rid='{$rid}'");
		if (!empty($rule)){
			return $this->respNews(array(array('title'=>$rule['title'],'description'=>$rule['description'],'picurl'=>toimage($rule['thumb']),'url'=>$this->createMobileUrl('index',array('rid'=>$rid)))));
		}
	}
}