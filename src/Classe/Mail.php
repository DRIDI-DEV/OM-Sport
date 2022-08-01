<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = '377fc5db373a7492051ef7896aa79c80';
    private $api_key_secret = '4209b886b9f87360edc10fbe7db995b6';

    public function send($to_email, $to_name, $subject, $content)
    {

        $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => 'dardourihd89@gmail.com',
                        'Name' => 'Haithem'
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4093978,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,

                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();



    }
}