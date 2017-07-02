<?php

namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;
use Cake\Core\Configure;

class EmailTrackerController extends AppController 
{
	private $emails_table;
	public function beforeFilter(Event $event) {
		$this->emails_table = TableRegistry::get('email_tracker');
	}

	public function afterFilter(Event $event) {
		TableRegistry::clear();
	}

	public function index() {
		$request_input = [];
		$conditions = [];
		$emails = [];
		if($this->request->is('post')) {
			$conditions[] = $this->getConditionsForHomeData();
		}

		if(empty($conditions)) {
			$emails = $this->emails_table->find();
		}
		else {
			$emails = $this->emails_table->find()
			->where($conditions);
		}
		$data = [
			'emails' => $emails,
			'sender_emails' => $this->emails_table->find('list',['fields' => 'sender_email', 'groupField' => 'sender_email'])
		];
		$this->set($data);
	}

	public function dashboard() {
		$request_input = [];
		$data = [];
		if($this->request->is('post')) {
			$request_input = $this->request->getData();
		}

		$emails = $this->emails_table->find()->select('client_id')->group('client_id');
		foreach ($emails as $email) {
			if(empty($request_input)) {
				$data['clients'][$email->client_id] = $this->emails_table->find()
				->where(['client_id'=>$email->client_id])
				->count();
			}
			else {
				$data['clients'][$email->client_id] = $this->emails_table->find()
				->where(['client_id'=>$email->client_id])
				->where(['time_sent >=' => $request_input['start-date']])
				->where(['time_sent <=' => $request_input['end-date']])
				->count();
			}
		}
		$this->set($data);
	}

	public function help() {
	}

	public function truncate() {
		$this->emails_table->deleteAll(['id >' => 0]);
		Cache::delete(Configure::read('email_tracker.cache_key'));
		echo "truncated email_tracker table";
		die();
	}

	private function getConditionsForHomeData() {
		$conditions = [];
		$request_input = $this->request->getData();

		if($request_input['client-id'] != '')
		$conditions[] = ['client_id'=>$request_input['client-id']];

		if($request_input['date-sent'] != '')
		$conditions[] = ['time_sent >='=>$request_input['date-sent']." 00:00:00", 'time_sent <='=>$request_input['date-sent']." 24:00:00"];

		if($request_input['sender-email'] != '' && $request_input['sender-email'] != 'ALL')
		$conditions[] = ['sender_email'=>$request_input['sender-email']];

		return $conditions;
	}
}