<?php

	Class extension_recaptcha2 extends Extension {

		public function about(){
			return array('name' => 'reCAPTCHA2',
						 'version' => '1.0',
						 'release-date' => '2015-07-14',
						 'author' => array(	'name' => 'Reallyalexpro',
											'website' => 'http://reallyalexpro.ru',
											'email' => 'reallyalexpro@yandex.ru'),
						 'description' => 'Event for reCAPTCHA v.2.0.'
				 		);
		}
		
		public function getSubscribedDelegates(){
			return array(
						array(
							'page' => '/blueprints/events/new/',
							'delegate' => 'AppendEventFilter',
							'callback' => 'addFilterToEventEditor'
						),
						
						array(
							'page' => '/blueprints/events/edit/',
							'delegate' => 'AppendEventFilter',
							'callback' => 'addFilterToEventEditor'
						),
						
						array(
							'page' => '/blueprints/events/',
							'delegate' => 'AppendEventFilterDocumentation',
							'callback' => 'addFilterDocumentationToEvent'
						),
																	
						array(
							'page' => '/system/preferences/',
							'delegate' => 'AddCustomPreferenceFieldsets',
							'callback' => 'appendPreferences'
						),
						
						array(
							'page' => '/frontend/',
							'delegate' => 'EventPreSaveFilter',
							'callback' => 'processEventData'
						),					
			);
		}
		
		public function addFilterToEventEditor($context){
			$context['options'][] = array('recaptcha2', @in_array('recaptcha2', $context['selected']) ,'reCAPTCHA v.2.0 Verification');		
		}
		
		public function appendPreferences($context){
			$group = new XMLElement('fieldset');
			$group->setAttribute('class', 'settings');
			$group->appendChild(new XMLElement('legend', 'reCAPTCHA v.2.0 Verification'));

			$div = new XMLElement('div', NULL, array('class' => 'group'));
			$label = Widget::Label('Public Key');
			$label->appendChild(Widget::Input('settings[recaptcha2][public-key]', General::Sanitize(Symphony::Configuration()->get('public-key', 'recaptcha2'))));		
			$div->appendChild($label);

			$label = Widget::Label('Private Key');
			$label->appendChild(Widget::Input('settings[recaptcha2][private-key]', General::Sanitize(Symphony::Configuration()->get('private-key', 'recaptcha2'))));		
			$div->appendChild($label);
			
			$group->appendChild($div);
			
			$group->appendChild(new XMLElement('p', 'Get a reCAPTCHA v.2.0 API public/private key pair from the <a href="https://www.google.com/recaptcha/">reCAPTCHA site</a>.', array('class' => 'help')));
			
			$context['wrapper']->appendChild($group);
						
		}
		
		public function addFilterDocumentationToEvent(array $context){								
			if(in_array('recaptcha2', $context['selected'])) {			
				$context['documentation'][] = new XMLElement('h3', 'reCAPTCHA v.2.0 Verification');
				$context['documentation'][] = new XMLElement('p', __('Add following code to form.'));
				$context['documentation'][] = contentAjaxEventDocumentation::processDocumentationCode('<div class="g-recaptcha" data-sitekey="your-public-key"></div>');
				
				
				
				$context['documentation'][] = new XMLElement('p', 'Each entry will be passed to the <a href="https://www.google.com/recaptcha/">reCAPTCHA v2.0 filtering service</a>. <strong>Note: Be sure to set your reCAPTCHA public and private API keys in the <a href="'.URL.'/symphony/system/preferences/">Symphony Preferences</a>.</strong>');
				
				$context['documentation'][] = new XMLElement('p', 'The following is an example of the XML returned form this filter:');
				
				$code = '<filter type="recaptcha2" status="passed" />
<filter type="recaptcha2" status="failed">Wrong captcha.</filter>';
					
				$context['documentation'][] = contentAjaxEventDocumentation::processDocumentationCode($code);
			}
		}
		
		public function processEventData($context){
			if(!in_array('recaptcha2', $context['event']->eParamFILTERS)) return;
		
			include_once(EXTENSIONS . '/recaptcha2/lib/autoload.php');
			
			$recaptcha = new \ReCaptcha\ReCaptcha($this->getPrivateKey());
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);			
			$context['messages'][] = array('recaptcha2', $resp->isSuccess(), (!$resp->isSuccess() ? 'Wrong captcha.' : NULL));

		}
		
		public function uninstall(){
			Symphony::Configuration()->remove('recaptcha2');
			Symphony::Configuration()->write();
		}

		public function getPublicKey(){						
			return Symphony::Configuration()->get('public-key', 'recaptcha2');
		}	
		
		public function getPrivateKey(){			
			return Symphony::Configuration()->get('private-key', 'recaptcha2');
		}			
		
	}

?>