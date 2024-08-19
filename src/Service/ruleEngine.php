<?php
namespace App\Service;

use Symfony\Component\Notifier\Recipient\Recipient;
use App\Service\mailService;

class ruleEngine
{
    private $notifier, $mailService;

    public function __construct( mailService $mailService)
    {
        // $this->notifier = $notifier;
        $this->mailService = $mailService;
    }

    public function evaluateRules(array $scanResult): void
    {
        // Example Rule 1: If vulnerabilities found > X, notify the user
        $vulnerabilityThreshold = 10; 
        if ($scanResult['vulnerabilities'] > $vulnerabilityThreshold) { 
            $this->mailService->sendEmail(
                $_ENV['TO_EMAIL'], 
                'Vulnerabilities Found', 
                'The scan found more than ' . $vulnerabilityThreshold . ' vulnerabilities.'
            );
        }
    }

}
