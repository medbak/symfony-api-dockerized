<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CreateNotebookDto;
use App\Repository\NotebookRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Exception\ValidatorException;
use Psr\Log\LoggerInterface;

class NotebookController extends AbstractController
{
    public function __construct(
        private readonly NotebookRepository $notebookRepository,
        private readonly LoggerInterface    $logger
    ) {
    }

    public function index(): JsonResponse
    {
        try {
            $notebooks = $this->notebookRepository->getAll();
            return new JsonResponse(['notebooks' => $notebooks], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logger->error($e);
            return new JsonResponse(['message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $notebookId): JsonResponse
    {
        try {
            $notebook = $this->notebookRepository->getById($notebookId);

            if (empty($notebook))
            {
                throw new EntityNotFoundException(sprintf('Notebook %d not found', $notebookId));
            }

            return new JsonResponse(['notebook' => $notebook], Response::HTTP_OK);
        } catch (EntityNotFoundException $e) {
            $this->logger->error($e);
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $this->logger->error($e);
            return new JsonResponse(['message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(#[MapRequestPayload] CreateNotebookDto $createRequestDto): JsonResponse
    {
        try {
            $this->logger->info('start creating a new entity');
            $newNotebook = $this->notebookRepository->create($createRequestDto);

            $this->logger->info('finish creating a new entity');
            return new JsonResponse(
                [
                    'message' => 'Notebook added!',
                    'notebook' => $newNotebook,
                ],
                Response::HTTP_CREATED);

        } catch (ValidatorException $e) {
            $this->logger->error($e);
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            $this->logger->error($e);
            return new JsonResponse(['message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(int $notebookId, Request $request): JsonResponse
    {
        try {
            $this->logger->info('start updating entity '.$notebookId);

            $payload = $request->request->all();
            $notebook = $this->notebookRepository->update($notebookId, $payload);

            $this->logger->info('finish updating'.$notebookId);
            return new JsonResponse(['updatedNotebook' => $notebook], Response::HTTP_OK);

        } catch (EntityNotFoundException $e) {
            $this->logger->error($e);
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $this->logger->error($e);
            return new JsonResponse(['message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(int $notebookId): JsonResponse
    {
        try {
            $this->notebookRepository->deleteNotebook($notebookId);

            $this->logger->info('entity deleted'.$notebookId);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);

        } catch (EntityNotFoundException $e) {
            $this->logger->error($e);
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $this->logger->error($e);
            return new JsonResponse(['message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
