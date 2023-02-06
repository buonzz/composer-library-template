<?php

namespace Mailtrap;

/**
 *  Mailtrup send email class
 *
 *  Use this section to define what this class is doing, the PHPDocumentator will use this
 *  to automatically generate an API documentation using this information.
 *
 *  @author yourname
 */
class MailtrapEmail
{

   /** @var string|boolean API key for mailtrap*/
   private $api_key = '';

   /**  @var array string who must receive email, string like John Doe <email@site.com> */
   private $sender = [];

   /**  @var array who must get email, string like John Doe <email@site.com> or email@site.com or multiple */
   private $recipients = [];

   /**  @var string Subject of this email */
   private $subject = '';

   /**  @var string Simple text for email,  */
   private $text = '';

   /**  @var string HTML Template for email  */
   private $html = '';


   function __construct($api_key = '')
   {
      if ($api_key !== '') {
         $this->api_key = $api_key;
      }
   }


   /**
    * @param string get string email@test.com or name<email@test.com> 
    *  or 'name<email@test.com>, name<email@test.com>' and parse they into array ['name<email@test.com>'] 
    * @return array output as array ['name<email@test.com>', 'name<email@test.com>']
    */
   private function parseEmailRecipients($string)
   {
      $response = [];

      preg_match_all('/(?:"?([^"@<>,]*)"?\s)?(?:<?([^@\s<,]+@[^@\s]+\.[^@\s>,]+)>?)/im', $string, $matches);
      if ($matches && count($matches[2]) > 0) {
         foreach ($matches[2] as $i => $match) {
            if ($this->validateEmail($match)) {
               array_push($response, ['name' => trim($matches[1][$i]), 'email' => trim($match)]);
            }
         }
      }

      return $response;
   }


   /**
    * Default function to validate if we are matched email
    * @var string Input email
    * @return boolean 
    */
   private function validateEmail($email)
   {
      return !!filter_var($email, FILTER_VALIDATE_EMAIL);
   }

   /**
    * Method to set current actual API key for object
    * (possible add some check about key rules: length or format)
    * @param string acceptable key for 
    * @return boolean
    */
   public function setApiKey($key)
   {
      $this->api_key = $key;
      return true;
   }

   /**
    * Method to get current actual API key for object
    * @return string api key
    */
   public function getApiKey()
   {
      return $this->api_key;
   }

   /**
    * Method to set sender of email
    *
    * @param string|array $param1 string or array of strings name and email like "John Doe <email@test.com>" or "email@test.com" 
    *
    * @return boolean Return true if some of emails was added to array and false if no one email was founded
    */
   public function from($email)
   {
      if (gettype($email) === 'array') {
         if (isset($email['email']) && $this->validateEmail($email['email'])) {
            $add = [];
            $add['name'] = $email['name'] ?: '';
            $add['email'] = $email['email'] ?: '';
            $this->sender = $add;
            return true;
         };
      }
      if (gettype($email) === 'string') {
         $email_add = $this->parseEmailRecipients($email);
         if (count($email_add)) {
            $this->sender = $email_add[0];
            return true;
         }
      }
      return false;
   }

   /**
    * @return string sender array 
    */

   public function getSenders()
   {
      return $this->sender;
   }

   /**
    * Sample method 
    *
    * @param string|array $param1 string or array of strings name and email like "John Doe <email@test.com>" or "email@test.com" 
    *
    * @return boolean Return true if some of emails was added to array and false if no one email was founded
    */
   public function to($to)
   {
      if (gettype($to) === 'array') {
         $add = [];
         foreach ($to as $k => $email) {
            if (gettype($email) === 'array' && $this->validateEmail($email['email'])) {
               array_push($add, ['name' => $email['name'], 'email' => $email['email']]);
            }
            if (gettype($email) === 'string' && $this->validateEmail($email)) {
               array_push($add, ['name' => '', 'email' => $email]);
            }
         }
         if (count($add)) {
            $this->recipients = array_merge($this->recipients, $add);
            return true;
         }
      }
      if (gettype($to) === 'string') {
         $add = $this->parseEmailRecipients($to);
         if (count($add)) {
            $this->recipients = array_merge($this->recipients, $add);
            return true;
         }
      }
      return false;
   }

   /**
    * @return string All recipients array 
    */

   public function getRecipients()
   {
      return $this->recipients;
   }

   /**
    * Function to set subject of email
    * Can be added length, remove special charts etc.... 
    * @param string Subject
    * @return boolean
    */
   public function subject($string)
   {
      $this->subject = substr($string, 0, 100);
      return true;
   }


   /**
    * Return current sated subject of string
    * @return string
    */
   public function getSubject()
   {
      return $this->subject;
   }

   /**
    * Function to set text of letter
    * @param string Text of letter 
    * @return boolean 
    */

   public function text($string)
   {
      $this->text = $string;
      return true;
   }

   /*
    * Return current sated subject of string
    * @return string
    */
   public function getText()
   {
      return $this->text;
   }

   
   /**
    * Function to set html of letter
    * @param string valid DOM string
    * @return boolean 
    */

    public function html($string)
    {
      $dom = new \DOMDocument('1.0', 'UTF-8');
      $dom->loadHTML($string);
      
      if (empty(libxml_get_errors()) && $string !== strip_tags($string)) {
         $this->html = $string;
         return true;
      }

       return false;
    }
 
    /*
     * Return current sated subject of string
     * @return string 
     */
    public function getHtml()
    {
       return $this->html;
    }

    /**
     * Function to check if minimum requirements of data already entered
     * @return boolean
     */

     private function checkDataError() {
         if (empty($this->sender) || 
         (empty($this->recipients) || count($this->recipients) == 0) || 
         empty($this->subject) || 
         empty($this->text) && empty($this->html)) {
            return true;
         }

         return false;
     }


    /**
     * Function to send all data via Mailtrap API with simple curl libriary
     * @return boolean 
     */

     private function sentToApi() {

      $data = [
         'from' => $this->sender,
         'to' => $this->recipients,
         'subject' => $this->subject,
         'text' => $this->text,
         // If we send HTML now its buggy and display error with success
         // 'html' => $this->html,
      ];
      $data_string = json_encode($data, JSON_PRETTY_PRINT);
      
      $curl = curl_init();

      curl_setopt_array($curl, [
         CURLOPT_URL => "https://stoplight.io/mocks/railsware/mailtrap-api-docs/100824079/api/send/435",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS => $data_string,
         CURLOPT_HTTPHEADER => [
            "Authorization: Bearer ".$this->getApiKey(),
            "Content-Type: application/json"
         ],
      ]);

      $response = curl_exec($curl);
      $err = curl_error($curl);
      
      curl_close($curl);

      return ['response' => $response, 'error' => $err];
     }


    /**
     * Main function - send all via Mailtrap
     * @param array of params if they was not set by methods
     * @return array with response [success => boolean, messages => array] 
     */

     public function send($params = []) {
      // Set params via incoming array
      if (count($params)) {
         foreach ($params as $method => $value) {
            // Return from send if some params was not set and have some error
            if (! $this->{$method}($value)) return ['success' => false, 'errors' => ['Error on set new params']];
         }
      }

      // Check if all params already set
      if ($this->checkDataError()) {
         return ['success' => false, 'errors' => ['Some of fields not filled']];
      }

      // Send formated json to Mailtrap API

      $response = $this->sentToApi();
      if ($response['error'] ) {
         return ['success' => false, 'errors' => ['Failed to send Mailtrap API']];
      }
      
      return json_decode($response['response'], true);
   }
}
