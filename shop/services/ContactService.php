<?php

namespace shop\services;


use shop\forms\ContactForm;
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