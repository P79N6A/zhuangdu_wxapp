<?php
defined('IN_IA') or exit('Access Denied');

$tag = random(32);
global $_GPC;
include $this->template('activity/param');
