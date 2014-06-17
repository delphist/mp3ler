<?php

Yii::import('ext.MDetect.library.Mobile_Detect');

/**
 * Mobile Detect Extension class file
 * @author Rasim
 */
class MDetect extends CApplicationComponent {

	/**
	 * @var Mobile_Detect
	 */
	private $mDetect;

	/**
	 * Get type of device
	 * @return string
	 */
	public function getGroup() {
		if(is_null($this->mDetect)) {
			$this->mDetect = new Mobile_Detect;
		}

		$deviceType = ($this->mDetect->isMobile() && !$this->mDetect->isTablet()) ? 'touch' : ($this->mDetect->isTablet() ? 'touch'  : 'web');
		
		return $deviceType;
	}

	/**
	 * Magic call
	 * @param string $method
	 * @param array $params
	 * @return mixed
	 */
	public function __call($method, $params) {
		if(is_null($this->mDetect)) {
			$this->mDetect = new Mobile_Detect;
		}
		return call_user_func_array(array($this->mDetect, $method), $params);
	}

}