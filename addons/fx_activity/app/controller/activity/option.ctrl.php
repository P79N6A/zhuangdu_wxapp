<?php
defined('IN_IA') or exit('Access Denied');
$ops = array('option', 'form', 'formitem', 'spec', 'specitem');
$op = $_GPC['op'];
$op = in_array($op, $ops) ? $op : 'option';

if ($op == 'option') {
	$tag = random(32);
	include $this->template('activity/option');
}

if ($op == 'form') {
	$type = $_GPC['type'];
	$form_type = trim($_GPC['form_type']);
	switch ($form_type)
	{
		case '0': $placeholder = '输入单选标题'; break;
		case '1': $placeholder = '输入多选标题'; break;
		case '2': $placeholder = '输入下拉选框标题';break;
		case '3': $placeholder = '输入单行文本标题';break;
		case '4': $placeholder = '输入数字文本标题';break;
		case '5': $placeholder = '输入单图上传标题';break;
		case '6': $placeholder = '输入多图上传标题';break;
		case '7': $placeholder = '输入日期标题';break;
		default: $placeholder = '输入'.$_GPC['title'].'标题';
	}
	$form = array(
		"id" => $type=='sys'?$form_type:random(32),
		"title" => $type=='sys'?$_GPC['title']:'',
		"fieldstype" => $type=='sys'?$form_type:'',
		"displaytype" => $type=='diy'?$form_type:'',
		"placeholder" => $placeholder
	);
	include $this->template('activity/form');
}

if ($op == 'formitem') {
	$form = array(
		"id" => $_GPC['formid']
	);
	$formitem = array(
		"id" => random(32),
		"title" => $_GPC['title'],
		"show" => 1,
		"placeholder" => $_GPC['placeholder']
	);
	include $this->template('activity/form_item');
}

if ($op == 'spec') {
	$spec = array(
		"id" => random(32),
		"title" => $_GPC['title'],
		"placeholder" => $_GPC['placeholder']
	);
	include $this->template('activity/spec');
}

if ($op == 'specitem') {
	$spec = array(
		"id" => $_GPC['specid']
	);
	$specitem = array(
		"id" => random(32),
		"title" => $_GPC['title'],
		"placeholder" => $_GPC['placeholder'],
		"show" => 1
	);
	include $this->template('activity/spec_item');
}
