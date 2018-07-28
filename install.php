<?php

if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

$freepbx_conf = freepbx_conf::create();
// Play 'beep' while recording
$set['value'] = '';
$set['defaultval'] =& $set['value'];
$set['options'] = array(0,300);
$set['readonly'] = 0;
$set['hidden'] = 0;
$set['level'] = 1;
$set['module'] = 'callrecording';
$set['category'] = 'Call Recording';
$set['emptyok'] = 1;
$set['sortorder'] = 10;
$set['name'] = "Beep every n seconds";
$set['description'] = "Asterisk 13.2 and higher supports the ability to play a regular 'beep' when a call is being recorded. If you set this to a positive number value, when a call is being actively recorded, both parties will hear a 'beep' every period that you select. If you are not running Asterisk 13.2 or higher, this setting will have no effect. To disable simply clear the value of this box or set the value to 0 and save. This is typically set arround 15seconds";
$set['type'] = CONF_TYPE_INT;
$freepbx_conf->define_conf_setting('CALLREC_BEEP_PERIOD',$set);

$fcc = new featurecode('callrecording', 'pauserecording');
$fcc->setDescription('In-Call Asterisk Toggle Call Recording Pause');
$fcc->setDefault('*3');
$fcc->update();
unset($fcc);
