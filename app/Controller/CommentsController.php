<?php
class CommentsController extends AppController {
	
	var $uses = array('Comment', 'Adventure', 'Text', 'EmailText');
	var $components = array('Like');
	
	public function add() {
		
		if ($this->request->isPost() && !empty($this->data['Comment']['value'])) {
			$comment = $this->data; 
			$comment['Comment']['date_created'] = date(Configure::read('DB_DATE_FORMAT'));			
			String::prettyPrintToHtml($comment['Comment']['value']);
						
			$this->Comment->save($comment);
			
			//send Magda update about comment.						
			$advId = $comment['Comment']['adventure_id'];
			$tripId = $this->Adventure->field('trip_id', array('id' => $advId));
			$comments = $this->Adventure->find('first', array(
				'fields' => array('Adventure.id', 'Adventure.title'),
				'conditions' => array('Adventure.id' => $advId),		
				'contain' => array('Comment.value') 					
			));
			$advTitle = $comments['Adventure']['title'];
			$comments = Set::extract('/value', $comments['Comment']);			
			$comments = implode('</li><li>', $comments);
			$comments = '<ul><li>'.$comments.'</li></ul>';
			
			
			$emailFields['advId'] = $advId;			
			$emailFields['advTitle'] = $advTitle;
			
			$toComment = Utility::toAdventure($advId);
			$emailFields['pageLink'] = '<a href="'.$this->constructSiteLink('trips', 'index/'.$tripId.$toComment).'">'.Configure::read('APP_NAME').'</a>';			
			$emailFields['comments'] = $comments;

			list($body, $this->Email->subject) = $this->EmailText->constructEmail('comment_notify', $emailFields);
			
			$this->Email->to = Configure::read('HTTP_HOST') != 'localhost' ? 
				array(Configure::read('DEVELOPER_EMAIL'), Configure::read('MAGDA_MALAK_EMAIL')) : 
				array(Configure::read('DEVELOPER_EMAIL'));
			$this->Email->bcc = false; //do not send bcc to Magda			
			$this->Email->sendAs = 'html';
			$this->Email->from = Configure::read('CONTACT_EMAIL');
			 			
			$this->Email->send($body);				
		}
		$this->redirect(array(
			'controller' => 'trips',
			'action' => 'index/'.$tripId.$toComment
		));
	}
}

