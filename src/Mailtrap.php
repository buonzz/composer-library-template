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

   /**  @var array string who must receive email, string like email@site.com <John Doe> */
   private $sender = [];

   /**  @var string who must get email, string like email@site.com <John Doe> */
   private $recipient = [];

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
   private function parseEmailRecepients($string) {
      $responce = [];

      preg_match_all('/(?:"?([^"@<>]*)"?\s)?(?:<?([^@\s]+@[^@\s]+\.[^@\s]+)>?)/i', $string, $matches);
      if ($matches && count($matches) > 1){
         foreach ($matches as $match) {
            if ((bool) filter_var($match[2], FILTER_VALIDATE_EMAIL)) {
               array_push($responce, ['name' => $match[1], 'email' => trim($match[2])]);
            }
         }
      }

      return $responce;
   }

   /**
    * Methot to get current actual API key for object
    */

   public function getApiKey()
    {
      return $this->api_key;
    }

   /**
    * Sample method 
    *
    * @param string|array $param1 string or array of strings name and email like "John Doe <email@test.com>" or "email@test.com" 
    *
    * @return string
    */
   public function from($to)
   {
      $to_add = [];
      if (gettype($to) === 'array') {
         foreach($to as $recipient) {
            $parsed = $this->parseEmailRecepients($recipient);
            array_push($to_add, $parsed);
         }
      }
      if (gettype($to) === 'string') {
         $to_add = $this->parseEmailRecepients($to);
      }

      if (count($to_add)) {
         $this->sender = array_merge($this->sender, $to_add);
         return true;
      }
      return false;
   }

   /**
    * @return array All recepients array 
    */
}
