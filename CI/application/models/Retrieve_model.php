<?php
/**
 * Created by PhpStorm.
 * User: E_Bwill
 * Date: 16/3/24
 * Time: 下午8:31
 */
class  Retrieve_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function make_code($email)
    {
        $query = $this->db->query("select email from users where email='".$email."';");
        $row_check = $query->first_row('array');
        if($row_check['email'])
        {
            return self::set_mem($email,self::random());
        }
        else{
            return "3";
        }
    }

    public function random($len=6,$format='NUMBER'){
        $is_abc = $is_numer = 0;
        $password = $tmp ='';
        switch($format){
            case 'ALL':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 'CHAR':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'NUMBER':
                $chars='0123456789';
                break;
            default :
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
        }
        mt_srand((double)microtime()*1000000*getmypid());
        while(strlen($password)<$len){
            $tmp =substr($chars,(mt_rand()%strlen($chars)),1);
            if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == 'CHAR'){
                $is_numer = 1;
            }
            if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
                $is_abc = 1;
            }
            $password.= $tmp;
        }
        if($is_numer <> 1 || $is_abc <> 1 || empty($password) ){
            $password = randpw($len,$format);
        }
        return $password;
    }

    public function set_mem($email,$random)
    {
        $mem = new Memcache;
        $mem->connect("127.0.0.1", 11211);

        if($mem->get($email))
            $mem->replace($email,$random,0,1800);
        else
            $mem->set($email,$random,0,1800);
        if($mem->get($email)) {
            self::sendmail($email,$random);
            return 1;
        }
        else
            return 9;
    }

    public function sendmail($email,$random)
    {
        file_get_contents("http://121.42.45.74/mail/send_email.php?email=".$email."&code=".$random);
    }

    public function validate($email,$code)
    {
        $mem = new Memcache;
        $mem->connect("127.0.0.1", 11211);
        $value = $mem->get($email);
        if(isset($value) && $value == $code)
            return 1;
        else
            return 0;

    }
}
