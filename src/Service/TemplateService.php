<?php

namespace App\Service;

use App\Entity\User;

class TemplateService
{
    public function emailResetPasword(): string
    {
        return '
        <DOCTYPE HTML>
        <html style="background-color: #eeeeee;">
            <head>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@300;400&display=swap" rel="stylesheet">
            </head>
            <body>
                <main style="background-color: white;background: white;padding:50px;border-radius:8px;font-family:\'Helvetica\';">
               
                    <h1
                        style="color:#4ae3b5;font-size: 22px;font-weight: 600;font-family:\'Helvetica\';margin-top:25px;"
                    >
                        Hi there !
                    </h1>
                    <h2
                        style="font-size: 16px;font-weight: 300;color:#171332;margin-bottom:50px;font-family:\'Helvetica\';"
                    >
                        Apparently you forgot your password ? 
                         <br/>
                        Don’t worry, happens to the best of us !
                                       
                        <br/><br/>
                        If it\'s not your idea, you can forget this email 🙃
                    </h2>
                    <a href="{{ url(\'app_reset_password\', {token: resetToken.token}) }}"
                        style="border-radius: 8px;text-decoration:none;padding:20px 10px;background-color:#171332;color:white;font-size:16px;font-weight:600;min-width:200px;font-family:\'Helvetica\';text-align: center;display:inline-block;"
                    >
                       Reset my password
                   </a>
                </main>
            </body>
        </html>
        ';
    }

}
