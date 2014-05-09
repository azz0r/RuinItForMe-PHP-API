<?php


class System_Notification {


    public $template;


    public function __construct($options=array()) {

        switch($options['template']) {
            case 'hive-created':
            default:
            $this->load(_EMAIL."spoiler-created.phtml");

            $this->title = $options['title'];
            $this->email = $options['email'];

            $this->replace('name',      $options['email']);
            $this->send();
            break;
        }
        return;
    }


    public function load($filepath) {
        $this->template = file_get_contents($filepath);
    }


    public function replace($var, $content) {
        $this->template = str_replace("#$var#", $content, $this->template);
    }


    public function send() {
        if (_ENV == 'dev') {
            $this->email = 'aaron.lote@gmail.com';
        }
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $headers .= 'From: Hive Tracking <www-data@hivetracking.com>' . "\r\n";


        $headers   = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-type: text/html; charset=iso-8859-1";
        $headers[] = "From: Hive Tracking <www-data@hivetracking.com>";
        $headers[] = "Reply-To: Hive Tracking <noreply@hivetracking.com>";
        $headers[] = "Subject: {$this->title}";
        $headers[] = "X-Mailer: PHP/".phpversion();


        mail($this->email, $this->title, $this->template, implode("\r\n", $headers));
        return $this;
    }


}