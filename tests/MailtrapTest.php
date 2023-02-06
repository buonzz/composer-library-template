<?php 

namespace Mailtrap\Tests;

require_once('./src/Mailtrap.php');
use Mailtrap;
use PHPUnit\Framework\TestCase;

/**
*  Corresponding Class to test MailtrapEmail class
*
*  @author poratuk
*/
class MailtrapEmailTest extends TestCase
{

  // Poratuk test case -- just need add lib to read this from .env.testcase in Symphony
  private $mailtrap_api_key = '8e18f66875308e0658035ad42a8e5b64'; 
	
  /**
  * Just check if the MailtrapEmail has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testInit()
  {
    $mailer = new Mailtrap\MailtrapEmail($this->mailtrap_api_key);
    $this->assertTrue(is_object($mailer));
    $this->assertSame($this->mailtrap_api_key, $mailer->getApiKey());
    unset($mailer);
  }
  
  /**
  * Just check if the Mailer has no syntax error when getting senders 
  *
  */
  public function testSetSenders()
  {
    $mailer = new Mailtrap\MailtrapEmail();

    $this->assertTrue($mailer->from( ['name' => 'John Doe', 'email' => 'email@test.to']));
    $this->assertSame($mailer->getSenders(), ['name' => 'John Doe', 'email' => 'email@test.to']);

    $this->assertTrue($mailer->from("email@test.to"));
    $this->assertFalse($mailer->from('wrongemail@test'));
    $this->assertSame($mailer->getSenders(), ['name' => '', 'email' => 'email@test.to']);

    $this->assertTrue($mailer->from("John Doe <email2@test.to>"));
    $this->assertFalse($mailer->from("John Doe <email@test>"));
    $this->assertFalse($mailer->from("<email@test>"));
    $this->assertSame($mailer->getSenders(), ['name' => 'John Doe', 'email' => 'email2@test.to']);
    unset($mailer);
  } 


  /**
  * Just check if the Mailer has no syntax error when getting recipients 
  * 1 Simple email
  *    Email with name
  * 2 Multiple recipients, Multiple recipients with names
  * 3 Array like Mailtrap
  */
  public function testSetRecipientsString()
  {
    $mailer = new Mailtrap\MailtrapEmail();
    $this->assertTrue($mailer->to("email@test.to"));
    $this->assertFalse($mailer->to('wrongemail@test'));
    $this->assertSame($mailer->getRecipients(), [['name' => '', 'email' => 'email@test.to']]);

    $this->assertTrue($mailer->to("John Doe <email2@test.to>"));
    $this->assertFalse($mailer->to("John Doe <email2@test>"));
    $this->assertFalse($mailer->to("email@test <>"));
    
    $this->assertSame($mailer->getRecipients(), [['name' => '', 'email' => 'email@test.to'], ['name' => 'John Doe', 'email' => 'email2@test.to']]);
    unset($mailer);
  }

  public function testSetRecipientsList()
  {
    $mailer = new Mailtrap\MailtrapEmail();
    $this->assertTrue($mailer->to('test@mail1.com, test@mail2.com, test@mail3.com'));
    $this->assertSame($mailer->getRecipients(), 
          [['name' => '', 'email' => 'test@mail1.com'],
          ['name' => '', 'email' => 'test@mail2.com'],
          ['name' => '', 'email' => 'test@mail3.com'],
        ]);
    
    $this->assertTrue($mailer->to('New name <test@mailname1.com>, New name 2 <test@mailname2.com>'));
    $this->assertSame($mailer->getRecipients(), 
          [
          ['name' => '', 'email' => 'test@mail1.com'],
          ['name' => '', 'email' => 'test@mail2.com'],
          ['name' => '', 'email' => 'test@mail3.com'],
          ['name' => 'New name', 'email' => 'test@mailname1.com'],
          ['name' => 'New name 2', 'email' => 'test@mailname2.com'],
        ]);
    unset($mailer);
  }

  public function testSetRecipientsArray()
  {
    $mailer = new Mailtrap\MailtrapEmail();
    $this->assertTrue($mailer->to([['name' => 'New name', 'email' => 'test@mailname1.com'],
    ['name' => 'New name 2', 'email' => 'test@mailname2.com']]));
    $this->assertSame($mailer->getRecipients(), [
          ['name' => 'New name', 'email' => 'test@mailname1.com'],
          ['name' => 'New name 2', 'email' => 'test@mailname2.com'],
        ]);

    unset($mailer);
  } 

  /**
   * 
   * Test for added Subject of mail
   */

   public function testSetSubject()
  {
    $mailer = new Mailtrap\MailtrapEmail();
    $this->assertTrue($mailer->subject('Test subject of email!'));
    $this->assertSame($mailer->getSubject(), "Test subject of email!");

    unset($mailer);
  } 

  /**
   * 
   * Test for added simple text of mail
   */

   public function testSetText()
  {
    $mailer = new Mailtrap\MailtrapEmail();
    $this->assertTrue($mailer->text('Test text of email!'));
    $this->assertSame($mailer->getText(), "Test text of email!");
    unset($mailer);
  } 

  /**
   * 
   * Test for added html for mailer and this html must be validated
   */
   public function testSetHtml()
  {
    $mailer = new Mailtrap\MailtrapEmail();
    $this->assertFalse($mailer->html('Test text of email!'));
    $this->assertTrue($mailer->html('<p>Test text of email!</p>'));
    $this->assertSame($mailer->getHTML(), '<p>Test text of email!</p>');
    unset($mailer);
  } 

  /**
   * 
   * Test for sending current email via Mailtrap API
   */
   public function testSendEmail()
  {
    $mailer = new Mailtrap\MailtrapEmail();
    $mailer->from('Andrii Cherytsya<poratuk@mailtrap.io>');
    $mailer->to('poratuk@gmail.com');
    $mailer->subject('Test email from mailtrap');
    $mailer->text('This is test email text');
    $mailer->html('<div style="color: darkgreen"><h1> This is test email</h1></div>');
    $response = $mailer->send();
    
    var_dump($response);
    $this->assertTrue($response['success']);
    $this->assertSame($response['messages'], ['Successfully sended!']);
    unset($mailer);
  } 

  /**
   * 
   * Test for sending email via Mailtrap API with one param only
   */
   public function testSendEmailWithParams()
  {
    $mailer = new Mailtrap\MailtrapEmail();
    $params = [
      'from' => 'poratuk@mailtrap.io',
      'to' => 'poratuk@gmail.com',
      'subject' => 'Test email from mailtrap',
      'text' => 'This is test email text',
      'html' => '<div style="color: darkgreen"><h1> This is test email</h1></div>',
    ];
    $response = $mailer->send($params);

    $this->assertTrue($response['success']);
    $this->assertSame($response['messages'], ['Successfully sended!']);
    unset($mailer);
  } 


  /**
   * 
   * Test for sending current email via Mailtrap API
   */
   public function testErrorOnSendEmail()
  {
    $mailer = new Mailtrap\MailtrapEmail($this->mailtrap_api_key);
    $mailer->from('Andrii Cherytsya<poratuk@mailtrap.io>');
    $mailer->to('poratuk@gmail.com');
    $mailer->subject('Test email from mailtrap');
    $response = $mailer->send();

    $this->assertFalse($response['success']);
    $this->assertArrayHasKey('errors', $response, ['Some of fields not filled']);
    unset($mailer);
  } 
}
