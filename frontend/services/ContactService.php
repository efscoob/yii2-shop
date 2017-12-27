<?php
/**
 * Created by PhpStorm.
 * User: Johnny
 * Date: 09.12.2017
 * Time: 19:34
 */

namespace frontend\services;


use frontend\forms\ContactForm;
use yii\mail\MailerInterface;

class ContactService
{
    private $mailer;
    private $adminEmail;

    public function __construct($adminEmail, MailerInterface $mailer)
    {
        $this->adminEmail = $adminEmail;
        $this->mailer = $mailer;
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     */
    public function sendEmail(ContactForm $form): void
    {
       $send = $this->mailer->compose()
            ->setTo($this->adminEmail)
            ->setFrom([$form->email => $form->name])
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();
       if (!$send) {
           throw new \RuntimeException("Contact message sending error");
       }
    }
}