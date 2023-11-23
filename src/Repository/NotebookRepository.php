<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\CreateNotebookDto;
use App\Entity\Notebook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @extends ServiceEntityRepository<Notebook>
 *
 * @method Notebook|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notebook|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notebook[]    findAll()
 * @method Notebook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotebookRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Notebook::class);
        $this->manager = $manager;
    }

    public function getAll(): array
    {
        $notebooks = $this->findAll();
        $result = [];

        foreach ($notebooks as $notebook) {
            $result[] = $notebook->toArray();
        }

        return $result;
    }

    public function getById(int $id): ?array
    {
        $notebook = $this->find($id);

        if (empty($notebook)) {
            return null;
        }

        return $notebook->toArray();
    }

    public function create(CreateNotebookDto $data): array
    {
        $val = $this->findOneBy(['identifier' => ($data->identifier)]);
        if (!empty($val)) {
            throw new ValidatorException('Entity with same identifier already exists!');
        }

        $notebook = new Notebook();

        $notebook
            ->setIdentifier($data->identifier)
            ->setHeadline($data->headline)
            ->setContent($data->content);

        $this->manager->persist($notebook);
        $this->manager->flush();

        return $this->getById($notebook->getId());
    }

    public function update(int $id, array $data):array
    {
        $notebook = $this->find($id);
        if (empty($notebook))
        {
            throw new EntityNotFoundException(sprintf('Notebook %d not found to be updated', $id));
        }

        empty($data['identifier']) ? true : $notebook->setIdentifier($data['identifier']);
        empty($data['headline']) ? true : $notebook->setHeadline($data['headline']);
        empty($data['content']) ? true : $notebook->setContent($data['content']);

        $this->manager->flush();

        return $notebook->toArray();
    }

    public function deleteNotebook(int $id):void
    {
        $notebook = $this->find($id);
        if (empty($notebook))
        {
            throw new EntityNotFoundException(sprintf('Notebook %d not found to be deleted', $id));
        }
        $this->manager->remove($notebook);
        $this->manager->flush();
    }
}
