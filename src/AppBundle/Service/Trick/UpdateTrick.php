<?php

namespace AppBundle\Service\Trick;

use AppBundle\Entity\User;
use AppBundle\Entity\Trick;
use AppBundle\Form\Trick\AddType;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Update a Trick.
 */
class UpdateTrick
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * Constructor.
     *
     * @param ObjectManager        $entityManager
     * @param SessionInterface     $session
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        ObjectManager $entityManager,
        SessionInterface $session,
        FormFactoryInterface $formFactory
    ) {
        $this->entityManager = $entityManager;
        $this->flashBag = $session->getFlashBag();
        $this->formFactory = $formFactory;
    }

    /**
     * Update a trick in DB.
     *
     * @param Request $request
     * @param User    $user
     * @param Trick   $trick
     *
     * @return FormView|null
     */
    public function update(Request $request, User $user, Trick $trick): ?FormView
    {
        // 1) build the form
        $form = $this->formFactory->create(AddType::class, $trick);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Add...
            $trick->setModifiedBy($user);

            // 5) save the Trick
            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            // Add a flash message
            $this->flashBag->add(
                'update_trick',
                [
                    'type' => 'success',
                    'title' => 'Le trick est bien mis à jour!',
                    'message' => '',
                ]
            );

            return null;
        }
        // Return the form
        return $form->createView();
    }
}