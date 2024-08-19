<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\ruleEngine;

class FinishController extends AbstractController
{
    /**
     * @Route("/api/finish", name="finish", methods={"POST"})
     */
    private $ruleEngine;
     public function __construct(ruleEngine $ruleEngine)
     {
         $this->ruleEngine = $ruleEngine;
     }
    public function finish(Request $request): Response
    {
        // Check if the request contains the required form fields
        if (!$request->files->has('repositoryZip') || !$request->request->has('ciUploadId') || !$request->request->has('debrickedConfig')) {
            return new JsonResponse([
                'error' => 'Missing required form fields.'
            ], Response::HTTP_BAD_REQUEST);
        }

        /** @var UploadedFile $repositoryZip */
        $repositoryZip = $request->files->get('repositoryZip');
        $ciUploadId = $request->request->get('ciUploadId');
        $debrickedConfig = $request->request->get('debrickedConfig');

        $debbrickedApiUrl = $_ENV['DEBRICKED_FINISH_URL'];
        $jwtToken = $_ENV['JWT_TOKEN']; // Fetch from environment variables

        $client = new Client();

        try {
            $response = $client->post($debbrickedApiUrl, [
                'headers' => [
                    'accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $jwtToken,
                    'Cookie' => $_ENV['DEBRICKED_SESSION_COOKIE'] // Fetch from environment variables
                ],
                'multipart' => [
                    [
                        'name' => 'debrickedConfig',
                        'contents' => $debrickedConfig,
                    ],
                    [
                        'name' => 'ciUploadId',
                        'contents' => $ciUploadId,
                    ],
                    [
                        'name' => 'repositoryZip',
                        'contents' => fopen($repositoryZip->getPathname(), 'r'),
                        'filename' => $repositoryZip->getClientOriginalName(),
                    ],
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            $this->ruleEngine->evaluateRules($result);

            return new JsonResponse([
                'message' => 'Request successful',
                'response' => $result
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Error forwarding request',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
