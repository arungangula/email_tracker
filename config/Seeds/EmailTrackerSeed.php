<?php
use Migrations\AbstractSeed;
use Cake\Cache\Cache;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * EmailTracker seed.
 */
class EmailTrackerSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $table = $this->table('email_tracker');
        $hostname = Configure::read('email_tracker.hostname');
        $username = Configure::read('email_tracker.username');
        $password = Configure::read('email_tracker.password');
        $cache_key = Configure::read('email_tracker.cache_key');
        $start_date = Configure::read('email_tracker.start_date');


        $subject_pattern = '/\b((BTID\s?)?[1-9][0-9][0-9][0-9])\b/';
        $sender_info_pattern = '/(.*)<(.*)>/';

        $last_email_date_from_cache = Cache::read($cache_key);
        $last_email_date = $last_email_date_from_cache ? $last_email_date_from_cache : $start_date;

        $imap = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
        $emails = imap_search($imap,'BCC "'.$username.'" SINCE "'.$last_email_date.'"');

        if($emails) {
            rsort($emails);
            foreach ($emails as $email_number) {
                $overview = imap_fetch_overview($imap, $email_number, 0);
                Cache::write($cache_key, date('d-M-Y', strtotime('+1 day', $overview[0]->udate)));
                if(preg_match($subject_pattern, $overview[0]->subject, $matches) == 1) {

                    $sender_name = '';
                    $sender_email = $overview[0]->from;
                    if(preg_match($sender_info_pattern, $sender_email, $sender_matches) == 1) {
                        $sender_name = trim($sender_matches[1]);
                        $sender_email = trim($sender_matches[2]);
                    }

                    $data[] = [
                        'client_id' => $matches[0],
                        'subject' => $overview[0]->subject,
                        'sender_name' => $sender_name,
                        'sender_email' => $sender_email,
                        'time_sent' => date('Y-m-d H:i:s', $overview[0]->udate)
                    ];
                }
            }
        }


        if(!empty($data)) {
            $table->insert($data)->save();
            echo "data saved";
        }
        else {
            echo "nothing to save";
        }
    }
}
