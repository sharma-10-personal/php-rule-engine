<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiResponseRepository")
 */
class ApiResponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ciUploadId;

    /**
     * @ORM\Column(type="integer")
     */
    private $uploadProgramsFileId;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalScans;

    /**
     * @ORM\Column(type="integer")
     */
    private $remainingScans;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $percentage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $estimatedDaysLeft;

    // getters and setters
}
