<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\SearchClientType;
use App\DTO\SearchClientDTO;
use App\Enum\Type;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


#[AsLiveComponent('searchClient')]
final class SearchClient extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public ?User $user = null;

    #[LiveProp]
    public array $clientIds = [];

    #[LiveProp]
    public ?string $message = null;

    public function __construct(
        private UserRepository $userRepository,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            SearchclientType::class,
            new SearchclientDTO()
        );
    }

    #[LiveAction]
    public function search(): void
    {
        $this->submitForm();

        $data = $this->getForm()->getData();
        $data->normalize();

        $clients = $this->userRepository->findUserByFieldAndRole($data, Type::CLIENT);

        // Stockage des id des Bookings
        $this->clientIds = array_map(
            static fn(User $client) => $client->getId(),
            $clients
        );
    }

    // reconstruction des résultats à partir de l'id
    public function getResults(): array
    {
        if (empty($this->clientIds)) {
            return [];
        }

        return $this->userRepository->findBy([
            'id' => $this->clientIds
        ]);
    }
}
