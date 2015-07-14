<?php	
		
	Final Class datasourcereCAPTCHA2 Extends DataSource{			

		function about(){
			return array(
					 'name' => 'reCAPTCHA2: Public Key',
					 'author' => array(
							'name' => 'Reallyalexpro',
							'website' => 'http://reallyalexpro.ru',
							'email' => 'reallyalexpro@yandex.ru'),
					 'version' => '1.0',
					 'release-date' => '2015-07-14');	
		}

		public function grab(){
			include_once(EXTENSIONS . '/recaptcha2/extension.driver.php');
			$driver = $this->_Parent->ExtensionManager->create('recaptcha2');
			return new XMLElement('recaptcha2', $driver->getPublicKey());
		}		
		
	}


