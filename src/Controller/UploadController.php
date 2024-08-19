<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

class UploadController extends AbstractController
{
   /**
     * @Route("/api/upload", name="upload", methods={"POST"})
     */
    public function upload(Request $request): Response
    {
        // Check if the request contains 'fileData'
        if (!$request->files->has('fileData')) {
            return new JsonResponse([
                'error' => 'No file uploaded.'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Extract file and other form data
        $file = $request->files->get('fileData');
        $repositoryUrl = $request->request->get('repositoryUrl');
        $fileRelativePath = $request->request->get('fileRelativePath');
        $branchName = $request->request->get('branchName');
        $defaultBranchName = $request->request->get('defaultBranchName');
        $releaseName = $request->request->get('releaseName');
        $repositoryName = $request->request->get('repositoryName');

        $jwtToken = $_ENV['JWT_TOKEN'] ?? '';
        $debbrickedApiUrl = $_ENV['DEBRICKED_UPLOAD_URL'];

        $client = new Client();

        try {
            $response = $client->post($debbrickedApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $jwtToken,
                    'Accept' => '*/*',
                ],
                'multipart' => [
                    [
                        'name' => 'fileData', // File upload field name
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                    [
                        'name' => 'repositoryUrl',
                        'contents' => $repositoryUrl,
                    ],
                    [
                        'name' => 'fileRelativePath',
                        'contents' => $fileRelativePath,
                    ],
                    [
                        'name' => 'branchName',
                        'contents' => $branchName,
                    ],
                    [
                        'name' => 'defaultBranchName',
                        'contents' => $defaultBranchName,
                    ],
                    [
                        'name' => 'releaseName',
                        'contents' => $releaseName,
                    ],
                    [
                        'name' => 'repositoryName',
                        'contents' => $repositoryName,
                    ],
                ],
            ]);

            $scanResult = json_decode($response->getBody()->getContents(), true);
            
            return new JsonResponse([
                'message' => 'Files uploaded and scan initiated.',
                'responses' => $scanResult
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Error forwarding file',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
