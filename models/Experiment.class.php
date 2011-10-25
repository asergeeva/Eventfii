<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
class Experiment {
	public static $EXPERIMENT_CREATE_EVENT = array(
		"exp_create_event_1",
		"exp_create_event_2",
		"exp_create_event_3",
	);
	
	/**
	 * This has to be in the form of:
	 *		feature_experiment_name => experiment_variance
	 */
	public static $EXPERIMENTS = array (
		'login_fb_only' => 2
	);
	
	public function __construct() {
		
	}
	
	public function __destruct() {
	
	}
}