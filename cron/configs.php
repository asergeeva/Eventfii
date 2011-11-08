<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */

define('ENV', 'qa.truersvp.com');

require_once(realpath(dirname(__FILE__)).'/../domains/'.ENV.'/html/configs.php');
require_once(realpath(dirname(__FILE__)).'/../domains/'.ENV.'/html/models/EFCommon.class.php');
require_once(realpath(dirname(__FILE__)).'/../domains/'.ENV.'/html/libs/Mailgun/Mailgun.php');
require_once(realpath(dirname(__FILE__)).'/../domains/'.ENV.'/html/db/DBConfig.class.php');
require_once(realpath(dirname(__FILE__)).'/../domains/'.ENV.'/html/models/EFMail.class.php');
require_once(realpath(dirname(__FILE__)).'/../domains/'.ENV.'/html/models/EFSMS.class.php');
require_once(realpath(dirname(__FILE__)).'/../domains/'.ENV.'/html/models/Event.class.php');