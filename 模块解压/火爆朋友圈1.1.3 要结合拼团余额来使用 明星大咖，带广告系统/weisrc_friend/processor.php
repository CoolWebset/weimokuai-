<?php
/**
 * 火爆朋友圈
 *
 * 作者:折翼天使资源社区
 *
 * qq : 3213288469
 */
defined('IN_IA') or exit('Access Denied');
include "model.php";

class weisrc_friendModuleProcessor extends WeModuleProcessor {

    public function respond() {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('weisrc_friend_reply') . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));
        $from_user = $this->message['from'];

        if ($row == false) {
            return $this->respText("活动已取消...");
        }

        if ($row['status'] == 0) {
            return $this->respText("活动暂停，请稍后...");
        }

        if ($row['starttime'] > time()) {
            return $this->respText("活动未开始，请等待...");
        }

        $endtime = $row['endtime'] + 68399;
        if ( $endtime < time()) {
            return $this->respNews(array(
                        'Title' => $row['end_theme'],
                        'Description' => $row['end_instruction'],
                        'PicUrl' => img_url($row['end_picurl']),
                        'Url' => $this->createMobileUrl('index', array('id' => $rid)),
            ));
        } else {
            return $this->respNews(array(
                        'Title' => $row['title'],
                        'Description' => $row['description'],
                        'PicUrl' => img_url($row['start_picurl']),
                        'Url' => $this->createMobileUrl('index', array('id' => $rid)),
            ));
        }
    }

}
